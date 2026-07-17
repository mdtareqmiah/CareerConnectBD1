<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->slug !== null;
    }

    public function view(User $user, Feedback $feedback): bool
    {
        if ($user->role?->slug === 'admin') {
            return true;
        }

        return $feedback->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role?->slug !== 'admin';
    }

    public function update(User $user, Feedback $feedback): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->role?->slug === 'admin';
    }
}
