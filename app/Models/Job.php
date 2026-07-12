<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'vacancy',
        'job_type',
        'workplace',
        'employment_status',
        'experience_level',
        'education_level',
        'salary_type',
        'salary_min',
        'salary_max',
        'location',
        'deadline',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'status',
        'published_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'published_at' => 'datetime',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'vacancy' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'job_id');
    }

    public function isSavedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->savedJobs()->where('user_id', $user->id)->exists();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isExpired(): bool
    {
        return $this->status === 'published' && $this->deadline->isBefore(today());
    }

    public function isOpen(): bool
    {
        return $this->isPublished() && ! $this->deadline->isBefore(today());
    }

    public function alreadyAppliedBy(User $user): bool
    {
        return $this->applications()->where('user_id', $user->id)->exists();
    }

    public function scopeForEmployer($query, $employer)
    {
        return $query->whereHas('company', fn ($query) => $query->where('employer_id', $employer->id));
    }

    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($query) use ($term) {
            $query->where('title', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhere('job_type', 'like', "%{$term}%")
                ->orWhere('employment_status', 'like', "%{$term}%")
                ->orWhere('status', 'like', "%{$term}%")
                ->orWhereHas('company', fn ($query) => $query->where('company_name', 'like', "%{$term}%"));
        });
    }

    public function scopeJobTypeFilter($query, ?string $jobType)
    {
        if (empty($jobType)) {
            return $query;
        }

        return $query->where('job_type', $jobType);
    }

    public function scopeSalaryRange($query, ?int $min, ?int $max)
    {
        if (filled($min)) {
            $query->where('salary_max', '>=', $min);
        }

        if (filled($max)) {
            $query->where('salary_min', '<=', $max);
        }

        return $query;
    }

    public function scopeStatusFilter($query, ?string $status)
    {
        return match ($status) {
            'draft' => $query->where('status', 'draft'),
            'published' => $query->where('status', 'published')->whereDate('deadline', '>=', today()),
            'closed' => $query->where('status', 'archived'),
            'expired' => $query->where('status', 'published')->whereDate('deadline', '<', today()),
            default => $query,
        };
    }

    public function scopeSortBy($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'published' && $this->deadline->isBefore(today())) {
            return 'expired';
        }

        if ($this->status === 'archived') {
            return 'closed';
        }

        return $this->status;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->display_status) {
            'draft' => 'secondary',
            'published' => 'success',
            'closed' => 'dark',
            'expired' => 'warning',
            default => 'secondary',
        };
    }

    protected static function booted(): void
    {
        static::creating(function (self $job) {
            if (empty($job->slug)) {
                $job->slug = self::generateUniqueSlug($job->title);
            }

            if ($job->status === 'published' && empty($job->published_at)) {
                $job->published_at = now();
            }
        });

        static::updating(function (self $job) {
            if ($job->isDirty('title') && ! $job->isDirty('slug')) {
                $job->slug = self::generateUniqueSlug($job->title, $job->id);
            }

            if ($job->status === 'published' && empty($job->published_at)) {
                $job->published_at = now();
            }

            if ($job->status !== 'published') {
                $job->published_at = null;
            }
        });
    }

    private static function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $original = $slug;
        $count = 1;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
