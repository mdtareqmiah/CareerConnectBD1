@extends('layouts.app')

@section('content')
@php
    $featuredJobs = $featuredJobs ?? [
        ['title' => 'Senior Product Designer', 'company' => 'BlueAmp Labs', 'location' => 'Dhaka, Bangladesh', 'skills' => ['UI/UX', 'Figma', 'Research'], 'link' => route('jobs.index')],
        ['title' => 'AI Recruitment Analyst', 'company' => 'NovaHire', 'location' => 'Chattogram, Bangladesh', 'skills' => ['Data', 'Python', 'Matching'], 'link' => route('jobs.index')],
        ['title' => 'Talent Acquisition Lead', 'company' => 'BridgeWorks', 'location' => 'Remote', 'skills' => ['Hiring', 'Leadership', 'People Ops'], 'link' => route('jobs.index')],
        ['title' => 'Junior Software Engineer', 'company' => 'Atlas Consulting', 'location' => 'Dhaka, Bangladesh', 'skills' => ['Laravel', 'JavaScript', 'SQL'], 'link' => route('jobs.index')],
    ];

    $topCompanies = $topCompanies ?? ['BengalTech', 'NovaHire', 'Atlas Consulting', 'BridgeWorks', 'Argo Systems'];

    $testimonials = $testimonials ?? [
        ['name' => 'Ayesha Rahman', 'headline' => 'Marketing Specialist', 'company' => 'WaveHR', 'quote' => 'CareerConnectBD matched me with opportunities aligned to my goals and helped me land a stronger role faster.'],
        ['name' => 'Tanvir Ahmed', 'headline' => 'HR Lead', 'company' => 'BengalTech', 'quote' => 'The candidate recommendations are focused and relevant, which made hiring feel more strategic than transactional.'],
        ['name' => 'Sadia Noor', 'headline' => 'Product Manager', 'company' => 'GreenLabs', 'quote' => 'The resume score insights helped me polish my profile and present myself with confidence.'],
    ];

    $primaryActionUrl = auth()->check()
        ? (auth()->user()?->role?->slug === 'job-seeker'
            ? route('job-seeker.dashboard')
            : (auth()->user()?->role?->slug === 'admin'
                ? '/admin'
                : (auth()->user()?->role?->slug === 'employer'
                    ? '/employer'
                    : route('dashboard'))))
        : route('jobs.index');

    $secondaryActionUrl = auth()->check()
        ? (auth()->user()?->role?->slug === 'employer'
            ? route('jobs.create')
            : route('employer.register'))
        : route('employer.register');
@endphp

<div id="home-page-root"
     data-featured-jobs='@json($featuredJobs)'
     data-top-companies='@json($topCompanies)'
     data-testimonials='@json($testimonials)'
     data-jobs-route="{{ route('jobs.index') }}"
     data-register-route="{{ route('register') }}"
     data-post-job-url="{{ route('employer.register') }}"
     data-primary-action-url="{{ $primaryActionUrl }}"
     data-secondary-action-url="{{ $secondaryActionUrl }}">
</div>
@endsection
