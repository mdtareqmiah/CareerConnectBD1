<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InterviewInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly array $data)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'interview_invitation',
            'title' => $this->data['title'] ?? 'Interview invitation',
            'message' => $this->data['message'] ?? 'You received an interview invitation.',
            'link' => $this->data['link'] ?? route('job-seeker.interview-invitations.index'),
            'icon' => 'calendar-event',
        ];
    }
}
