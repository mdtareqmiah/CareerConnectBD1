<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ResumeReviewedNotification extends Notification implements ShouldQueue
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
            'type' => 'resume_reviewed',
            'title' => $this->data['title'] ?? 'Resume reviewed',
            'message' => $this->data['message'] ?? 'Your resume has been reviewed.',
            'link' => $this->data['link'] ?? route('job-seeker.resumes.index'),
            'icon' => 'file-earmark-text',
        ];
    }
}
