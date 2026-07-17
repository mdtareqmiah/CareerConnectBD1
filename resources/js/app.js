
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { createApp } from 'vue';
import Alpine from 'alpinejs';
import HomePage from './components/HomePage.vue';
import SiteFooter from './components/SiteFooter.vue';
import JobsPage from './components/JobsPage.vue';

window.Alpine = Alpine;

const homePageRoot = document.getElementById('home-page-root');

if (homePageRoot) {
    const props = {
        featuredJobs: homePageRoot.dataset.featuredJobs ? JSON.parse(homePageRoot.dataset.featuredJobs) : [],
        topCompanies: homePageRoot.dataset.topCompanies ? JSON.parse(homePageRoot.dataset.topCompanies) : [],
        testimonials: homePageRoot.dataset.testimonials ? JSON.parse(homePageRoot.dataset.testimonials) : [],
        jobsRoute: homePageRoot.dataset.jobsRoute || '/',
        registerRoute: homePageRoot.dataset.registerRoute || '/register',
        postJobUrl: homePageRoot.dataset.postJobUrl || '/employer/register',
        primaryActionUrl: homePageRoot.dataset.primaryActionUrl || '/',
        secondaryActionUrl: homePageRoot.dataset.secondaryActionUrl || '/employer/register',
    };

    createApp(HomePage, props).mount(homePageRoot);
}

const footerRoot = document.getElementById('site-footer-root');

if (footerRoot) {
    createApp(SiteFooter, {
        hasCustomLogo: footerRoot.dataset.hasCustomLogo === '1',
        jobsRoute: footerRoot.dataset.jobsRoute || '/',
        registerRoute: footerRoot.dataset.registerRoute || '/register',
        employerRegisterRoute: footerRoot.dataset.employerRegisterRoute || '/employer/register',
        jobSeekerDashboardRoute: footerRoot.dataset.jobSeekerDashboardRoute || '/job-seeker/dashboard',
        jobSeekerApplicationsRoute: footerRoot.dataset.jobSeekerApplicationsRoute || '/job-seeker/applications',
        savedJobsRoute: footerRoot.dataset.savedJobsRoute || '/job-seeker/saved-jobs',
        profileRoute: footerRoot.dataset.profileRoute || '/job-seeker/profile',
        employerDashboardRoute: footerRoot.dataset.employerDashboardRoute || '/employer',
        employerApplicationsRoute: footerRoot.dataset.employerApplicationsRoute || '/employer/applications',
        companyRoute: footerRoot.dataset.companyRoute || '/company',
        currentYear: Number(footerRoot.dataset.currentYear || new Date().getFullYear()),
    }).mount(footerRoot);
}

const jobsPageRoot = document.getElementById('jobs-page-root');

if (jobsPageRoot) {
    const globalData = window.__JOBS_DATA__ || {};

    createApp(JobsPage, {
        jobs: Array.isArray(globalData.jobs) ? globalData.jobs : [],
        jobTypes: Array.isArray(globalData.jobTypes) ? globalData.jobTypes : [],
        pagination: globalData.pagination || null,
    }).mount(jobsPageRoot);
}

window.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('#mainNavbar');

    if (! navbar) {
        return;
    }

    const toggleNavbarTheme = () => {
        const shouldScroll = window.scrollY > 24;
        navbar.classList.toggle('navbar-scrolled', shouldScroll);
    };

    toggleNavbarTheme();
    window.addEventListener('scroll', toggleNavbarTheme, { passive: true });
});

Alpine.start();
