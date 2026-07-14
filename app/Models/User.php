<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\Feedback;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the role assigned to the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function jobSeekerProfile(): HasOne
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'employer_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'user_id');
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'user_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'user_id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function hasSavedJob(Job $job): bool
    {
        return $this->savedJobs()->where('job_id', $job->id)->exists();
    }
}
