@php
    $user = auth()->user();
    $roleSlug = $user?->role?->slug;
    $homeRoute = match ($roleSlug) {
        'job-seeker' => route('job-seeker.dashboard'),
        'admin' => route('admin.dashboard'),
        'employer' => route('employer.dashboard'),
        default => route('dashboard'),
    };
    $hasCustomLogo = file_exists(public_path('images/logo.png'));

    $profileImage = asset('images/default-avatar.svg');

    if ($user) {
        if ($roleSlug === 'job-seeker') {
            $profileImage = optional($user->jobSeekerProfile)->profile_photo_url
                ?? asset('images/default-avatar.svg');
        } elseif ($roleSlug === 'employer') {
            $company = $user->company;

            if ($company) {
                if (! empty($company->logo_url)) {
                    $profileImage = $company->logo_url;
                } elseif (! empty($company->logo)) {
                    $profileImage = asset('storage/' . ltrim($company->logo, '/'));
                } elseif (! empty($company->logo_path)) {
                    $profileImage = asset('storage/' . ltrim($company->logo_path, '/'));
                }
            }
        }
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm" id="mainNavbar">
    <div class="container-xl">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ auth()->check() ? $homeRoute : '/' }}">
            @if ($hasCustomLogo)
                <img src="{{ asset('images/logo.png') }}" alt="CareerConnectBD" height="40" class="d-inline-block align-text-top">
            @else
                <span class="fw-bold text-primary">CareerConnectBD</span>
            @endif
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.create') ? 'active' : '' }}" href="{{ route('contact.create') }}">Contact</a>
                    </li>
                @endguest

                @auth
                    @if ($roleSlug === 'job-seeker')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.applications.*') ? 'active' : '' }}" href="{{ route('job-seeker.applications.index') }}">My Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.index') }}">Feedback</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('support-tickets.*') ? 'active' : '' }}" href="{{ route('support-tickets.index') }}">Support</a>
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
                            <a class="nav-link {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}" href="{{ route('job-seeker.profile.edit') }}">Profile</a>
                        </li>
                    @elseif ($roleSlug === 'employer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}" href="{{ route('employer.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.jobs.*') ? 'active' : '' }}" href="{{ route('employer.jobs.index') }}">Manage Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}" href="{{ route('employer.applications.index') }}">Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}" href="{{ route('employer.jobs.create') }}">Post Job</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.index') }}">Feedback</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('support-tickets.*') ? 'active' : '' }}" href="{{ route('support-tickets.index') }}">Support</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ $user->company ? route('company.show', $user->company) : route('company.create') }}">Company</a>
                        </li>
                    @elseif ($roleSlug === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
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

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.create') }}">Contact Us</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register as Job Seeker</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employer.register') ? 'active' : '' }}" href="{{ route('employer.register') }}">Register as Employer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                @else
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative dropdown-toggle d-flex align-items-center" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            @if (($unreadNotificationsCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" data-notification-count>
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @else
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" data-notification-count></span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="notificationsDropdown" style="min-width: 320px;" data-notification-list data-notification-view-all-url="{{ route('notifications.index') }}">
                            <li><h6 class="dropdown-header">Latest notifications</h6></li>
                            @forelse ($latestNotifications as $notification)
                                @php
                                    $data = $notification->data ?? [];
                                    $title = $data['title'] ?? 'Notification';
                                    $message = $data['message'] ?? '';
                                @endphp
                                <li data-notification-item="1" data-notification-id="{{ $notification->id }}">
                                    <a class="dropdown-item rounded-3" href="{{ $data['link'] ?? route('notifications.index') }}">
                                        <div class="d-flex justify-content-between gap-3">
                                            <div class="min-w-0">
                                                <div class="fw-semibold">{{ $title }}</div>
                                                <div class="small text-muted">{{ Str::limit($message, 60) }}</div>
                                            </div>
                                            @if (is_null($notification->read_at))
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li data-notification-empty><span class="dropdown-item-text text-muted">No notifications yet.</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-primary fw-semibold rounded-3" href="{{ route('notifications.index') }}">View all notifications</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-1 d-flex align-items-center">
                                <img src="{{ $profileImage }}" alt="{{ $user->name }}" class="rounded-circle border shadow-sm" width="32" height="32" style="object-fit: cover;">
                            </span>
                            <span class="me-1">{{ $user->name }}</span>
                            <span class="small text-muted">▼</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header">{{ $user->name }}</h6></li>
                            <li><span class="dropdown-item-text small text-muted">{{ $user->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('notifications.index') }}">View all notifications</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if ($roleSlug === 'job-seeker')
                                <li><a class="dropdown-item {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}" href="{{ route('job-seeker.profile.edit') }}">Profile</a></li>
                            @elseif ($roleSlug === 'employer')
                                <li><a class="dropdown-item {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}" href="{{ route('employer.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('employer.jobs.*') ? 'active' : '' }}" href="{{ route('employer.jobs.index') }}">Manage Jobs</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}" href="{{ route('employer.jobs.create') }}">Post Job</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ $user->company ? route('company.show', $user->company) : route('company.create') }}">Company</a></li>
                            @else
                                <li><a class="dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
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
