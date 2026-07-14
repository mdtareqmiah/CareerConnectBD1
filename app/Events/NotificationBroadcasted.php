<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationBroadcasted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $user,
        public array $payload
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.'.$this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
