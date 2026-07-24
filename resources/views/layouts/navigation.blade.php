@php
$user = auth()->user();
$roleSlug = $user?->role?->slug;

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/
$homeRoute = match ($roleSlug) {
'job-seeker' => route('job-seeker.dashboard'),
'admin' => route('admin.dashboard'),
'employer' => route('employer.dashboard'),
default => route('dashboard'),
};

/*
|--------------------------------------------------------------------------
| Profile / Avatar Image
|--------------------------------------------------------------------------
| Job Seeker -> Job Seeker Profile Photo
| Employer   -> Company Logo
| Admin      -> Default Avatar
|--------------------------------------------------------------------------
*/
$profileImage = asset('images/default-avatar.svg');

if ($user) {
if ($roleSlug === 'job-seeker') {
$profileImage = optional($user->jobSeekerProfile)->profile_photo_url
?? asset('images/default-avatar.svg');
} elseif ($roleSlug === 'employer') {
/*
* Change these field names only if your Company model
* uses a different logo column.
*/
$company = $user->company;

if ($company) {
if (!empty($company->logo_url)) {
$profileImage = $company->logo_url;
} elseif (!empty($company->logo)) {
$profileImage = asset('storage/' . ltrim($company->logo, '/'));
} elseif (!empty($company->logo_path)) {
$profileImage = asset('storage/' . ltrim($company->logo_path, '/'));
}
}
}
}
@endphp


<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
<div class="container">
  {{-- BRAND --}}
<a
    class="navbar-brand d-flex align-items-center"
    href="{{ auth()->check() ? $homeRoute : url('/') }}"
    aria-label="CareerConnectBD Home"
>
    <span class="careerconnect-brand-icon">CC</span>

    <span class="careerconnect-brand-text">
        CareerConnectBD
    </span>
</a>


{{-- MOBILE TOGGLE --}}
<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#mainNavbar"
aria-controls="mainNavbar"
aria-expanded="false"
aria-label="Toggle navigation">

<span class="navbar-toggler-icon"></span>

</button>


{{-- NAVIGATION --}}
<div class="collapse navbar-collapse justify-content-between" id="mainNavbar">

{{-- LEFT NAVIGATION --}}
<ul class="navbar-nav me-auto mb-2 mb-lg-0">

{{-- GUEST --}}
@guest

<li class="nav-item">
<a
class="nav-link {{ request()->is('/') ? 'active' : '' }}"
href="{{ url('/') }}">
Home
</a>
</li>

<li class="nav-item">
<a
class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}"
href="{{ route('jobs.index') }}">
Jobs
</a>
</li>

@else

{{-- JOB SEEKER --}}
@if ($roleSlug === 'job-seeker')

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}"
    href="{{ route('job-seeker.dashboard') }}">
    Dashboard
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}"
    href="{{ route('jobs.index') }}">
    Jobs
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.applications.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.applications.index') }}">
    My Applications
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.saved-jobs.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.saved-jobs.index') }}">

    Saved Jobs

    @if (!empty($savedJobsCount))
        <span class="badge bg-primary rounded-pill ms-1">
            {{ $savedJobsCount }}
        </span>
    @endif

</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.profile.edit') }}">
    Profile
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.educations.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.educations.index') }}">
    Education
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.experiences.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.experiences.index') }}">
    Experience
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.skills.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.skills.index') }}">
    Skills
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.resumes.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.resumes.index') }}">
    Resume
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('job-seeker.resume-builders.*') ? 'active' : '' }}"
    href="{{ route('job-seeker.resume-builders.index') }}">
    Resume Builder
</a>
</li>


{{-- EMPLOYER --}}
@elseif ($roleSlug === 'employer')

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}"
    href="{{ route('employer.dashboard') }}">
    Dashboard
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('employer.jobs.*') ? 'active' : '' }}"
    href="{{ route('employer.jobs.index') }}">
    Manage Jobs
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}"
    href="{{ route('employer.applications.index') }}">
    Applications
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}"
    href="{{ route('employer.jobs.create') }}">
    Post Job
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}"
    href="{{ $user->company
        ? route('company.show', $user->company)
        : route('company.create') }}">
    Company
</a>
</li>


{{-- ADMIN --}}
@elseif ($roleSlug === 'admin')

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
    href="{{ route('admin.dashboard') }}">
    Dashboard
</a>
</li>

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
    href="{{ route('roles.index') }}">
    Role Management
</a>
</li>


{{-- DEFAULT --}}
@else

<li class="nav-item">
<a
    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
    href="{{ route('dashboard') }}">
    Dashboard
</a>
</li>

@endif

@endguest

</ul>


{{-- RIGHT NAVIGATION --}}
<ul class="navbar-nav ms-auto align-items-lg-center">

{{-- GUEST --}}
@guest

<li class="nav-item">
<a
class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
href="{{ route('register') }}">
Register as Job Seeker
</a>
</li>

<li class="nav-item">
<a
class="nav-link {{ request()->routeIs('employer.register') ? 'active' : '' }}"
href="{{ route('employer.register') }}">
Register as Employer
</a>
</li>

<li class="nav-item">
<a
class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
href="{{ route('login') }}">
Login
</a>
</li>


{{-- AUTHENTICATED USER --}}
@else

<li class="nav-item dropdown">

<a
class="nav-link dropdown-toggle d-flex align-items-center"
href="#"
id="userDropdown"
role="button"
data-bs-toggle="dropdown"
aria-expanded="false">

{{-- PROFILE IMAGE --}}
<span class="me-2 d-flex align-items-center">
    <img
        src="{{ $profileImage }}"
        alt="{{ $user->name }}"
        class="rounded-circle border"
        width="36"
        height="36"
        style="object-fit: cover;"
    >
</span>

<span class="me-2">
    {{ $user->name }}
</span>

</a>


{{-- DROPDOWN --}}
<ul
class="dropdown-menu dropdown-menu-end shadow-sm"
aria-labelledby="userDropdown">

<li>
    <h6 class="dropdown-header">
        {{ $user->name }}
    </h6>
</li>

<li>
    <span class="dropdown-item-text small text-muted">
        {{ $user->email }}
    </span>
</li>

<li>
    <hr class="dropdown-divider">
</li>


{{-- JOB SEEKER DROPDOWN --}}
@if ($roleSlug === 'job-seeker')

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('job-seeker.dashboard') ? 'active' : '' }}"
            href="{{ route('job-seeker.dashboard') }}">
            Dashboard
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}"
            href="{{ route('jobs.index') }}">
            Jobs
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('job-seeker.profile.*') ? 'active' : '' }}"
            href="{{ route('job-seeker.profile.edit') }}">
            Profile
        </a>
    </li>


{{-- EMPLOYER DROPDOWN --}}
@elseif ($roleSlug === 'employer')

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}"
            href="{{ route('employer.dashboard') }}">
            Dashboard
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('employer.jobs.*') ? 'active' : '' }}"
            href="{{ route('employer.jobs.index') }}">
            Manage Jobs
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}"
            href="{{ route('employer.jobs.create') }}">
            Post Job
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}"
            href="{{ route('employer.applications.index') }}">
            Applications
        </a>
    </li>

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('company.*') ? 'active' : '' }}"
            href="{{ $user->company
                ? route('company.show', $user->company)
                : route('company.create') }}">
            Company
        </a>
    </li>


{{-- ADMIN DROPDOWN --}}
@elseif ($roleSlug === 'admin')

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>
    </li>

@else

    <li>
        <a
            class="dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

@endif


{{-- LOGOUT --}}
<li>
    <form
        method="POST"
        action="{{ route('logout') }}">

        @csrf

        <button
            type="submit"
            class="dropdown-item text-start">
            Logout
        </button>

    </form>
</li>

</ul>

</li>

@endguest

</ul>

</div>

</div>
</nav>
<style>
    .careerconnect-brand-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);

    display: inline-flex;
    align-items: center;
    justify-content: center;

    color: #2563eb;
    font-size: 18px;
    font-weight: 700;

    flex-shrink: 0;
}

.careerconnect-brand-text {
    margin-left: 10px;
    color: #111827;
    font-size: 1.25rem;
    font-weight: 600;
    white-space: nowrap;
}

.navbar-brand {
    display: inline-flex !important;
    align-items: center !important;
    text-decoration: none;
    position: relative;
    z-index: 1000;
}

@media (max-width: 576px) {
    .careerconnect-brand-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .careerconnect-brand-text {
        font-size: 1.1rem;
    }
}
</style>