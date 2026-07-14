<?php

namespace App\Providers;

use App\Broadcasting\NotificationChannel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['web', 'auth']]);
        Broadcast::channel('notifications.{userId}', NotificationChannel::class);
    }
}
