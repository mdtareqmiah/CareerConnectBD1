@php
    $hasCustomLogo = file_exists(public_path('images/logo.png'));
@endphp

<div id="site-footer-root"
     data-has-custom-logo="{{ $hasCustomLogo ? '1' : '0' }}"
     data-jobs-route="{{ route('jobs.index') }}"
     data-register-route="{{ route('register') }}"
     data-employer-register-route="{{ route('employer.register') }}"
     data-job-seeker-dashboard-route="{{ route('job-seeker.dashboard') }}"
     data-job-seeker-applications-route="{{ route('job-seeker.applications.index') }}"
     data-saved-jobs-route="{{ route('job-seeker.saved-jobs.index') }}"
     data-profile-route="{{ route('job-seeker.profile.edit') }}"
     data-employer-dashboard-route="{{ route('dashboard') }}"
     data-employer-applications-route="{{ route('employer.applications.index') }}"
     data-company-route="{{ auth()->user()?->company ? route('company.show', auth()->user()->company) : route('company.create') }}"
     data-current-year="{{ date('Y') }}">
</div>
