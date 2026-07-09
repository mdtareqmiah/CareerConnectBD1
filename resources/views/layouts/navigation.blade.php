<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ auth()->check() && auth()->user()?->role?->slug === 'job-seeker' ? route('job-seeker.dashboard') : route('dashboard') }}">
            CareerConnectBD
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if (auth()->user()?->role?->slug === 'job-seeker')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}" href="{{ route('job-seeker.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">My Profile</a>
                        </li>
                        <li class="nav-item"><span class="nav-link disabled">Education</span></li>
                        <li class="nav-item"><span class="nav-link disabled">Experience</span></li>
                        <li class="nav-item"><span class="nav-link disabled">Skills</span></li>
                        <li class="nav-item"><span class="nav-link disabled">Resume</span></li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth
                    <li class="nav-item me-2">
                        <span class="navbar-text">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
