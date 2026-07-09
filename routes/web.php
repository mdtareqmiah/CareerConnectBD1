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
    Route::get('/employer', function () {
        return response('Employer access granted', 200);
    });
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
