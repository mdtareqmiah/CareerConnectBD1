import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { createApp } from 'vue';
import * as bootstrap from 'bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

import Alpine from 'alpinejs';
import HomePage from './components/HomePage.vue';
import SiteFooter from './components/SiteFooter.vue';
import JobsPage from './components/JobsPage.vue';
import EmployerDashboard from './components/employer/EmployerDashboard.vue';

window.Alpine = Alpine;
window.bootstrap = bootstrap;

const homePageRoot = document.getElementById('home-page-root');

if (homePageRoot) {
    const props = {
        featuredJobs: homePageRoot.dataset.featuredJobs ? JSON.parse(homePageRoot.dataset.featuredJobs) : [],
        topCompanies: homePageRoot.dataset.topCompanies ? JSON.parse(homePageRoot.dataset.topCompanies) : [],
        testimonials: homePageRoot.dataset.testimonials ? JSON.parse(homePageRoot.dataset.testimonials) : [],
        jobsRoute: homePageRoot.dataset.jobsRoute ?? '/',
        registerRoute: homePageRoot.dataset.registerRoute ?? '/register',
        postJobUrl: homePageRoot.dataset.postJobUrl ?? '/employer/register',
        primaryActionUrl: homePageRoot.dataset.primaryActionUrl ?? '/',
        secondaryActionUrl: homePageRoot.dataset.secondaryActionUrl ?? '/employer/register',
    };

    createApp(HomePage, props).mount(homePageRoot);
}

const footerRoot = document.getElementById('site-footer-root');

if (footerRoot) {
    createApp(SiteFooter, {
        hasCustomLogo: footerRoot.dataset.hasCustomLogo === '1',
        jobsRoute: footerRoot.dataset.jobsRoute ?? '/',
        registerRoute: footerRoot.dataset.registerRoute ?? '/register',
        employerRegisterRoute: footerRoot.dataset.employerRegisterRoute ?? '/employer/register',
        jobSeekerDashboardRoute: footerRoot.dataset.jobSeekerDashboardRoute ?? '/job-seeker/dashboard',
        jobSeekerApplicationsRoute: footerRoot.dataset.jobSeekerApplicationsRoute ?? '/job-seeker/applications',
        savedJobsRoute: footerRoot.dataset.savedJobsRoute ?? '/job-seeker/saved-jobs',
        profileRoute: footerRoot.dataset.profileRoute ?? '/job-seeker/profile',
        employerDashboardRoute: footerRoot.dataset.employerDashboardRoute ?? '/employer',
        employerApplicationsRoute: footerRoot.dataset.employerApplicationsRoute ?? '/employer/applications',
        companyRoute: footerRoot.dataset.companyRoute ?? '/company',
        currentYear: Number(footerRoot.dataset.currentYear ?? new Date().getFullYear()),
    }).mount(footerRoot);
}

const jobsPageRoot = document.getElementById('jobs-page-root');

if (jobsPageRoot) {
    const globalData = window.__JOBS_DATA__ || {};

    createApp(JobsPage, {
        jobs: Array.isArray(globalData.jobs) ? globalData.jobs : [],
        jobTypes: Array.isArray(globalData.jobTypes) ? globalData.jobTypes : [],
        pagination: globalData.pagination ?? null,
    }).mount(jobsPageRoot);
}

const employerDashboard = document.getElementById('employer-dashboard');

if (employerDashboard) {
    try {
        const user = JSON.parse(employerDashboard.dataset.user ?? '{}');
        const company = JSON.parse(employerDashboard.dataset.company ?? 'null');
        const stats = JSON.parse(employerDashboard.dataset.stats ?? '{}');
        const jobStats = JSON.parse(employerDashboard.dataset.jobStats ?? '{}');
        const applicationStats = JSON.parse(employerDashboard.dataset.applicationStats ?? '{}');
        const recentJobs = JSON.parse(employerDashboard.dataset.recentJobs ?? '[]');

        createApp(EmployerDashboard, {
            user,
            company,
            stats,
            jobStats,
            applicationStats,
            recentJobs,
        }).mount(employerDashboard);
    } catch (error) {
        console.error('Employer Dashboard Vue Error:', error);
    }
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

initializeBootstrapDropdowns();

const authUserId = document.querySelector('meta[name="auth-user-id"]')?.getAttribute('content');

if (authUserId) {
    initializeRealtimeNotifications(authUserId);
}

function initializeBootstrapDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-bs-toggle="dropdown"]');

    dropdownTriggers.forEach((trigger) => {
        if (!trigger.dataset.dropdownInitialized) {
            bootstrap.Dropdown.getOrCreateInstance(trigger);
            trigger.dataset.dropdownInitialized = 'true';
        }
    });
}

window.addEventListener('load', initializeBootstrapDropdowns);
document.addEventListener('DOMContentLoaded', initializeBootstrapDropdowns);

function initializeRealtimeNotifications(authUserId) {
    const appKey = import.meta.env.VITE_REVERB_APP_KEY;

    if (!appKey) {
        return;
    }

    const scheme = import.meta.env.VITE_REVERB_SCHEME ?? 'http';
    const wsHost = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;
    const wsPort = Number(import.meta.env.VITE_REVERB_PORT ?? 8080);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: appKey,
        wsHost,
        wsPort,
        wssPort: wsPort,
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        },
    });

    window.Echo.private(`notifications.${authUserId}`)
        .listen('.notification.updated', (payload) => {
            applyNotificationRealtimeUpdate(payload);
        });
}

function applyNotificationRealtimeUpdate(payload) {
    const unreadCount = Number(payload?.unread_count ?? 0);
    updateNotificationCount(unreadCount);

    if (payload?.kind === 'notification.created' && payload.notification) {
        prependNotification(payload.notification);
        showNotificationToast(payload.notification);
        showBrowserNotification(payload.notification);
        return;
    }
    if (payload?.action === 'deleted' && payload?.notification_id) {
        removeNotificationItem(payload.notification_id);
    }
    if (payload?.action === 'read' && payload?.notification_id) {
        markNotificationRead(payload.notification_id);
    }

    if (payload?.action === 'read_all') {
        markAllNotificationsRead();
    }
}

function updateNotificationCount(count) {
    document.querySelectorAll('[data-notification-count]').forEach((badge) => {
        if (count > 0) {
            badge.textContent = String(count);
            badge.classList.remove('d-none');
        } else {
            badge.textContent = '';
            badge.classList.add('d-none');
        }
    });
}

function prependNotification(notification) {
    const lists = document.querySelectorAll('[data-notification-list]');

    lists.forEach((list) => {
        list.querySelectorAll('[data-notification-empty]').forEach((node) => node.remove());

        const dividerItem = list.querySelector('li hr.dropdown-divider')?.parentElement;
        const item = document.createElement('li');
        item.setAttribute('data-notification-item', '1');
        item.setAttribute('data-notification-id', String(notification.id ?? ''));

        const title = escapeHtml(notification.title ?? 'Notification');
        const message = escapeHtml(notification.message ?? '');
        const link = escapeHtml(notification.link ?? list.getAttribute('data-notification-view-all-url') ?? '/notifications');

        item.innerHTML = `
            <a class="dropdown-item" href="${link}">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold">${title}</div>
                        <div class="small text-muted">${message}</div>
                    </div>
                    <span class="badge bg-primary">New</span>
                </div>
            </a>
        `;

        if (dividerItem) {
            list.insertBefore(item, dividerItem);
        } else {
            list.appendChild(item);
        }
    });
}

function removeNotificationItem(notificationId) {
    document
        .querySelectorAll(`[data-notification-id="${CSS.escape(String(notificationId))}"]`)
        .forEach((node) => node.remove());

    ensureEmptyState();
}

function markNotificationRead(notificationId) {
    document
        .querySelectorAll(`[data-notification-id="${CSS.escape(String(notificationId))}"] .badge.bg-primary`)
        .forEach((badge) => badge.remove());
}

function markAllNotificationsRead() {
    document.querySelectorAll('[data-notification-list] .badge.bg-primary').forEach((badge) => badge.remove());
}

function ensureEmptyState() {
    document.querySelectorAll('[data-notification-list]').forEach((list) => {
        const itemCount = list.querySelectorAll('[data-notification-item]').length;

        if (itemCount === 0 && !list.querySelector('[data-notification-empty]')) {
            const empty = document.createElement('li');
            empty.setAttribute('data-notification-empty', '1');
            empty.innerHTML = '<span class="dropdown-item-text text-muted">No notifications yet.</span>';

            const dividerItem = list.querySelector('li hr.dropdown-divider')?.parentElement;

            if (dividerItem) {
                list.insertBefore(empty, dividerItem);
            } else {
                list.appendChild(empty);
            }
        }
    });
}

function showNotificationToast(notification) {
    const container = getOrCreateToastContainer();
    const toastElement = document.createElement('div');

    toastElement.className = 'toast align-items-center border-0';
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');

    const title = escapeHtml(notification.title ?? 'Notification');
    const message = escapeHtml(notification.message ?? '');
    const link = escapeHtml(notification.link ?? '/notifications');

    toastElement.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <div>${message}</div>
            <a href="${link}" class="small text-primary text-decoration-none">View notification</a>
        </div>
    `;

    container.appendChild(toastElement);

    const toast = bootstrap.Toast.getOrCreateInstance(toastElement, { delay: 4500 });
    toast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function showBrowserNotification(notification) {
    if (!('Notification' in window) || document.hasFocus()) {
        return;
    }

    const title = notification.title ?? 'Notification';
    const body = notification.message ?? '';

    if (Notification.permission === 'granted') {
        new Notification(title, { body });
        return;
    }

    if (Notification.permission === 'default') {
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                new Notification(title, { body });
            }
        });
    }
}

function getOrCreateToastContainer() {
    const existing = document.getElementById('realtime-notification-toasts');

    if (existing) {
        return existing;
    }

    const container = document.createElement('div');
    container.id = 'realtime-notification-toasts';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1080';
    document.body.appendChild(container);

    return container;
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}
