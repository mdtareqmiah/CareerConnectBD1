<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ApplicationManagementController;
use App\Http\Controllers\Admin\CompanyManagementController;
use App\Http\Controllers\Admin\EmployerManagementController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user?->role?->slug === 'job-seeker') {
        return redirect()->route('job-seeker.dashboard');
    }

    if ($user?->role?->slug === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user?->role?->slug === 'employer') {
        return redirect()->route('employer.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:employer'])->group(function () {
    Route::get('/employer', function () {
        $user = Auth::user();

        if ($user->company) {
            return redirect()->route('employer.dashboard');
        }

        return redirect()->route('company.create');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::match(['put', 'patch'], '/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{userId}/restore', [UserManagementController::class, 'restore'])
            ->whereNumber('userId')
            ->name('users.restore');
        Route::get('/employers', [EmployerManagementController::class, 'index'])->name('employers.index');
        Route::get('/employers/{employer}', [EmployerManagementController::class, 'show'])
            ->whereNumber('employer')
            ->name('employers.show');

        Route::get('/companies', [CompanyManagementController::class, 'index'])->name('companies.index');
        Route::get('/companies/{company}', [CompanyManagementController::class, 'show'])
            ->whereNumber('company')
            ->name('companies.show');
        Route::get('/companies/{company}/edit', [CompanyManagementController::class, 'edit'])
            ->whereNumber('company')
            ->name('companies.edit');
        Route::match(['put', 'patch'], '/companies/{company}', [CompanyManagementController::class, 'update'])
            ->whereNumber('company')
            ->name('companies.update');
        Route::patch('/companies/{company}/approve', [CompanyManagementController::class, 'approve'])
            ->whereNumber('company')
            ->name('companies.approve');
        Route::patch('/companies/{company}/reject', [CompanyManagementController::class, 'reject'])
            ->whereNumber('company')
            ->name('companies.reject');
        Route::patch('/companies/{company}/suspend', [CompanyManagementController::class, 'suspend'])
            ->whereNumber('company')
            ->name('companies.suspend');
        Route::patch('/companies/{company}/activate', [CompanyManagementController::class, 'activate'])
            ->whereNumber('company')
            ->name('companies.activate');
        Route::get('/jobs', [JobManagementController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/{jobId}', [JobManagementController::class, 'show'])
            ->whereNumber('jobId')
            ->name('jobs.show');
        Route::patch('/jobs/{jobId}/publish', [JobManagementController::class, 'publish'])
            ->whereNumber('jobId')
            ->name('jobs.publish');
        Route::patch('/jobs/{jobId}/unpublish', [JobManagementController::class, 'unpublish'])
            ->whereNumber('jobId')
            ->name('jobs.unpublish');
        Route::patch('/jobs/{jobId}/close', [JobManagementController::class, 'close'])
            ->whereNumber('jobId')
            ->name('jobs.close');
        Route::patch('/jobs/{jobId}/reopen', [JobManagementController::class, 'reopen'])
            ->whereNumber('jobId')
            ->name('jobs.reopen');
        Route::delete('/jobs/{jobId}', [JobManagementController::class, 'destroy'])
            ->whereNumber('jobId')
            ->name('jobs.destroy');
        Route::patch('/jobs/{jobId}/restore', [JobManagementController::class, 'restore'])
            ->whereNumber('jobId')
            ->name('jobs.restore');

        Route::get('/applications', [ApplicationManagementController::class, 'index'])->name('applications.index');
        Route::get('/applications/{jobApplication}', [ApplicationManagementController::class, 'show'])
            ->whereNumber('jobApplication')
            ->name('applications.show');
        Route::get('/applications/{jobApplication}/resume/preview', [ApplicationManagementController::class, 'previewResume'])
            ->whereNumber('jobApplication')
            ->name('applications.resume.preview');
        Route::get('/applications/{jobApplication}/resume/download', [ApplicationManagementController::class, 'downloadResume'])
            ->whereNumber('jobApplication')
            ->name('applications.resume.download');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::view('/cms', 'admin.section', ['title' => 'CMS'])->name('cms');
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SystemSettingController::class, 'update'])->name('settings.update');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    });

    
    Route::resource('roles', App\Http\Controllers\RoleController::class);
});

Route::middleware(['auth', 'role:employer'])->group(function () {
    Route::get('/employer/dashboard', [App\Http\Controllers\EmployerDashboardController::class, 'index'])
        ->name('employer.dashboard');

    Route::middleware('ensure.employer.has.company')->group(function () {
        Route::resource('company', App\Http\Controllers\CompanyController::class)
            ->except(['index', 'destroy'])
            ->names('company');

        Route::delete('/company/{company}', [App\Http\Controllers\CompanyController::class, 'destroy'])
            ->name('company.destroy');

                Route::prefix('employer')->name('employer.')->group(function () {

            Route::get('/jobs', [App\Http\Controllers\JobController::class, 'index'])
                ->name('jobs.index');

            Route::get('/jobs/create', [App\Http\Controllers\JobController::class, 'create'])
                ->name('jobs.create');

            Route::post('/jobs', [App\Http\Controllers\JobController::class, 'store'])
                ->name('jobs.store');

            Route::get('/jobs/{job}/edit', [App\Http\Controllers\JobController::class, 'edit'])
                ->name('jobs.edit');

            Route::match(['put', 'patch'], '/jobs/{job}', [App\Http\Controllers\JobController::class, 'update'])
                ->name('jobs.update');

            Route::delete('/jobs/{job}', [App\Http\Controllers\JobController::class, 'destroy'])
                ->name('jobs.destroy');

            Route::get('/jobs/trash', [App\Http\Controllers\JobController::class, 'trash'])
                ->name('jobs.trash');

            Route::post('/jobs/{job}/restore', [App\Http\Controllers\JobController::class, 'restore'])
                ->name('jobs.restore');

            Route::delete('/jobs/{job}/force-delete', [App\Http\Controllers\JobController::class, 'forceDelete'])
                ->name('jobs.forceDelete');

            Route::post('/jobs/{job}/duplicate', [App\Http\Controllers\JobController::class, 'duplicate'])
                ->name('jobs.duplicate');

            Route::get('/applications', [App\Http\Controllers\EmployerApplicationController::class, 'index'])
                ->name('applications.index');

            Route::get('/applications/{jobApplication}', [App\Http\Controllers\EmployerApplicationController::class, 'show'])
                ->name('applications.show');

            Route::patch('/applications/{jobApplication}/status', [App\Http\Controllers\EmployerApplicationController::class, 'updateStatus'])
                ->name('applications.update_status');

            Route::get('/applications/{jobApplication}/resume/preview', [App\Http\Controllers\EmployerApplicationController::class, 'previewResume'])
                ->name('applications.resume.preview');

            Route::get('/applications/{jobApplication}/resume', [App\Http\Controllers\EmployerApplicationController::class, 'downloadResume'])
                ->name('applications.resume.download');

        });
    });
});

Route::get('/jobs', [App\Http\Controllers\JobController::class, 'index'])
    ->name('jobs.index');

Route::get('/jobs/{job}/apply', [App\Http\Controllers\JobApplicationController::class, 'redirectToLogin'])
    ->name('jobs.apply');

Route::get('/jobs/{job}', [App\Http\Controllers\JobController::class, 'show'])
    ->name('jobs.show');

Route::middleware(['auth', 'role:job-seeker'])->group(function () {
    Route::get('/saved-jobs', [App\Http\Controllers\SavedJobController::class, 'index'])
        ->name('job-seeker.saved-jobs.index');

    Route::post('/jobs/{job}/save', [App\Http\Controllers\SavedJobController::class, 'toggle'])
        ->name('jobs.saved.toggle');
    Route::get('/job-seeker/dashboard', [App\Http\Controllers\JobSeekerDashboardController::class, 'index'])
        ->name('job-seeker.dashboard');

    Route::get('/job-seeker/profile/create', [App\Http\Controllers\JobSeekerProfileController::class, 'create'])
        ->name('job-seeker.profile.create');

    Route::resource('job-applications', App\Http\Controllers\JobApplicationController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names([
            'index' => 'job-applications.index',
            'create' => 'job-applications.create',
            'store' => 'job-applications.store',
            'show' => 'job-applications.show',
            'edit' => 'job-applications.edit',
            'update' => 'job-applications.update',
            'destroy' => 'job-applications.destroy',
        ]);

    Route::post('/job-seeker/profile', [App\Http\Controllers\JobSeekerProfileController::class, 'store'])
        ->name('job-seeker.profile.store');

    Route::get('/job-seeker/profile/edit/{profile?}', [App\Http\Controllers\JobSeekerProfileController::class, 'edit'])
        ->name('job-seeker.profile.edit');

    Route::match(['put', 'patch'], '/job-seeker/profile', [App\Http\Controllers\JobSeekerProfileController::class, 'update'])
        ->name('job-seeker.profile.update');

    Route::get('/job-seeker/educations', [App\Http\Controllers\EducationController::class, 'index'])
        ->name('job-seeker.educations.index');

    Route::get('/job-seeker/educations/create', [App\Http\Controllers\EducationController::class, 'create'])
        ->name('job-seeker.educations.create');

    Route::post('/job-seeker/educations', [App\Http\Controllers\EducationController::class, 'store'])
        ->name('job-seeker.educations.store');

    Route::get('/job-seeker/educations/{education}/edit', [App\Http\Controllers\EducationController::class, 'edit'])
        ->name('job-seeker.educations.edit');

    Route::match(['put', 'patch'], '/job-seeker/educations/{education}', [App\Http\Controllers\EducationController::class, 'update'])
        ->name('job-seeker.educations.update');

    Route::delete('/job-seeker/educations/{education}', [App\Http\Controllers\EducationController::class, 'destroy'])
        ->name('job-seeker.educations.destroy');

    Route::get('/job-seeker/skills', [App\Http\Controllers\SkillController::class, 'index'])
        ->name('job-seeker.skills.index');

    Route::get('/job-seeker/skills/create', [App\Http\Controllers\SkillController::class, 'create'])
        ->name('job-seeker.skills.create');

    Route::post('/job-seeker/skills', [App\Http\Controllers\SkillController::class, 'store'])
        ->name('job-seeker.skills.store');

    Route::get('/job-seeker/skills/{skill}/edit', [App\Http\Controllers\SkillController::class, 'edit'])
        ->name('job-seeker.skills.edit');

    Route::match(['put', 'patch'], '/job-seeker/skills/{skill}', [App\Http\Controllers\SkillController::class, 'update'])
        ->name('job-seeker.skills.update');

    Route::delete('/job-seeker/skills/{skill}', [App\Http\Controllers\SkillController::class, 'destroy'])
        ->name('job-seeker.skills.destroy');

    Route::get('/job-seeker/resumes', [App\Http\Controllers\ResumeController::class, 'index'])
        ->name('job-seeker.resumes.index');

    Route::get('/job-seeker/resumes/create', [App\Http\Controllers\ResumeController::class, 'create'])
        ->name('job-seeker.resumes.create');

    Route::post('/job-seeker/resumes', [App\Http\Controllers\ResumeController::class, 'store'])
        ->name('job-seeker.resumes.store');

    Route::get('/job-seeker/resumes/{resume}/edit', [App\Http\Controllers\ResumeController::class, 'edit'])
        ->name('job-seeker.resumes.edit');

    Route::match(['put', 'patch'], '/job-seeker/resumes/{resume}', [App\Http\Controllers\ResumeController::class, 'update'])
        ->name('job-seeker.resumes.update');

    Route::delete('/job-seeker/resumes/{resume}', [App\Http\Controllers\ResumeController::class, 'destroy'])
        ->name('job-seeker.resumes.destroy');

    Route::get('/job-seeker/resumes/{resume}/download', [App\Http\Controllers\ResumeController::class, 'download'])
        ->name('job-seeker.resumes.download');

    Route::get('/job-seeker/resume-builders', [App\Http\Controllers\ResumeBuilderController::class, 'index'])
        ->name('job-seeker.resume-builders.index');

    Route::get('/job-seeker/resume-builders/create', [App\Http\Controllers\ResumeBuilderController::class, 'create'])
        ->name('job-seeker.resume-builders.create');

    Route::post('/job-seeker/resume-builders', [App\Http\Controllers\ResumeBuilderController::class, 'store'])
        ->name('job-seeker.resume-builders.store');

    Route::get('/job-seeker/resume-builders/{resumeBuilder}/edit', [App\Http\Controllers\ResumeBuilderController::class, 'edit'])
        ->name('job-seeker.resume-builders.edit');

    Route::match(['put', 'patch'], '/job-seeker/resume-builders/{resumeBuilder}', [App\Http\Controllers\ResumeBuilderController::class, 'update'])
        ->name('job-seeker.resume-builders.update');

    Route::delete('/job-seeker/resume-builders/{resumeBuilder}', [App\Http\Controllers\ResumeBuilderController::class, 'destroy'])
        ->name('job-seeker.resume-builders.destroy');

    Route::get('/job-seeker/resume-builders/{resumeBuilder}/preview', [App\Http\Controllers\ResumeBuilderController::class, 'preview'])
        ->name('resume-builder.preview');

    Route::get('/job-seeker/resume-builders/{resumeBuilder}/download', [App\Http\Controllers\ResumeBuilderController::class, 'downloadPdf'])
        ->name('resume-builder.download');

    Route::get('/job-seeker/resume-builders/{resumeBuilder}/print', [App\Http\Controllers\ResumeBuilderController::class, 'print'])
        ->name('resume-builder.print');

    Route::get('/job-seeker/applications', [App\Http\Controllers\JobSeekerApplicationController::class, 'index'])
        ->name('job-seeker.applications.index');

    Route::get('/job-seeker/applications/{jobApplication}', [App\Http\Controllers\JobSeekerApplicationController::class, 'show'])
        ->name('job-seeker.applications.show');

    Route::get('/job-seeker/experiences', [App\Http\Controllers\ExperienceController::class, 'index'])
        ->name('job-seeker.experiences.index');

    Route::get('/job-seeker/experiences/create', [App\Http\Controllers\ExperienceController::class, 'create'])
        ->name('job-seeker.experiences.create');

    Route::post('/job-seeker/experiences', [App\Http\Controllers\ExperienceController::class, 'store'])
        ->name('job-seeker.experiences.store');

    Route::get('/job-seeker/experiences/{experience}/edit', [App\Http\Controllers\ExperienceController::class, 'edit'])
        ->name('job-seeker.experiences.edit');

    Route::match(['put', 'patch'], '/job-seeker/experiences/{experience}', [App\Http\Controllers\ExperienceController::class, 'update'])
        ->name('job-seeker.experiences.update');

    Route::delete('/job-seeker/experiences/{experience}', [App\Http\Controllers\ExperienceController::class, 'destroy'])
        ->name('job-seeker.experiences.destroy');
});

require __DIR__.'/auth.php';
