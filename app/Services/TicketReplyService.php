<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class TicketReplyService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly MailService $mailService,
    ) {
    }

    public function createReply(SupportTicket $ticket, User $sender, array $data): SupportTicketMessage
    {
        $reply = $ticket->messages()->create([
            'sender_id' => $sender->id,
            'message' => $data['message'],
            'attachment_path' => $this->storeAttachment($data['attachment'] ?? null),
        ]);

        $isAdmin = $sender->role?->slug === 'admin';

        $ticket->update([
            'status' => $isAdmin ? 'answered' : 'pending',
            'assigned_admin_id' => $isAdmin ? ($ticket->assigned_admin_id ?: $sender->id) : $ticket->assigned_admin_id,
            'last_reply_at' => now(),
        ]);

        if ($isAdmin) {
            $this->notificationService->notifySystem($ticket->user, [
                'title' => 'Admin replied to your support ticket',
                'message' => 'Admin replied to your support ticket: '.$ticket->subject.'.',
                'link' => route('support-tickets.show', $ticket),
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'reply_id' => $reply->id,
            ]);

            $this->mailService->send('support_ticket_reply', $ticket->user->email, [
                'subject' => "Support reply: {$ticket->ticket_number}",
                'message' => $data['message'],
                'url' => route('support-tickets.show', $ticket),
            ], $sender);
        } else {
            $this->notificationService->notifyUsersByRole('admin', 'system', [
                'title' => 'Support ticket received new reply',
                'message' => "{$ticket->ticket_number} has a new customer reply.",
                'link' => route('admin.support-tickets.show', $ticket),
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'reply_id' => $reply->id,
            ]);
        }

        return $reply->load('sender');
    }

    private function storeAttachment(mixed $attachment): ?string
    {
        if (! $attachment instanceof UploadedFile) {
            return null;
        }

        return $attachment->store('support-ticket-replies', 'public');
    }
}
