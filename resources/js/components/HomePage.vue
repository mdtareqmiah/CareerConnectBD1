<template>
    <div class="home-page-shell">
        <section class="py-5">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <span class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-4 fw-semibold text-uppercase small">
                        Trusted by leading Bangladesh employers
                    </span>
                    <h1 class="display-5 fw-bold lh-base mb-4 text-dark">Find your next role faster with AI-powered recruitment.</h1>
                    <p class="lead text-muted mb-5" style="max-width: 46rem;">
                        Connect with smart employers, showcase your strengths, and discover opportunities that match your skills through intelligent career matching.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a :href="primaryActionUrl" class="btn btn-primary btn-lg px-5">Explore Jobs</a>
                        <a :href="secondaryActionUrl" class="btn btn-success btn-lg px-5">Post a Job</a>
                    </div>

                    <div class="row g-3">
                        <div v-for="stat in stats" :key="stat.label" class="col-4">
                            <div class="p-4 rounded-4 bg-white border shadow-soft h-100">
                                <p class="text-uppercase text-muted small mb-2">{{ stat.label }}</p>
                                <h3 class="h4 fw-semibold mb-0">{{ stat.value }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 position-relative">
                    <div class="position-relative rounded-5 overflow-hidden shadow-elevated" style="min-height: 560px; background-color: #f8fafc;">
                        <img src="/images/hero-ai-recruitment.png" alt="AI recruitment illustration" class="img-fluid w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 end-0 mt-4 me-4 p-3 rounded-4 bg-white border shadow-sm" style="max-width: 220px;">
                            <h3 class="h3 fw-bold mb-0">94% AI Match</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-muted rounded-5">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="text-uppercase text-primary small fw-semibold">Featured Opportunities</span>
                    <h2 class="h3 fw-semibold mb-2">Explore curated roles matched to your profile</h2>
                    <p class="text-muted mb-0">Browse high-growth positions and discover the opportunities shaping Bangladesh’s talent market.</p>
                </div>
                <a :href="jobsRoute" class="btn btn-outline-primary">Browse all jobs</a>
            </div>

            <div class="row g-4">
                <div v-for="job in featuredJobs" :key="job.title" class="col-md-6 col-xl-3">
                    <div class="card h-100 border-0 shadow-soft overflow-hidden">
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge rounded-pill bg-primary-subtle text-primary mb-3 align-self-start">AI recommended</span>
                            <h3 class="h5 fw-semibold mb-2 text-dark">{{ job.title }}</h3>
                            <p class="text-muted mb-3">{{ job.company }} · {{ job.location }}</p>
                            <div class="mb-4">
                                <span v-for="skill in job.skills" :key="skill" class="badge rounded-pill bg-white text-secondary border mb-2 me-2">
                                    {{ skill }}
                                </span>
                            </div>
                            <div class="mt-auto">
                                <a :href="job.link || jobsRoute" class="btn btn-sm btn-outline-primary">View role</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <span class="text-uppercase text-muted small fw-semibold mb-3 d-inline-block">AI-Powered Features</span>
                    <h2 class="h3 fw-semibold mb-3">Smart tools built for faster hiring and stronger careers</h2>
                    <p class="text-muted mb-4">Leverage the platform’s built-in intelligence to improve matches, profile quality, employer discovery, and application confidence.</p>
                </div>

                <div class="col-lg-7">
                    <div class="row g-4">
                        <div v-for="feature in features" :key="feature.title" class="col-md-6">
                            <div class="card h-100 border-0 shadow-soft p-4">
                                <div class="rounded-3 d-inline-flex p-3 mb-3" :class="feature.iconClass">
                                    <i :class="feature.icon"></i>
                                </div>
                                <h3 class="h5 fw-semibold mb-2">{{ feature.title }}</h3>
                                <p class="text-muted mb-0">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-muted rounded-5">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="text-uppercase text-primary small fw-semibold">Top companies</span>
                    <h2 class="h3 fw-semibold mb-2">Trusted by growing teams across the region</h2>
                    <p class="text-muted mb-0">Companies use CareerConnectBD to discover candidates, streamline hiring, and build stronger teams.</p>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
                <div v-for="company in topCompanies" :key="company" class="col">
                    <div class="card h-100 border-0 shadow-soft py-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3" style="height: 48px;">
                            <span class="display-6 text-primary">{{ company.charAt(0).toUpperCase() }}</span>
                        </div>
                        <p class="mb-0 fw-semibold">{{ company }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="text-uppercase text-muted small fw-semibold">How it works</span>
                    <h2 class="h3 fw-semibold mb-2">A simple, modern process for every career stage</h2>
                </div>
            </div>

            <div class="row g-4">
                <div v-for="step in steps" :key="step.title" class="col-md-4">
                    <div class="card h-100 border-0 shadow-soft p-4">
                        <div class="rounded-3 d-inline-flex p-3 mb-3" :class="step.iconClass">
                            <i :class="step.icon"></i>
                        </div>
                        <h3 class="h5 fw-semibold mb-2">{{ step.title }}</h3>
                        <p class="text-muted mb-0">{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-muted rounded-5">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="text-uppercase text-primary small fw-semibold">Success stories</span>
                    <h2 class="h3 fw-semibold mb-2">Real results from job seekers and employers</h2>
                </div>
            </div>

            <div class="row g-4">
                <div v-for="testimonial in testimonials" :key="testimonial.name" class="col-md-6">
                    <div class="card h-100 border-0 shadow-soft p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="bi bi-chat-quote fs-4"></i>
                            </div>
                            <div>
                                <p class="fw-semibold mb-1">{{ testimonial.name }}</p>
                                <p class="text-muted mb-0 small">{{ testimonial.headline }} · {{ testimonial.company }}</p>
                            </div>
                        </div>
                        <p class="text-muted mb-0">“{{ testimonial.quote }}”</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-primary text-white rounded-5 shadow-elevated">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">Build better career matches with CareerConnectBD</h2>
                    <p class="mb-0 opacity-85">Whether you’re seeking a role or hiring talent, the platform gives you the tools to move forward with confidence.</p>
                </div>
                <div class="col-md-4 d-flex flex-wrap gap-3 justify-content-md-end">
                    <a :href="registerRoute" class="btn btn-light btn-lg">Get started</a>
                    <a :href="jobsRoute" class="btn btn-outline-light btn-lg">Browse jobs</a>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    featuredJobs: { type: Array, default: () => [] },
    topCompanies: { type: Array, default: () => [] },
    testimonials: { type: Array, default: () => [] },
    jobsRoute: { type: String, default: '/' },
    registerRoute: { type: String, default: '/register' },
    postJobUrl: { type: String, default: '/employer/register' },
    primaryActionUrl: { type: String, default: '/' },
    secondaryActionUrl: { type: String, default: '/employer/register' },
});

const stats = [
    { label: 'Jobs', value: '1200+' },
    { label: 'Companies', value: '350+' },
    { label: 'AI matching', value: 'Powered by AI' },
];

const features = [
    {
        title: 'Resume Analysis',
        description: 'Understand how your profile performs and discover exactly where to sharpen your experience.',
        icon: 'bi bi-speedometer2 fs-4',
        iconClass: 'bg-primary-subtle text-primary',
    },
    {
        title: 'Smart Matching',
        description: 'See roles that fit your strengths and get matched with employers actively hiring for your skillset.',
        icon: 'bi bi-robot fs-4',
        iconClass: 'bg-success-subtle text-success',
    },
    {
        title: 'Talent Discovery',
        description: 'Employers can surface the right candidates faster with focused talent insights and shortlist tools.',
        icon: 'bi bi-people-fill fs-4',
        iconClass: 'bg-info-subtle text-info',
    },
    {
        title: 'Profile Guidance',
        description: 'Complete your candidate profile with clearer milestones and professional detail tracking.',
        icon: 'bi bi-check2-square fs-4',
        iconClass: 'bg-warning-subtle text-warning',
    },
];

const steps = [
    {
        title: 'Build your profile',
        description: 'Add experience, education, and skills in a polished format that highlights your strengths.',
        icon: 'bi bi-person-vcard fs-4',
        iconClass: 'bg-primary-subtle text-primary',
    },
    {
        title: 'Get matched',
        description: 'Receive tailored job suggestions from AI-assisted matching and employer discovery tools.',
        icon: 'bi bi-magic fs-4',
        iconClass: 'bg-success-subtle text-success',
    },
    {
        title: 'Apply with confidence',
        description: 'Submit stronger applications, track progress, and engage with hiring teams more effectively.',
        icon: 'bi bi-briefcase-fill fs-4',
        iconClass: 'bg-warning-subtle text-warning',
    },
];

const companyNames = computed(() => props.topCompanies || []);
</script>
