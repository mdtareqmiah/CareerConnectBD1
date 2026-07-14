<?php

namespace App\Policies;

use App\Models\InterviewInvitation;
use App\Models\User;

class InterviewInvitationPolicy
{
    public function respond(User $user, InterviewInvitation $invitation): bool
    {
        return $user->role?->slug === 'job-seeker' && $invitation->candidate_id === $user->id;
    }
}
