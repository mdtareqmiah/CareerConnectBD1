<?php

namespace App\Models;

use App\Models\User;
use App\Relations\ProfileSkillsRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class JobSeekerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'professional_title',
        'professional_summary',
        'current_job_title',
        'current_company',
        'years_of_experience',
        'expected_salary',
        'preferred_job_type',
        'preferred_workplace',
        'preferred_location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'website_url',
        'profile_photo',
        'is_profile_completed',
        'is_available_for_work',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_profile_completed' => 'boolean',
        'is_available_for_work' => 'boolean',
        'years_of_experience' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function skills(): ProfileSkillsRelation
    {
        return new ProfileSkillsRelation(
            $this->newRelatedInstance(Skill::class)->newQuery(),
            $this,
            'job_seeker_profile_id',
            $this->getKeyName()
        );
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (JobSeekerProfile $profile): void {
            if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
        });
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }

        return asset('images/default-avatar.svg');
    }
}
