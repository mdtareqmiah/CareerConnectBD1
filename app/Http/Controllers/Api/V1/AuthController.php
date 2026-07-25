<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseApiController
{
    public function user(Request $request): JsonResponse
    {
        $user = $request->user()?->loadMissing(['role', 'jobSeekerProfile', 'company']);

        if (! $user instanceof User) {
            return $this->unauthorized('Unauthenticated.');
        }

        return $this->success($this->transformUser($user), 'Authenticated user retrieved.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();


        $user = $request->user()?->loadMissing(['role', 'jobSeekerProfile', 'company']);

        if (! $user instanceof User) {
            return $this->unauthorized('Unauthenticated.');
        }

        return $this->success($this->transformUser($user), 'Logged in successfully.');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->success(null, 'Logged out successfully.');
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->slug,
            'profile' => $this->profilePayload($user),
        ];
    }

    private function profilePayload(User $user): ?array
    {
        if ($user->role?->slug === 'job-seeker' && $user->jobSeekerProfile) {
            return [
                'id' => $user->jobSeekerProfile->id,
                'first_name' => $user->jobSeekerProfile->first_name,
                'last_name' => $user->jobSeekerProfile->last_name,
                'phone' => $user->jobSeekerProfile->phone,
                'current_job_title' => $user->jobSeekerProfile->current_job_title,
                'current_company' => $user->jobSeekerProfile->current_company,
                'is_profile_completed' => $user->jobSeekerProfile->is_profile_completed,
            ];
        }

        if ($user->role?->slug === 'employer' && $user->company) {
            return [
                'id' => $user->company->id,
                'company_name' => $user->company->company_name,
                'verification_status' => $user->company->verification_status,
                'website' => $user->company->website,
            ];
        }

        return null;
    }
}
