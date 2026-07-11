<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Policies\CompanyPolicy;
use App\Policies\JobPolicy;
use App\Policies\JobApplicationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Job::class, JobPolicy::class);
        Gate::policy(JobApplication::class, JobApplicationPolicy::class);
    }
}
