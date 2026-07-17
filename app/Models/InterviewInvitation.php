<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_id',
        'employer_id',
        'subject',
        'interview_at',
        'meeting_link',
        'location',
        'notes',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'interview_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function scopeForCandidate($query, User $candidate)
    {
        return $query->where('candidate_id', $candidate->id);
    }

    public function scopeForEmployer($query, User $employer)
    {
        return $query->where('employer_id', $employer->id);
    }

    public static function statuses(): array
    {
        return ['pending', 'accepted', 'declined', 'cancelled'];
    }
}
