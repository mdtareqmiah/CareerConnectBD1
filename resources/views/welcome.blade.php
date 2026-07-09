@extends('layouts.app')

@section('content')
<div class="py-3 py-lg-4">
    <section class="row align-items-center g-5 py-4 py-lg-5">
        <div class="col-lg-7">
            <span class="badge bg-primary mb-3">CareerConnectBD</span>
            <h1 class="display-5 fw-bold mb-3">Build your career with a smarter job platform.</h1>
            <p class="lead text-muted mb-4">
                CareerConnectBD brings together job seekers, employers, and administrators in one polished experience for discovering opportunities, managing profiles, and growing professionally.
            </p>

            <div class="d-flex flex-wrap gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Account</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login</a>
                @else
                    <a href="{{ auth()->user()?->role?->slug === 'job-seeker' ? route('job-seeker.dashboard') : (auth()->user()?->role?->slug === 'admin' ? '/admin' : (auth()->user()?->role?->slug === 'employer' ? '/employer' : route('dashboard'))) }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                @endguest
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 fw-semibold mb-3">What you can do</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">Create a polished job-seeker profile</li>
                        <li class="list-group-item px-0">Track education and professional progress</li>
                        <li class="list-group-item px-0">Manage access for employers and admins</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4 py-3">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5 fw-semibold">For Job Seekers</h3>
                    <p class="text-muted mb-0">Showcase your profile, education, and experience in a clear, professional layout.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5 fw-semibold">For Employers</h3>
                    <p class="text-muted mb-0">Access a focused dashboard built for hiring and role-based workflows.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5 fw-semibold">For Administrators</h3>
                    <p class="text-muted mb-0">Manage roles and maintain a structured platform experience with ease.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4 p-lg-5 text-center">
            <h2 class="h4 fw-semibold mb-2">Ready to get started?</h2>
            <p class="text-muted mb-4">Join CareerConnectBD and explore the next step in your professional journey.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary">Register Now</a>
            @else
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Manage Your Profile</a>
            @endguest
        </div>
    </section>
</div>
@endsection
