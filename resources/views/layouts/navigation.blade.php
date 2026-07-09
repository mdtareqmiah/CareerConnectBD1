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

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ auth()->check() ? $homeRoute : '/' }}">
            CareerConnectBD
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse d-flex justify-content-between" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
                    </li>
                @else
                    @if ($roleSlug === 'job-seeker')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}" href="{{ route('job-seeker.profile.edit') }}">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.educations.*') ? 'active' : '' }}" href="{{ route('job-seeker.educations.index') }}">Education</a>
                        </li>
                    @elseif ($roleSlug === 'employer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('employer') ? 'active' : '' }}" href="/employer">Employer Dashboard</a>
                        </li>
                    @elseif ($roleSlug === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="/admin">Admin Dashboard</a>
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

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2">{{ $user->name }}</span>
                            <span class="small">▼</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header">{{ $user->name }}</h6>
                            </li>
                            <li>
                                <span class="dropdown-item-text small text-muted">{{ $user->email }}</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
                            </li>
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
