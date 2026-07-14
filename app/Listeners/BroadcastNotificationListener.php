<?php

namespace App\Listeners;

use App\Events\NotificationBroadcasted;
use Illuminate\Broadcasting\BroadcastManager;

class BroadcastNotificationListener
{
    public function __construct(private readonly BroadcastManager $broadcastManager)
    {
    }

    public function handle(NotificationBroadcasted $event): void
    {
        $this->broadcastManager->event($event);
    }
}
