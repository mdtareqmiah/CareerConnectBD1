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
});

require __DIR__.'/auth.php';
