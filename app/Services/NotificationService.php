<?php

namespace App\Services;

use App\Events\NotificationBroadcasted;
use App\Models\User;
use App\Notifications\AdminAnnouncementNotification;
use App\Notifications\ApplicationStatusChangedNotification;
use App\Notifications\EmployerPostedNewJobNotification;
use App\Notifications\InterviewInvitationNotification;
use App\Notifications\JobAppliedNotification;
use App\Notifications\ResumeReviewedNotification;
use App\Notifications\SystemNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Throwable;

class NotificationService
{
    public function getForUser(User $user, ?string $filter = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        return $query->paginate($perPage)->appends(['filter' => $filter]);
    }

    public function getLatestForUser(User $user, int $limit = 10): Collection
    {
        return $user->notifications()->latest()->limit($limit)->get();
    }

    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function markAsRead(DatabaseNotification $notification): bool
    {
        $wasUnread = $notification->read_at === null;

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        if ($wasUnread && $notification->notifiable instanceof User) {
            $this->broadcastUserState($notification->notifiable, [
                'action' => 'read',
                'notification_id' => $notification->id,
            ]);
        }

        return true;
    }

    public function markAllAsRead(User $user): int
    {
        $notifications = $user->unreadNotifications()->get();
        $notificationIds = $notifications->pluck('id')->values()->all();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        if (! empty($notificationIds)) {
            $this->broadcastUserState($user, [
                'action' => 'read_all',
                'notification_ids' => $notificationIds,
            ]);
        }

        return $notifications->count();
    }

    public function delete(DatabaseNotification $notification): bool
    {
        $notificationId = $notification->id;
        $owner = $notification->notifiable;
        $deleted = (bool) $notification->delete();

        if ($deleted && $owner instanceof User) {
            $this->broadcastUserState($owner, [
                'action' => 'deleted',
                'notification_id' => $notificationId,
            ]);
        }

        return $deleted;
    }

    public function notifyUsersByRole(string $roleSlug, string $type, array $data): int
    {
        $users = User::query()
            ->whereHas('role', fn ($query) => $query->where('slug', $roleSlug))
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->get();

        if ($users->isEmpty()) {
            return 0;
        }

        NotificationFacade::send($users, $this->makeNotification($type, $data));

        foreach ($users as $user) {
            $this->broadcastLatestNotification($user);
        }

        return $users->count();
    }

    public function notifyJobApplied(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new JobAppliedNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifyApplicationStatusChanged(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new ApplicationStatusChangedNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifyEmployerPostedNewJob(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new EmployerPostedNewJobNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifyResumeReviewed(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new ResumeReviewedNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifyAdminAnnouncement(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new AdminAnnouncementNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifySystem(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new SystemNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    public function notifyInterviewInvitation(User $recipient, array $data): int
    {
        NotificationFacade::send($recipient, new InterviewInvitationNotification($data));
        $this->broadcastLatestNotification($recipient);

        return 1;
    }

    private function broadcastLatestNotification(User $user): void
    {
        if (! $this->shouldBroadcastRealtime()) {
            return;
        }

        $notification = $user->notifications()->latest()->first();

        if (! $notification) {
            return;
        }

        $data = is_array($notification->data) ? $notification->data : [];

        try {
            event(new NotificationBroadcasted($user, [
                'kind' => 'notification.created',
                'unread_count' => $this->unreadCount($user),
                'notification' => [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'link' => $data['link'] ?? route('notifications.index'),
                    'is_unread' => $notification->read_at === null,
                    'created_at' => $notification->created_at?->toISOString(),
                    'created_human' => $notification->created_at?->diffForHumans(),
                ],
            ]));
        } catch (Throwable $exception) {
            $this->logBroadcastFailure($user, $exception, 'notification.created');
        }
    }

    private function broadcastUserState(User $user, array $payload = []): void
    {
        if (! $this->shouldBroadcastRealtime()) {
            return;
        }

        try {
            event(new NotificationBroadcasted($user, array_merge([
                'kind' => 'notification.state',
                'unread_count' => $this->unreadCount($user),
            ], $payload)));
        } catch (Throwable $exception) {
            $this->logBroadcastFailure($user, $exception, 'notification.state');
        }
    }

    private function shouldBroadcastRealtime(): bool
    {
        return (bool) config('broadcasting.realtime_enabled', false)
            && ! in_array(config('broadcasting.default'), ['null', 'log'], true);
    }

    private function logBroadcastFailure(User $user, Throwable $exception, string $kind): void
    {
        if (! $exception instanceof BroadcastException) {
            throw $exception;
        }

        Log::warning('Realtime notification broadcast failed.', [
            'user_id' => $user->id,
            'kind' => $kind,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }

    private function makeNotification(string $type, array $data): object
    {
        return match ($type) {
            'job_applied' => new JobAppliedNotification($data),
            'application_status_changed' => new ApplicationStatusChangedNotification($data),
            'employer_posted_job' => new EmployerPostedNewJobNotification($data),
            'resume_reviewed' => new ResumeReviewedNotification($data),
            'admin_announcement' => new AdminAnnouncementNotification($data),
            default => new SystemNotification($data),
        };
    }
}
