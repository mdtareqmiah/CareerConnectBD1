<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'job_seeker_profile_id',
        'degree',
        'field_of_study',
        'institution_name',
        'board_or_university',
        'education_level',
        'result',
        'grading_system',
        'passing_year',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'passing_year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function jobSeekerProfile(): BelongsTo
    {
        return $this->belongsTo(JobSeekerProfile::class);
    }
}
