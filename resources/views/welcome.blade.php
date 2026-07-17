@extends('layouts.app')

@section('content')
<section class="bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[1.4fr_0.9fr] items-center">
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-300/20 bg-white/10 px-4 py-2 text-sm text-sky-100">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-sky-400"></span>
                    Trusted by 350+ employers across Bangladesh
                </div>

                <div class="max-w-3xl space-y-6">
                    <h1 class="text-5xl font-bold tracking-tight sm:text-6xl">
                        Build a stronger career with
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-indigo-300 to-purple-300">
                            modern hiring tools.
                        </span>
                    </h1>
                    <p class="text-base leading-8 text-slate-300 sm:text-lg">
                        CareerConnectBD helps job seekers and employers connect faster with smarter profiles, curated roles, and AI-driven recruitment support.
                    </p>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row">
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-sky-400 px-8 py-4 text-sm font-semibold text-slate-950 shadow-2xl shadow-sky-400/20 transition hover:bg-sky-300">
                            Register Now
                        </a>
                        <a href="{{ route('employer.register') }}" class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900/80 px-8 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Employer Signup
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full bg-sky-400 px-8 py-4 text-sm font-semibold text-slate-950 shadow-2xl shadow-sky-400/20 transition hover:bg-sky-300">
                            Manage Profile
                        </a>
                        <a href="{{ auth()->user()?->role?->slug === 'employer' ? route('jobs.create') : route('employer.register') }}" class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900/80 px-8 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Post a Job
                        </a>
                    @endguest
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-3xl bg-slate-900/80 p-5 border border-white/10">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm uppercase tracking-[0.2em] text-slate-400">Jobs</span>
                            <span class="text-3xl font-bold text-white">1200+</span>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-slate-900/80 p-5 border border-white/10">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm uppercase tracking-[0.2em] text-slate-400">Companies</span>
                            <span class="text-3xl font-bold text-white">350+</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-slate-900/95 shadow-2xl shadow-slate-950/40 sm:min-h-[560px]">
                <img src="{{ asset('images/ai-recruitment.png') }}" alt="AI recruitment illustration" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-950/20 via-transparent to-slate-950/70"></div>

                <div class="absolute bottom-8 left-8 max-w-xs rounded-[1.5rem] bg-slate-950/90 p-5 shadow-2xl ring-1 ring-white/10 backdrop-blur-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-orange-400 to-orange-500 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2l2.77 5.62L21 8.24l-4.5 4.38L17.54 21 12 17.78 6.46 21l1.05-8.38L3 8.24l6.23-.62L12 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">94% AI Match</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Designed for every stage of hiring</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">From job seekers to employers, CareerConnectBD offers polished workflows, powerful insights, and reliable matches.</p>
        </div>

        <div class="grid gap-8 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-200">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-6 text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Smart job matching</h3>
                <p class="text-gray-600 leading-relaxed">Receive opportunities that align with your profile and make your search faster and more accurate.</p>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-200">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center mb-6 text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Employer workflows</h3>
                <p class="text-gray-600 leading-relaxed">Manage roles, review candidates, and hire faster with smarter tools for your team.</p>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-xl border border-slate-200">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-6 text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Professional visibility</h3>
                <p class="text-gray-600 leading-relaxed">Stand out with a profile that highlights your experience, skills, and career goals.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between mb-10">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-sky-500">Featured opportunities</p>
                <h2 class="mt-3 text-4xl font-bold text-slate-900">Browse curated roles</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center rounded-full border border-slate-200 bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Browse all jobs</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
            <a href="{{ route('jobs.index') }}" class="group block rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <p class="text-sm uppercase tracking-[0.2em] text-sky-500">AI Recommended</p>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">Senior Product Designer</h3>
                <p class="mt-3 text-sm text-slate-600">BlueAmp Labs · Dhaka, Bangladesh</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">UI/UX</span>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Figma</span>
                </div>
            </a>
            <a href="{{ route('jobs.index') }}" class="group block rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <p class="text-sm uppercase tracking-[0.2em] text-sky-500">AI Recommended</p>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">AI Recruitment Analyst</h3>
                <p class="mt-3 text-sm text-slate-600">NovaHire · Chattogram, Bangladesh</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Data</span>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Python</span>
                </div>
            </a>
            <a href="{{ route('jobs.index') }}" class="group block rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <p class="text-sm uppercase tracking-[0.2em] text-sky-500">AI Recommended</p>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">Talent Acquisition Lead</h3>
                <p class="mt-3 text-sm text-slate-600">BridgeWorks · Remote</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Hiring</span>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Leadership</span>
                </div>
            </a>
            <a href="{{ route('jobs.index') }}" class="group block rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <p class="text-sm uppercase tracking-[0.2em] text-sky-500">AI Recommended</p>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">Junior Software Engineer</h3>
                <p class="mt-3 text-sm text-slate-600">Atlas Consulting · Dhaka, Bangladesh</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">Laravel</span>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">JavaScript</span>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
