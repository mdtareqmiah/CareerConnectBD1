import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import EmployerDashboard from './components/employer/EmployerDashboard.vue';

const employerDashboard = document.getElementById('employer-dashboard');

if (employerDashboard) {
    try {
        const user = JSON.parse(
            employerDashboard.dataset.user || '{}'
        );

        const company = JSON.parse(
            employerDashboard.dataset.company || 'null'
        );

        const stats = JSON.parse(
            employerDashboard.dataset.stats || '{}'
        );

        const jobStats = JSON.parse(
            employerDashboard.dataset.jobStats || '{}'
        );

        const applicationStats = JSON.parse(
            employerDashboard.dataset.applicationStats || '{}'
        );

        const recentJobs = JSON.parse(
            employerDashboard.dataset.recentJobs || '[]'
        );

        createApp(EmployerDashboard, {
            user,
            company,
            stats,
            jobStats,
            applicationStats,
            recentJobs,
        }).mount(employerDashboard);

    } catch (error) {
        console.error(
            'Employer Dashboard Vue Error:',
            error
        );
    }
}