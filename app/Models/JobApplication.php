<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'resume_id',
        'cover_letter',
        'status',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }

    public function scopeForEmployer($query, User $employer)
    {
        return $query->whereHas('job.company', fn ($query) => $query->where('employer_id', $employer->id));
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'reviewed' => 'Reviewed',
            'shortlisted' => 'Shortlisted',
            'rejected' => 'Rejected',
            'accepted' => 'Hired',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst($this->status);
    }

    public function statusBadge(): string
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $this->status_badge_color,
            e($this->status_label)
        );
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'secondary',
            'reviewed' => 'info',
            'shortlisted' => 'primary',
            'rejected' => 'danger',
            'accepted' => 'success',
            default => 'secondary',
        };
    }
}
