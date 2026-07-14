<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Contracts\Authentication\Authenticatable;

class NotificationChannel
{
    public function join(Authenticatable $user, int|string $userId): array|bool
    {
        if (! $user instanceof User) {
            return false;
        }

        if ((int) $user->id !== (int) $userId) {
            return false;
        }

        return ['id' => $user->id, 'name' => $user->name];
    }
}
