<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
