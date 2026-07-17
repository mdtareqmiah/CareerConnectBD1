@php
    $user = auth()->user();
    $roleSlug = $user?->role?->slug;
    $homeRoute = match ($roleSlug) {
        'job-seeker' => route('job-seeker.dashboard'),
        'admin' => '/admin',
        'employer' => '/employer',
        default => route('dashboard'),
    };
    $hasCustomLogo = file_exists(public_path('images/logo.png'));
@endphp

<!-- Premium Glassmorphism Navigation with Tailwind CSS -->
<nav class="sticky top-0 z-50 h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">

        <!-- Logo Section (Left) -->
        <a href="{{ auth()->check() ? $homeRoute : '/' }}" class="flex items-center gap-3 group hover:opacity-80 transition-opacity duration-300" aria-label="CareerConnectBD home">
            @if ($hasCustomLogo)
                <img src="{{ asset('images/logo.png') }}" alt="CareerConnectBD logo" class="w-12 h-12 rounded-lg object-cover">
            @else
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg">CC</div>
            @endif

            <div class="flex flex-col">
                <span class="text-lg font-extrabold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent leading-tight">CareerConnectBD</span>
                <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Premium AI Hiring</span>
            </div>
        </a>

        <!-- Center Navigation Links (Desktop Only) -->
        <div class="hidden md:flex items-center gap-1">
            <a href="{{ auth()->check() ? $homeRoute : '/' }}" class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-full transition-all duration-200 {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : '' }}">
                Home
            </a>
            <a href="{{ route('jobs.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all duration-200">
                Jobs
            </a>
            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-slate-600 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all duration-200">
                For Seeker
            </a>
            <a href="{{ route('employer.register') }}" class="px-4 py-2 text-sm font-medium text-slate-600 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all duration-200">
                For Employer
            </a>
        </div>

        <!-- Login Button & Auth Actions (Right) -->
        <div class="flex items-center gap-4">
            @guest
                <!-- Premium Login Button -->
                <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-full shadow-md shadow-orange-500/10 hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                    Login
                </a>
            @else
                <!-- User Authenticated: Notifications & Dropdown -->
                <button class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors duration-200" aria-label="Notifications">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div class="relative">
                    <button id="userMenuButton" type="button" class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors duration-200" aria-label="User menu" aria-expanded="false">
                        <img src="{{ optional($user->jobSeekerProfile)->profile_photo_url ?? asset('images/default-avatar.svg') }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                        <span class="text-sm font-medium text-slate-700 hidden sm:inline">{{ $user->name }}</span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="userMenuDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-200">
                            <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        </div>

                        @if ($roleSlug === 'job-seeker')
                            <a href="{{ route('job-seeker.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                            <a href="{{ route('jobs.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Jobs</a>
                            <a href="{{ route('job-seeker.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                        @elseif ($roleSlug === 'employer')
                            <a href="{{ route('employer.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                            <a href="{{ route('jobs.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Manage Jobs</a>
                            <a href="{{ auth()->user()->company ? route('company.show', auth()->user()->company) : route('company.create') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Company</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-t border-slate-200">Settings</a>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Logout</button>
                        </form>
                    </div>
                </div>
            @endguest

            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuToggle" class="md:hidden w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors duration-200" aria-label="Toggle menu">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="hidden md:hidden absolute top-20 left-0 right-0 bg-white border-b border-slate-200 shadow-lg z-40">
        <div class="flex flex-col p-4 gap-2">
            <a href="{{ auth()->check() ? $homeRoute : '/' }}" class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-full">Home</a>
            <a href="{{ route('jobs.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-full">Jobs</a>
            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-full">For Seeker</a>
            <a href="{{ route('employer.register') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-full">For Employer</a>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Close mobile menu when a link is clicked
    const mobileLinks = mobileMenu?.querySelectorAll('a');
    mobileLinks?.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // User menu toggle
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenuDropdown = document.getElementById('userMenuDropdown');

    if (userMenuButton && userMenuDropdown) {
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = userMenuDropdown.classList.contains('opacity-100');
            userMenuDropdown.classList.toggle('opacity-100', !isOpen);
            userMenuDropdown.classList.toggle('visible', !isOpen);
            userMenuDropdown.classList.toggle('invisible', isOpen);
            userMenuDropdown.classList.toggle('pointer-events-auto', !isOpen);
            userMenuDropdown.classList.toggle('pointer-events-none', isOpen);
            userMenuButton.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                userMenuDropdown.classList.remove('opacity-100');
                userMenuDropdown.classList.add('invisible');
                userMenuDropdown.classList.remove('visible');
                userMenuDropdown.classList.remove('pointer-events-auto');
                userMenuDropdown.classList.add('pointer-events-none');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }
</script>
