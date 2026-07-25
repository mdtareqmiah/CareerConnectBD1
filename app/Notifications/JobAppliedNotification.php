<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobAppliedNotification extends Notification
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
        return array_merge([
            'type' => 'job_applied',
            'title' => $this->data['title'] ?? 'New job application',
            'message' => $this->data['message'] ?? 'A candidate applied for one of your jobs.',
            'link' => $this->data['link'] ?? route('employer.applications.index'),
            'icon' => 'briefcase',
        ], $this->data);
    }
}
