<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification implements ShouldQueue
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
            'type' => 'application_status_changed',
            'title' => $this->data['title'] ?? 'Application status updated',
            'message' => $this->data['message'] ?? 'The status of one of your applications changed.',
            'link' => $this->data['link'] ?? route('job-seeker.applications.index'),
            'icon' => 'check-circle',
        ];
    }
}
