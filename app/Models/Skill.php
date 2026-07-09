<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobSeekerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(JobSeekerProfile::class, 'job_seeker_skills')
            ->withPivot(['proficiency_level', 'years_of_experience'])
            ->withTimestamps();
    }
}
