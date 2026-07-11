<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Job;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'company_name',
        'company_logo',
        'industry',
        'company_size',
        'founded_year',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'company_description',
    ];

    /**
     * Get the employer (user) that owns the company.
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function jobs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $company) {
            if ($company->company_logo && Storage::disk('public')->exists("company-logos/{$company->company_logo}")) {
                Storage::disk('public')->delete("company-logos/{$company->company_logo}");
            }
        });
    }

    /**
     * Get the company logo URL.
     */
    public function getCompanyLogoUrlAttribute(): string
    {
        if ($this->company_logo && Storage::disk('public')->exists("company-logos/{$this->company_logo}")) {
            return Storage::url("company-logos/{$this->company_logo}");
        }

        return asset('images/company-logo-placeholder.svg');
    }
}
