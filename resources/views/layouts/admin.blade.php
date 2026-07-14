<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-user-id" content="{{ auth()->id() }}">

    <title>{{ config('app.name', 'CareerConnectBD') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .admin-shell {
            min-height: 100vh;
        }

        .admin-sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #111827;
        }

        .admin-sidebar .nav-link {
            color: #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        .admin-sidebar .nav-link.active,
        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                min-width: 100%;
                max-width: 100%;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-light">
<div class="admin-shell d-flex">
    <aside class="admin-sidebar d-none d-lg-flex flex-column p-3 text-white">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fs-5 fw-semibold mb-4">Admin Panel</a>

        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.employers.*') ? 'active' : '' }}" href="{{ route('admin.employers.index') }}">Employers</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" href="{{ route('admin.companies.index') }}">Companies</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">Jobs</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">Applications</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.communications.*') || request()->routeIs('admin.feedback.*') || request()->routeIs('admin.support-tickets.*') ? 'active' : '' }}" href="{{ route('admin.communications.index') }}">Communications</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Reports</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.cms') ? 'active' : '' }}" href="{{ route('admin.cms') }}">CMS</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">Settings</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile') }}">Profile</a></li>
        </ul>

        <div class="mt-auto pt-3 border-top border-secondary-subtle">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-grow-1">
        <nav class="navbar navbar-light bg-white border-bottom d-lg-none">
            <div class="container-fluid">
                <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar" aria-controls="adminMobileSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="adminMobileSidebar" aria-labelledby="adminMobileSidebarLabel">
            <div class="offcanvas-header">
                <h5 id="adminMobileSidebarLabel" class="offcanvas-title">Admin Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav nav-pills flex-column gap-2">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.employers.index') }}">Employers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.companies.index') }}">Companies</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.jobs.index') }}">Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.applications.index') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.cms') }}">CMS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.profile') }}">Profile</a></li>
                </ul>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
                </form>
            </div>
        </div>

        <section class="p-3 p-md-4">
            <div class="d-flex justify-content-end mb-3">
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm position-relative dropdown-toggle d-inline-flex align-items-center gap-2" type="button" id="adminNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i> <span>Notifications</span>
                        @if (auth()->check() && ($unreadNotificationsCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" data-notification-count>
                                {{ $unreadNotificationsCount }}
                            </span>
                        @else
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" data-notification-count></span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="adminNotificationsDropdown" style="min-width: 320px; max-width: min(92vw, 360px);" data-notification-list data-notification-view-all-url="{{ route('notifications.index') }}">
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
                </div>
            </div>
            @include('components.flash-messages')
            @yield('content')
        </section>
    </main>
</div>

@yield('scripts')
</body>
</html>
