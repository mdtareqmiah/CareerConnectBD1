<?php

namespace Tests\Feature;

use App\Events\NotificationBroadcasted;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotificationRealtimeTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'role_id' => $this->createRole('job-seeker', 'Job Seeker')->id,
            'is_active' => true,
        ]);
    }

    public function test_service_dispatches_realtime_event_when_notification_created(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.realtime_enabled' => true]);
        Event::fake([NotificationBroadcasted::class]);

        $user = $this->createUser();
        $service = app(NotificationService::class);

        $service->notifySystem($user, [
            'title' => 'Realtime Title',
            'message' => 'Realtime Message',
            'link' => route('notifications.index'),
        ]);

        Event::assertDispatched(NotificationBroadcasted::class, function (NotificationBroadcasted $event) use ($user) {
            return $event->user->is($user)
                && ($event->payload['kind'] ?? null) === 'notification.created'
                && ($event->payload['unread_count'] ?? null) === 1;
        });
    }

    public function test_service_dispatches_realtime_event_when_notifications_are_marked_read(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.realtime_enabled' => true]);
        Event::fake([NotificationBroadcasted::class]);

        $user = $this->createUser();
        $notification = $user->notifications()->create([
            'id' => 'realtime-read-notif',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Read Me', 'message' => 'Mark as read', 'link' => '#'],
        ]);

        $service = app(NotificationService::class);
        $service->markAsRead($notification);

        Event::assertDispatched(NotificationBroadcasted::class, function (NotificationBroadcasted $event) use ($user, $notification) {
            return $event->user->is($user)
                && ($event->payload['kind'] ?? null) === 'notification.state'
                && ($event->payload['action'] ?? null) === 'read'
                && ($event->payload['notification_id'] ?? null) === $notification->id
                && ($event->payload['unread_count'] ?? null) === 0;
        });
    }
}
