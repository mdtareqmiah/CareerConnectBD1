<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationFoundationTest extends TestCase
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

    public function test_user_can_view_notification_center_and_filter_unread_notifications(): void
    {
        config(['broadcasting.default' => 'null']);

        $user = $this->createUser();

        $user->notifications()->create([
            'id' => 'notif-1',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Welcome', 'message' => 'Welcome to CareerConnectBD', 'link' => '#'],
        ]);

        $user->notifications()->create([
            'id' => 'notif-2',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Profile Reminder', 'message' => 'Complete your profile', 'link' => '#'],
            'read_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('notifications.index', ['filter' => 'unread']));

        $response->assertOk();
        $response->assertSee('Welcome');
        $response->assertDontSee('Profile Reminder');
    }

    public function test_user_can_mark_single_and_all_notifications_read_and_delete_one(): void
    {
        config(['broadcasting.default' => 'null']);

        $user = $this->createUser();

        $first = $user->notifications()->create([
            'id' => 'notif-3',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'First', 'message' => 'First message', 'link' => '#'],
        ]);

        $second = $user->notifications()->create([
            'id' => 'notif-4',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Second', 'message' => 'Second message', 'link' => '#'],
        ]);

        $this->actingAs($user)->patch(route('notifications.read', ['notification' => $first->id]))
            ->assertRedirect();

        $this->assertNotNull(DatabaseNotification::find($first->id)->read_at);

        $this->actingAs($user)->patch(route('notifications.read-all'))
            ->assertRedirect();

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());

        $this->actingAs($user)->delete(route('notifications.destroy', ['notification' => $second->id]))
            ->assertRedirect();

        $this->assertNull(DatabaseNotification::find($second->id));
    }
}
