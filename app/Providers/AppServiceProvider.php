<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\Feedback;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SupportTicket;
use App\Policies\CompanyPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\InterviewInvitationPolicy;
use App\Policies\JobPolicy;
use App\Policies\JobApplicationPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\SupportTicketPolicy;
use App\Services\AI\AIManager;
use App\Services\NotificationService;
use App\Services\AI\AIServiceInterface;
use App\Services\AI\GeminiService;
use App\Services\AI\OpenAIService;
use App\Services\AI\RuleBasedAIService;
use App\Models\SavedJob;
use App\Models\User;
use App\Models\InterviewInvitation;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AIServiceInterface::class, function ($app) {
            return match (config('ai.default')) {
                'openai' => $app->make(OpenAIService::class),
                'gemini' => $app->make(GeminiService::class),
                default => $app->make(RuleBasedAIService::class),
            };
        });

        $this->app->singleton(AIManager::class, function ($app) {
            return new AIManager($app->make(AIServiceInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Job::class, JobPolicy::class);
        Gate::policy(JobApplication::class, JobApplicationPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(InterviewInvitation::class, InterviewInvitationPolicy::class);
        Gate::policy(DatabaseNotification::class, NotificationPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
        Gate::policy(Feedback::class, FeedbackPolicy::class);
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);

        View::composer(['layouts.navigation', 'layouts.admin'], function ($view) {
            $user = auth()->user();
            $savedJobsCount = 0;
            $unreadNotificationsCount = 0;
            $latestNotifications = collect();

            if ($user?->role?->slug === 'job-seeker') {
                try {
                    if (Schema::hasTable('saved_jobs')) {
                        $savedJobsCount = SavedJob::where('user_id', $user->id)->count();
                    }
                } catch (\Throwable) {
                    $savedJobsCount = 0;
                }
            }

            if ($user) {
                try {
                    $notificationService = app(NotificationService::class);
                    $unreadNotificationsCount = $notificationService->unreadCount($user);
                    $latestNotifications = $user->unreadNotifications()->latest()->limit(10)->get();
                } catch (\Throwable) {
                    $unreadNotificationsCount = 0;
                    $latestNotifications = collect();
                }
            }

            $view->with(compact('savedJobsCount', 'unreadNotificationsCount', 'latestNotifications'));
        });
    }
}
