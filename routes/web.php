<?php

use App\Http\Controllers\ProfileController;
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
        return redirect()->to('/admin');
    }

    if ($user?->role?->slug === 'employer') {
        return redirect()->to('/employer');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:employer'])->group(function () {
    Route::get('/employer', function () {
        $user = auth()->user();

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
        return response('Admin access granted', 200);
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

        Route::resource('jobs', App\Http\Controllers\JobController::class)
            ->except(['index', 'show']);

        Route::get('/employer/jobs/trash', [App\Http\Controllers\JobController::class, 'trash'])
            ->name('jobs.trash');

        Route::post('/employer/jobs/{job}/restore', [App\Http\Controllers\JobController::class, 'restore'])
            ->name('jobs.restore');

        Route::delete('/employer/jobs/{job}/force-delete', [App\Http\Controllers\JobController::class, 'forceDelete'])
            ->name('jobs.forceDelete');

        Route::post('/employer/jobs/{job}/duplicate', [App\Http\Controllers\JobController::class, 'duplicate'])
            ->name('jobs.duplicate');
    });

    Route::resource('jobs', App\Http\Controllers\JobController::class)
        ->only(['index', 'show']);
});

Route::middleware(['auth', 'role:job-seeker'])->group(function () {
    Route::get('/job-seeker/dashboard', [App\Http\Controllers\JobSeekerDashboardController::class, 'index'])
        ->name('job-seeker.dashboard');

    Route::get('/job-seeker/profile/create', [App\Http\Controllers\JobSeekerProfileController::class, 'create'])
        ->name('job-seeker.profile.create');

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
