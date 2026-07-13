@php
    $user = auth()->user();
    $roleSlug = $user?->role?->slug;
    $homeRoute = match ($roleSlug) {
        'job-seeker' => route('job-seeker.dashboard'),
        'admin' => '/admin',
        'employer' => '/employer',
        default => route('dashboard'),
    };
@endphp

<nav class="navbar navbar-expand-lg navbar-light sticky-top border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3 fw-semibold text-dark" href="{{ auth()->check() ? $homeRoute : '/' }}">
            <span class="brand-mark d-inline-flex align-items-center justify-content-center rounded-circle text-white">CC</span>
            <span class="d-flex flex-column">
                <span class="fw-bold">CareerConnectBD</span>

            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a>
                    </li>
                @else
                    @if ($roleSlug === 'job-seeker')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}" @if (request()->routeIs('job-seeker.dashboard')) aria-current="page" @endif>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.applications.*') ? 'active' : '' }}" href="{{ route('job-seeker.applications.index') }}">My Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.saved-jobs.index') ? 'active' : '' }}" href="{{ route('job-seeker.saved-jobs.index') }}">
                                Saved Jobs
                                @if (! empty($savedJobsCount))
                                    <span class="badge bg-primary rounded-pill ms-1">{{ $savedJobsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}" href="{{ route('job-seeker.profile.edit') }}" @if (request()->routeIs('job-seeker.profile.*')) aria-current="page" @endif>Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.educations.*') ? 'active' : '' }}" href="{{ route('job-seeker.educations.index') }}" @if (request()->routeIs('job-seeker.educations.*')) aria-current="page" @endif>Education</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.experiences.*') ? 'active' : '' }}" href="{{ route('job-seeker.experiences.index') }}" @if (request()->routeIs('job-seeker.experiences.*')) aria-current="page" @endif>Experience</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.skills.*') ? 'active' : '' }}" href="{{ route('job-seeker.skills.index') }}" @if (request()->routeIs('job-seeker.skills.*')) aria-current="page" @endif>Skills</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.resumes.*') ? 'active' : '' }}" href="{{ route('job-seeker.resumes.index') }}" @if (request()->routeIs('job-seeker.resumes.*')) aria-current="page" @endif>Resume</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.resume-builders.*') ? 'active' : '' }}" href="{{ route('job-seeker.resume-builders.index') }}" @if (request()->routeIs('job-seeker.resume-builders.*')) aria-current="page" @endif>Resume Builder</a>
                        </li>
                    @elseif ($roleSlug === 'employer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Manage Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}" href="{{ route('employer.applications.index') }}">Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('jobs.create') ? 'active' : '' }}" href="{{ route('jobs.create') }}">Post Job</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ auth()->user()->company ? route('company.show', auth()->user()->company) : route('company.create') }}">Company</a>
                        </li>
                    @elseif ($roleSlug === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">Role Management</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                @endguest
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register as job seeker</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employer.register') ? 'active' : '' }}" href="{{ route('employer.register') }}">Register as Employer</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="{{ route('login') }}">Login</a>
                    </li>

                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-flex align-items-center">
                                <img src="{{ optional($user->jobSeekerProfile)->profile_photo_url ?? asset('images/default-avatar.svg') }}" alt="Avatar" class="rounded-circle border" width="32" height="32">
                            </span>
                            <span class="me-2 fw-semibold">{{ $user->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header">{{ $user->name }}</h6>
                            </li>
                            <li>
                                <span class="dropdown-item-text small text-muted">{{ $user->email }}</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if ($roleSlug === 'job-seeker')
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}">Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}" href="{{ route('job-seeker.profile.edit') }}">Profile</a>
                                </li>
                            @elseif ($roleSlug === 'employer')
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}" href="{{ route('employer.dashboard') }}">Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Manage Jobs</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('jobs.create') ? 'active' : '' }}" href="{{ route('jobs.create') }}">Post Job</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ auth()->user()->company ? route('company.show', auth()->user()->company) : route('company.create') }}">Company</a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-start">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
