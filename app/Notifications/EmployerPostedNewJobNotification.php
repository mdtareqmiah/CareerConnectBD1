<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class EmployerPostedNewJobNotification extends Notification implements ShouldQueue
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
            'type' => 'employer_posted_job',
            'title' => $this->data['title'] ?? 'New job posted',
            'message' => $this->data['message'] ?? 'A new job has just been posted.',
            'link' => $this->data['link'] ?? route('jobs.index'),
            'icon' => 'megaphone',
        ];
    }
}
