<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->slug !== null;
    }

    public function view(User $user, SupportTicket $supportTicket): bool
    {
        if ($user->role?->slug === 'admin') {
            return true;
        }

        return $supportTicket->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role?->slug !== 'admin';
    }

    public function update(User $user, SupportTicket $supportTicket): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function delete(User $user, SupportTicket $supportTicket): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function reply(User $user, SupportTicket $supportTicket): bool
    {
        return $user->role?->slug === 'admin' || $supportTicket->user_id === $user->id;
    }
}
