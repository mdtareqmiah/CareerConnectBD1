<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Feedback;
use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CommunicationCenterTest extends TestCase
{
    use RefreshDatabase;

    private function role(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function user(string $roleSlug): User
    {
        $name = match ($roleSlug) {
            'admin' => 'Admin',
            'employer' => 'Employer',
            default => 'Job Seeker',
        };

        return User::factory()->create([
            'role_id' => $this->role($roleSlug, ucfirst(str_replace('-', ' ', $roleSlug)))->id,
            'is_active' => true,
        ]);
    }

    public function test_public_contact_form_submission_creates_record_and_queues_emails(): void
    {
        Mail::fake();
        Notification::fake();

        $admin = $this->user('admin');

        $response = $this->post(route('contact.store'), [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '01700000000',
            'subject' => 'Need help',
            'category' => 'technical',
            'message' => 'I need help with profile verification process.',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('contact_messages', [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Need help',
            'status' => 'unread',
        ]);

        $this->assertDatabaseHas('email_logs', [
            'recipient' => 'john@example.com',
            'type' => 'contact_acknowledgement',
        ]);

        $this->assertDatabaseHas('email_logs', [
            'recipient' => $admin->email,
            'type' => 'admin_reply',
        ]);
    }

    public function test_authenticated_user_can_submit_feedback_and_view_own_feedback(): void
    {
        Notification::fake();

        $user = $this->user('job-seeker');
        $this->user('admin');

        $response = $this->actingAs($user)->post(route('feedback.store'), [
            'type' => 'suggestion',
            'subject' => 'Improve search',
            'message' => 'Please add advanced salary filter in job search.',
            'rating' => 4,
        ]);

        $feedback = Feedback::first();

        $response->assertRedirect(route('feedback.show', $feedback));

        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)->get(route('feedback.index'))->assertOk();
        $this->actingAs($user)->get(route('feedback.show', $feedback))->assertOk();
    }

    public function test_user_cannot_access_other_users_feedback(): void
    {
        $owner = $this->user('job-seeker');
        $other = $this->user('employer');

        $feedback = Feedback::create([
            'user_id' => $owner->id,
            'type' => 'bug_report',
            'subject' => 'Issue',
            'message' => 'A sample bug report message.',
            'rating' => 3,
            'status' => 'pending',
        ]);

        $this->actingAs($other)->get(route('feedback.show', $feedback))->assertForbidden();
    }

    public function test_user_can_create_support_ticket_and_reply(): void
    {
        Notification::fake();

        $user = $this->user('job-seeker');
        $this->user('admin');

        $createResponse = $this->actingAs($user)->post(route('support-tickets.store'), [
            'category' => 'technical',
            'priority' => 'high',
            'subject' => 'Cannot upload resume',
            'message' => 'Upload fails with unknown error every time.',
        ]);

        $ticket = SupportTicket::first();

        $createResponse->assertRedirect(route('support-tickets.show', $ticket));

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'user_id' => $user->id,
            'status' => 'open',
        ]);

        $replyResponse = $this->actingAs($user)->post(route('support-tickets.replies.store', $ticket), [
            'message' => 'Adding extra details here for support team.',
        ]);

        $replyResponse->assertRedirect(route('support-tickets.show', $ticket));
        $this->assertDatabaseHas('support_ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_id' => $user->id,
        ]);
    }

    public function test_admin_inbox_can_view_and_manage_contacts_feedback_and_tickets(): void
    {
        Mail::fake();
        Notification::fake();

        $admin = $this->user('admin');
        $user = $this->user('job-seeker');

        $contact = ContactMessage::create([
            'full_name' => 'Jane',
            'email' => 'jane@example.com',
            'subject' => 'Question',
            'category' => 'general',
            'message' => 'General contact message.',
            'status' => 'unread',
        ]);

        $feedback = Feedback::create([
            'user_id' => $user->id,
            'type' => 'general_feedback',
            'subject' => 'Great platform',
            'message' => 'Thanks for this platform.',
            'rating' => 5,
            'status' => 'pending',
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => 'TKT-20260714-00001',
            'user_id' => $user->id,
            'category' => 'billing',
            'priority' => 'medium',
            'status' => 'open',
            'subject' => 'Billing query',
            'message' => 'Need invoice details.',
            'last_reply_at' => now(),
        ]);

        $this->actingAs($admin)->get(route('admin.communications.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.feedback.show', $feedback))->assertOk();
        $this->actingAs($admin)->get(route('admin.support-tickets.show', $ticket))->assertOk();

        $this->actingAs($admin)
            ->patch(route('admin.feedback.status', $feedback), ['status' => 'resolved'])
            ->assertRedirect();

        $this->actingAs($admin)
            ->patch(route('admin.support-tickets.status', $ticket), ['status' => 'closed'])
            ->assertRedirect();

        $this->actingAs($admin)
            ->patch(route('admin.communications.contacts.status', $contact), ['status' => 'in_review'])
            ->assertRedirect();

        $this->assertDatabaseHas('feedback', ['id' => $feedback->id, 'status' => 'resolved']);
        $this->assertDatabaseHas('support_tickets', ['id' => $ticket->id, 'status' => 'closed']);
        $this->assertDatabaseHas('contact_messages', ['id' => $contact->id, 'status' => 'in_review']);
    }

    public function test_non_admin_cannot_access_admin_communication_inbox(): void
    {
        $user = $this->user('job-seeker');

        $this->actingAs($user)->get(route('admin.communications.index'))->assertForbidden();
    }
}
