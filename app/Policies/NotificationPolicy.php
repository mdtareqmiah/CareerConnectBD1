<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role?->slug === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role !== null;
    }

    public function view(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable_id === $user->id
            && $notification->notifiable_type === User::class;
    }

    public function update(User $user, DatabaseNotification $notification): bool
    {
        return $this->view($user, $notification);
    }

    public function delete(User $user, DatabaseNotification $notification): bool
    {
        return $this->view($user, $notification);
    }
}
