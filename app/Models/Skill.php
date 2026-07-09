<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_seeker_profile_id',
        'skill_name',
        'proficiency_level',
        'years_of_experience',
        'notes',
        'name',
        'slug',
        'category',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $skill): void {
            if (empty($skill->skill_name) && ! empty($skill->name)) {
                $skill->skill_name = $skill->name;
            }

            if (empty($skill->proficiency_level)) {
                $skill->proficiency_level = 'Beginner';
            }
        });
    }

    protected $casts = [
        'years_of_experience' => 'integer',
    ];

    public function jobSeekerProfile(): BelongsTo
    {
        return $this->belongsTo(JobSeekerProfile::class);
    }
}
