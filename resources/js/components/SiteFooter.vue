<template>
    <footer class="site-footer mt-5 bg-black text-white">
        <div class="container-xl py-6">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center gap-3 fw-semibold text-white mb-3" href="/">
                        <img v-if="hasCustomLogo" src="/images/logo.png" alt="CareerConnectBD logo" class="w-12 h-12 rounded-lg object-cover">
                        <div v-else class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg">CC</div>
                        <span class="d-flex flex-column lh-1">
                            <span class="fw-bold">CareerConnectBD</span>
                            <small class="text-warning">Premium AI hiring</small>
                        </span>
                    </a>
                    <p class="text-white-50 mb-0">A modern recruitment platform designed for fast, trusted hiring and career growth.</p>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-white fw-semibold mb-3">Quick Links</h6>
                    <ul class="list-unstyled footer-link-list">
                        <li class="mb-2"><a href="/" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-house-fill"></i></span>Home</a></li>
                        <li class="mb-2"><a :href="jobsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-briefcase-fill"></i></span>Browse jobs</a></li>
                        <li class="mb-2"><a :href="registerRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-person-plus-fill"></i></span>Job seeker signup</a></li>
                        <li><a :href="employerRegisterRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-building-add"></i></span>Employer signup</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-white fw-semibold mb-3">Job Seekers</h6>
                    <ul class="list-unstyled footer-link-list">
                        <li class="mb-2"><a :href="jobSeekerDashboardRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-speedometer2"></i></span>Dashboard</a></li>
                        <li class="mb-2"><a :href="jobSeekerApplicationsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-file-earmark-text"></i></span>Applications</a></li>
                        <li class="mb-2"><a :href="savedJobsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-bookmark-heart"></i></span>Saved jobs</a></li>
                        <li><a :href="profileRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-person-badge-fill"></i></span>Profile</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-white fw-semibold mb-3">Employers</h6>
                    <ul class="list-unstyled footer-link-list">
                        <li class="mb-2"><a :href="employerDashboardRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-speedometer2"></i></span>Dashboard</a></li>
                        <li class="mb-2"><a :href="jobsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-list-check"></i></span>Manage jobs</a></li>
                        <li class="mb-2"><a :href="employerApplicationsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-file-earmark-text"></i></span>Applications</a></li>
                        <li><a :href="companyRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-building"></i></span>Company</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-white fw-semibold mb-3">Resources</h6>
                    <ul class="list-unstyled footer-link-list">
                        <li class="mb-2"><a :href="jobsRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-tags-fill"></i></span>Job categories</a></li>
                        <li class="mb-2"><a :href="registerRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-lightbulb-fill"></i></span>Career tips</a></li>
                        <li><a :href="employerRegisterRoute" class="text-white text-decoration-none"><span class="footer-link-icon"><i class="bi bi-bookmarks"></i></span>Employer resources</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-white/10 mt-5 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="small text-white">© {{ currentYear }} <span class="text-orange-500 fw-semibold">CareerConnectBD</span>. All rights reserved.</div>
                <div class="d-flex align-items-center gap-3 footer-social-icons">
                    <a href="#" class="text-white text-decoration-none" aria-label="YouTube"><i class="bi bi-youtube fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none" aria-label="Google"><i class="bi bi-google fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none" aria-label="Instagram"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none" aria-label="LinkedIn"><i class="bi bi-linkedin fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none" aria-label="Twitter"><i class="bi bi-twitter fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none" aria-label="Facebook"><i class="bi bi-facebook fs-5"></i></a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    hasCustomLogo: { type: Boolean, default: false },
    jobsRoute: { type: String, default: '/' },
    registerRoute: { type: String, default: '/register' },
    employerRegisterRoute: { type: String, default: '/employer/register' },
    jobSeekerDashboardRoute: { type: String, default: '/job-seeker/dashboard' },
    jobSeekerApplicationsRoute: { type: String, default: '/job-seeker/applications' },
    savedJobsRoute: { type: String, default: '/job-seeker/saved-jobs' },
    profileRoute: { type: String, default: '/job-seeker/profile' },
    employerDashboardRoute: { type: String, default: '/employer' },
    employerApplicationsRoute: { type: String, default: '/employer/applications' },
    companyRoute: { type: String, default: '/company' },
    currentYear: { type: Number, default: () => new Date().getFullYear() },
});

const currentYear = computed(() => props.currentYear);
</script>
