<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class SupportTicketService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly MailService $mailService,
    ) {
    }

    public function create(User $user, array $data): SupportTicket
    {
        $ticket = SupportTicket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'user_id' => $user->id,
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => 'open',
            'subject' => $data['subject'],
            'message' => $data['message'],
            'last_reply_at' => now(),
        ]);

        $ticket->messages()->create([
            'sender_id' => $user->id,
            'message' => $data['message'],
            'attachment_path' => $this->storeAttachment($data['attachment'] ?? null),
        ]);

        $this->notificationService->notifyUsersByRole('admin', 'system', [
            'title' => 'New support ticket received',
            'message' => 'New support ticket received: '.$ticket->subject.'.',
            'link' => route('admin.support-tickets.show', $ticket),
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
            'created_by_id' => $ticket->user_id,
            'created_by_name' => $user->name,
        ]);

        return $ticket->load('messages.sender');
    }

    public function paginateForUser(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return SupportTicket::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function paginateForAdmin(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = SupportTicket::query()->with('user');

        if (! empty($filters['q'])) {
            $search = trim((string) $filters['q']);
            $query->where(function ($builder) use ($search) {
                $builder->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest('created_at');
        } elseif ($sort === 'priority') {
            $query->orderByRaw("FIELD(priority, 'urgent','high','medium','low')")->latest('created_at');
        } else {
            $query->latest('created_at');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function updateStatus(SupportTicket $ticket, string $status, User $admin): SupportTicket
    {
        $previousStatus = $ticket->status;

        $ticket->update([
            'status' => $status,
            'assigned_admin_id' => $ticket->assigned_admin_id ?: $admin->id,
            'resolved_at' => $status === 'resolved' ? now() : $ticket->resolved_at,
            'closed_at' => $status === 'closed' ? now() : null,
        ]);

        if ($previousStatus !== $status) {
            $this->notificationService->notifySystem($ticket->user, [
                'title' => 'Support ticket status updated',
                'message' => "Your support ticket '{$ticket->subject}' is now {$status}.",
                'link' => route('support-tickets.show', $ticket),
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'status' => $status,
            ]);
        }

        if ($status === 'closed') {
            $this->mailService->send('support_ticket_closed', $ticket->user->email, [
                'subject' => "Ticket closed: {$ticket->ticket_number}",
                'message' => "Your support ticket {$ticket->ticket_number} has been closed.",
                'url' => route('support-tickets.show', $ticket),
            ], $admin);
        }

        return $ticket;
    }

    public function delete(SupportTicket $ticket): bool
    {
        return (bool) $ticket->delete();
    }

    private function generateTicketNumber(): string
    {
        $nextId = (int) (SupportTicket::query()->max('id') ?? 0) + 1;

        return 'TKT-'.now()->format('Ymd').'-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    private function storeAttachment(mixed $attachment): ?string
    {
        if (! $attachment instanceof UploadedFile) {
            return null;
        }

        return $attachment->store('support-ticket-attachments', 'public');
    }
}
