<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_seeker_profile_id',
        'title',
        'professional_summary',
        'personal_information',
        'education',
        'experience',
        'skills',
        'projects',
        'certifications',
        'languages',
        'references',
        'social_links',
        'template',
        'status',
        'is_default',
    ];

    protected $casts = [
        'personal_information' => 'array',
        'education' => 'array',
        'experience' => 'array',
        'skills' => 'array',
        'projects' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'references' => 'array',
        'social_links' => 'array',
        'is_default' => 'boolean',
    ];

    public function jobSeekerProfile(): BelongsTo
    {
        return $this->belongsTo(JobSeekerProfile::class);
    }
}
