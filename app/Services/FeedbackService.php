<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class FeedbackService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly MailService $mailService,
    ) {
    }

    public function create(User $user, array $data): Feedback
    {
        $attachmentPath = null;

        if (($data['attachment'] ?? null) instanceof UploadedFile) {
            $attachmentPath = $data['attachment']->store('feedback-attachments', 'public');
        }

        $feedback = Feedback::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'rating' => (int) $data['rating'],
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        $this->notificationService->notifyUsersByRole('admin', 'system', [
            'title' => 'New feedback received',
            'message' => 'New feedback received from '.$user->name.'.',
            'link' => route('admin.communications.index', ['tab' => 'feedback']),
            'feedback_id' => $feedback->id,
            'feedback_subject' => $feedback->subject,
            'feedback_type' => $feedback->type,
            'submitted_by_id' => $user->id,
            'submitted_by_name' => $user->name,
        ]);

        return $feedback;
    }

    public function paginateForUser(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return Feedback::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function paginateForAdmin(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Feedback::query()->with('user');

        if (! empty($filters['q'])) {
            $search = trim((string) $filters['q']);
            $query->where(function ($builder) use ($search) {
                $builder->where('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest('created_at');
        } elseif ($sort === 'rating') {
            $query->orderByDesc('rating')->latest('created_at');
        } else {
            $query->latest('created_at');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function updateStatus(Feedback $feedback, string $status, User $admin): Feedback
    {
        $feedback->update([
            'status' => $status,
            'assigned_admin_id' => $feedback->assigned_admin_id ?: $admin->id,
            'resolved_at' => $status === 'resolved' ? now() : $feedback->resolved_at,
            'closed_at' => $status === 'closed' ? now() : $feedback->closed_at,
        ]);

        if ($status === 'resolved' || $status === 'closed') {
            $this->notificationService->notifySystem($feedback->user, [
                'title' => 'Feedback status updated',
                'message' => "Your feedback '{$feedback->subject}' is now {$status}.",
                'link' => route('feedback.show', $feedback),
                'feedback_id' => $feedback->id,
                'feedback_subject' => $feedback->subject,
                'status' => $status,
            ]);

            $this->mailService->send('feedback_reply', $feedback->user->email, [
                'subject' => "Feedback {$status}: {$feedback->subject}",
                'message' => "Your feedback has been marked as {$status} by our support team.",
                'url' => route('feedback.show', $feedback),
            ], $admin);
        }

        return $feedback;
    }

    public function delete(Feedback $feedback): bool
    {
        return (bool) $feedback->delete();
    }
}
