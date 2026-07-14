<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification implements ShouldQueue
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
            'type' => 'system',
            'title' => $this->data['title'] ?? 'System update',
            'message' => $this->data['message'] ?? 'A system notification is available.',
            'link' => $this->data['link'] ?? route('notifications.index'),
            'icon' => 'info-circle',
        ];
    }
}
