<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role?->slug === 'employer' || $user->role?->slug === 'job-seeker';
    }

    public function view(User $user, JobApplication $jobApplication): bool
    {
        if ($user->id === $jobApplication->user_id) {
            return true;
        }

        return $user->id === $jobApplication->job->company->employer_id;
    }

    public function create(User $user): bool
    {
        return $user->role?->slug === 'job-seeker';
    }

    public function update(User $user, JobApplication $jobApplication): bool
    {
        return $user->id === $jobApplication->user_id;
    }

    public function updateStatus(User $user, JobApplication $jobApplication): bool
    {
        return $user->role?->slug === 'employer' && $user->id === $jobApplication->job->company->employer_id;
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->id === $jobApplication->user_id;
    }
}
