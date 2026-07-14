<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminAnnouncementNotification extends Notification implements ShouldQueue
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
            'type' => 'admin_announcement',
            'title' => $this->data['title'] ?? 'New announcement',
            'message' => $this->data['message'] ?? 'An administrator posted an announcement.',
            'link' => $this->data['link'] ?? route('notifications.index'),
            'icon' => 'megaphone',
        ];
    }
}
