@extends('layouts.app')

@section('content')
<div class="py-2 py-lg-4">
    <section class="row align-items-center g-5 py-4 py-lg-5">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-4">
                <i class="bi bi-stars"></i>
                <span class="fw-semibold">Premium hiring platform</span>
            </div>
            <h1 class="display-5 fw-bold mb-3 text-dark">Find your next opportunity with a smarter recruiting experience.</h1>
            <p class="lead text-muted mb-4">
                CareerConnectBD brings together job seekers, employers, and teams in one polished experience for discovering opportunities, promoting talent, and growing professionally.
            </p>

            <div class="d-flex flex-wrap gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Create Account</a>
                    <a href="{{ route('employer.register') }}" class="btn btn-outline-primary btn-lg px-4">Register as Employer</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">Login</a>
                @else
                    <a href="{{ auth()->user()?->role?->slug === 'job-seeker' ? route('job-seeker.dashboard') : (auth()->user()?->role?->slug === 'admin' ? '/admin' : (auth()->user()?->role?->slug === 'employer' ? '/employer' : route('dashboard'))) }}" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                @endguest
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4 text-muted small">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-shield-check text-primary"></i> Secure profiles</span>
                <span class="d-flex align-items-center gap-2"><i class="bi bi-lightning-charge text-primary"></i> Fast applications</span>
                <span class="d-flex align-items-center gap-2"><i class="bi bi-graph-up-arrow text-primary"></i> Better hiring visibility</span>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-soft">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold text-uppercase">What you can do</p>
                            <h2 class="h4 fw-semibold mb-0">Everything in one place</h2>
                        </div>
                        <div class="rounded-circle bg-primary-subtle p-3 text-primary">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex align-items-center gap-3">
                            <span class="rounded-circle bg-primary-subtle text-primary p-2"><i class="bi bi-person-badge"></i></span>
                            <span>Build an impressive job-seeker profile</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex align-items-center gap-3">
                            <span class="rounded-circle bg-primary-subtle text-primary p-2"><i class="bi bi-mortarboard"></i></span>
                            <span>Track education, experience, and skills</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex align-items-center gap-3">
                            <span class="rounded-circle bg-primary-subtle text-primary p-2"><i class="bi bi-building"></i></span>
                            <span>Manage employer workflows with clarity</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4 py-3">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-soft">
                <div class="card-body p-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-3 mb-3">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <h3 class="h5 fw-semibold">For Job Seekers</h3>
                    <p class="text-muted mb-0">Showcase your profile, education, and experience in a clear, professional layout that stands out.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-soft">
                <div class="card-body p-4">
                    <div class="rounded-circle bg-success-subtle text-success d-inline-flex p-3 mb-3">
                        <i class="bi bi-building-up"></i>
                    </div>
                    <h3 class="h5 fw-semibold">For Employers</h3>
                    <p class="text-muted mb-0">Access a focused dashboard built for hiring, review workflows, and role-based collaboration.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-soft">
                <div class="card-body p-4">
                    <div class="rounded-circle bg-info-subtle text-info d-inline-flex p-3 mb-3">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <h3 class="h5 fw-semibold">For Administrators</h3>
                    <p class="text-muted mb-0">Manage roles and maintain a structured platform experience with clarity and control.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="card border-0 shadow-soft mt-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5 text-center position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                <span class="rounded-pill bg-white px-3 py-2 shadow-sm small fw-semibold text-muted">Ready when you are</span>
            </div>
            <h2 class="h3 fw-semibold mb-2 mt-4">Ready to get started?</h2>
            <p class="text-muted mb-4">Join CareerConnectBD and step into a more modern, confident hiring journey.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary">Register Now</a>
            @else
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Manage Your Profile</a>
            @endguest
        </div>
    </section>
</div>
@endsection
