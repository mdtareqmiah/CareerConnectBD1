<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly MailService $mailService,
    ) {
    }

    public function create(array $data): ContactMessage
    {
        $contact = ContactMessage::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'category' => $data['category'],
            'message' => $data['message'],
            'status' => 'unread',
        ]);

        $adminUsers = User::query()
            ->whereHas('role', fn ($query) => $query->where('slug', 'admin'))
            ->get();

        foreach ($adminUsers as $admin) {
            $this->notificationService->notifySystem($admin, [
                'title' => 'New contact message',
                'message' => $contact->subject,
                'link' => route('admin.communications.index', ['tab' => 'contacts']),
            ]);

            $this->mailService->send('admin_reply', $admin->email, [
                'subject' => 'New Contact Message: '.$contact->subject,
                'message' => "A new contact message was received from {$contact->full_name} ({$contact->email}).",
                'url' => route('admin.communications.index', ['tab' => 'contacts']),
            ]);
        }

        $this->mailService->send('contact_acknowledgement', $contact->email, [
            'subject' => 'We received your message',
            'message' => 'Thank you for contacting CareerConnectBD. Our team will reply shortly.',
            'url' => route('contact.create'),
        ]);

        return $contact;
    }

    public function paginateForAdmin(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = ContactMessage::query();

        if (! empty($filters['q'])) {
            $search = trim((string) $filters['q']);
            $query->where(function ($builder) use ($search) {
                $builder->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest('created_at');
        } else {
            $query->latest('created_at');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function updateStatus(ContactMessage $contactMessage, string $status, User $admin): ContactMessage
    {
        $contactMessage->update([
            'status' => $status,
            'assigned_admin_id' => $contactMessage->assigned_admin_id ?: $admin->id,
            'read_at' => $status !== 'unread' ? now() : null,
            'closed_at' => $status === 'closed' ? now() : null,
        ]);

        return $contactMessage;
    }

    public function reply(ContactMessage $contactMessage, User $admin, string $subject, string $message): void
    {
        $this->mailService->send('admin_reply', $contactMessage->email, [
            'subject' => $subject,
            'message' => $message,
            'url' => route('contact.create'),
        ], $admin);

        $contactMessage->update([
            'status' => 'replied',
            'assigned_admin_id' => $contactMessage->assigned_admin_id ?: $admin->id,
            'read_at' => $contactMessage->read_at ?? now(),
            'replied_at' => now(),
        ]);
    }

    public function delete(ContactMessage $contactMessage): bool
    {
        return (bool) $contactMessage->delete();
    }
}
