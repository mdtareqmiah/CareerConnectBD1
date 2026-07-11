<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Job $job): bool
    {
        return $user->id === $job->company->employer_id;
    }

    public function create(User $user): bool
    {
        return $user->role?->slug === 'employer';
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->company->employer_id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id === $job->company->employer_id;
    }

    public function restore(User $user, Job $job): bool
    {
        return $user->id === $job->company->employer_id;
    }

    public function forceDelete(User $user, Job $job): bool
    {
        return $user->id === $job->company->employer_id;
    }
}
