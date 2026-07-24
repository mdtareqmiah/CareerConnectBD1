<template>
    <div class="employer-dashboard">

        <!-- =========================
             Header
        ========================== -->
        <div class="dashboard-header">

            <div>
                <h1>
                    Welcome, {{ user.name }}
                </h1>

                <p>
                    Manage your company, jobs and applications from one place.
                </p>
            </div>

            <div class="header-actions">

                <a
                    v-if="company"
                    :href="companyEditUrl"
                    class="btn btn-primary"
                >
                    Manage Company
                </a>

                <a
                    v-else
                    :href="companyCreateUrl"
                    class="btn btn-primary"
                >
                    Create Company
                </a>

            </div>

        </div>


        <!-- =========================
             Company Alert
        ========================== -->
        <div
            v-if="!company"
            class="alert alert-warning mt-4"
        >
            <strong>
                Company profile not completed.
            </strong>

            Create your company profile before posting jobs.
        </div>


        <!-- =========================
             Statistics
        ========================== -->
        <div class="stats-grid mt-4">

            
            <!-- Job Posts -->
            <a
                href="/employer/jobs"
                class="stat-card stat-card-link"
            >
                <div class="stat-icon blue">💼</div>

                <div>
                    <span>Job Posts</span>
                    <h2>{{ stats.job_postings ?? 0 }}</h2>
                </div>
            </a>

            <!-- Published Jobs -->
            <a
                href="/employer/jobs?status=published"
                class="stat-card stat-card-link"
            >
                <div class="stat-icon green">📢</div>

                <div>
                    <span>Published</span>
                    <h2>{{ jobStats.published_jobs ?? 0 }}</h2>
                </div>
            </a>

            <!-- Draft Jobs -->
            <a
                href="/employer/jobs?status=draft"
                class="stat-card stat-card-link"
            >
                <div class="stat-icon orange">📝</div>

                <div>
                    <span>Draft Jobs</span>
                    <h2>{{ jobStats.draft_jobs ?? 0 }}</h2>
                </div>
            </a>

            <!-- Applications -->
            <a
                href="/employer/applications"
                class="stat-card stat-card-link"
            >
                <div class="stat-icon purple">👥</div>

                <div>
                    <span>Applications</span>
                    <h2>{{ applicationStats.total_applications ?? 0 }}</h2>
                </div>
            </a>
        </div>


        <!-- =========================
             Application Overview
        ========================== -->
        <div class="section-card mt-4">

            <div class="section-title">

                <h3>
                    Application Overview
                </h3>

                <a href="/employer/applications">
                    View All
                </a>

            </div>


            <div class="application-grid">

                <!-- Pending -->
                <div class="application-box">

                    <h2>
                        {{ applicationStats.pending_applications ?? 0 }}
                    </h2>

                    <span>
                        Pending
                    </span>

                </div>


                <!-- Reviewed -->
                <div class="application-box">

                    <h2>
                        {{ applicationStats.reviewed_applications ?? 0 }}
                    </h2>

                    <span>
                        Reviewed
                    </span>

                </div>


                <!-- Shortlisted -->
                <div class="application-box">

                    <h2>
                        {{ applicationStats.shortlisted_applications ?? 0 }}
                    </h2>

                    <span>
                        Shortlisted
                    </span>

                </div>


                <!-- Rejected -->
                <div class="application-box">

                    <h2>
                        {{ applicationStats.rejected_applications ?? 0 }}
                    </h2>

                    <span>
                        Rejected
                    </span>

                </div>


                <!-- Hired -->
                <div class="application-box">

                    <h2>
                        {{ applicationStats.hired_applications ?? 0 }}
                    </h2>

                    <span>
                        Hired
                    </span>

                </div>

            </div>

        </div>


        <!-- =========================
             Quick Actions
        ========================== -->
        <div class="section-card mt-4">

            <div class="section-title">

                <h3>
                    Quick Actions
                </h3>

            </div>


            <div class="quick-grid">

                <!-- Post Job -->
                <a
                    href="/employer/jobs/create"
                    class="quick-card"
                >

                    <div class="quick-icon">
                        ➕
                    </div>

                    <div>
                        <strong>
                            Post Job
                        </strong>

                        <small>
                            Publish a new job
                        </small>
                    </div>

                </a>


                <!-- Manage Jobs -->
                <a
                    href="/employer/jobs"
                    class="quick-card"
                >

                    <div class="quick-icon">
                        💼
                    </div>

                    <div>
                        <strong>
                            Manage Jobs
                        </strong>

                        <small>
                            Edit your jobs
                        </small>
                    </div>

                </a>


                <!-- Applications -->
                <a
                    href="/employer/applications"
                    class="quick-card"
                >

                    <div class="quick-icon">
                        📄
                    </div>

                    <div>
                        <strong>
                            Applications
                        </strong>

                        <small>
                            Review candidates
                        </small>
                    </div>

                </a>


                <!-- Company -->
                <a
                    v-if="company"
                    :href="companyEditUrl"
                    class="quick-card"
                >

                    <div class="quick-icon">
                        🏢
                    </div>

                    <div>
                        <strong>
                            Company
                        </strong>

                        <small>
                            Update company profile
                        </small>
                    </div>

                </a>

            </div>

        </div>


        <!-- =========================
             Recent Jobs
        ========================== -->
        <div
            v-if="company"
            class="section-card mt-4"
        >

            <!-- Section Header -->
            <div class="section-title">

                <div>
                    <h3>
                        Recent Jobs
                    </h3>

                    <p class="section-subtitle">
                        Your latest job postings.
                    </p>
                </div>

                <a
                    href="/employer/jobs"
                    class="view-all-link"
                >
                    View All →
                </a>

            </div>


            <!-- =========================
                 Jobs Available
            ========================== -->
            <div
                v-if="recentJobs && recentJobs.length > 0"
                class="recent-job-list"
            >

                <div
                    v-for="job in recentJobs"
                    :key="job.id"
                    class="recent-job"
                >

                    <!-- Job Information -->
                    <div class="recent-job-info">

                        <h5>
                            {{ job.title }}
                        </h5>

                        <small>
                            {{ job.location || 'Location not specified' }}

                            <span class="job-separator">
                                •
                            </span>

                            {{ job.job_type || 'Job type not specified' }}
                        </small>

                    </div>


                    <!-- Job Actions -->
                    <div class="recent-job-actions">

                        <!-- Published -->
                        <span
                            v-if="job.status === 'published'"
                            class="badge bg-success"
                        >
                            Published
                        </span>


                        <!-- Draft -->
                        <span
                            v-else-if="job.status === 'draft'"
                            class="badge bg-secondary"
                        >
                            Draft
                        </span>


                        <!-- Other Status -->
                        <span
                            v-else
                            class="badge bg-warning text-dark"
                        >
                            {{ job.status }}
                        </span>


                        <!-- Edit -->
                        <a
                            :href="`/employer/jobs/${job.id}/edit`"
                            class="btn btn-sm btn-outline-primary edit-job-btn"
                        >
                            Edit
                        </a>

                    </div>

                </div>

            </div>


            <!-- =========================
                 No Jobs
            ========================== -->
            <div
                v-else
                class="empty-jobs"
            >

                <div class="empty-job-icon">
                    💼
                </div>

                <h5>
                    No Jobs Posted Yet
                </h5>

                <p>
                    Start recruiting by posting your first job.
                </p>

                <a
                    href="/employer/jobs/create"
                    class="btn btn-primary"
                >
                    Post Your First Job
                </a>

            </div>

        </div>


        <!-- =========================
             Company Information
        ========================== -->
        <div
            v-if="company"
            class="section-card mt-4"
        >

            <!-- Company Header -->
            <div class="section-title">

                <div>
                    <h3>
                        Company Information
                    </h3>

                    <p>
                        Your company profile details.
                    </p>
                </div>

                <a
                    :href="companyViewUrl"
                    class="view-profile-link"
                >
                    View Profile →
                </a>

            </div>


            <!-- Company Profile Content -->
            <div class="company-profile-content">


                <!-- Company Logo -->
                <div class="company-logo-wrapper">

                    <img
                        v-if="company?.company_logo_url"
                        :src="company.company_logo_url"
                        :alt="company.company_name || 'Company Logo'"
                        class="company-logo"
                        @error="handleLogoError"
                    >

                    <div
                        v-else
                        class="company-logo-placeholder"
                    >
                        🏢
                    </div>

                </div>


                <!-- Company Information -->
                <div class="company-info">

                    <!-- Company Name -->
                    <h2>
                        {{ company.company_name || 'Company Name Not Available' }}
                    </h2>


                    <!-- Description -->
                    <p class="company-description">
                        {{
                            company.company_description ||
                            'No company description available.'
                        }}
                    </p>


                    <!-- Company Details -->
                    <div class="company-grid">


                        <!-- Industry -->
                        <div class="company-detail-item">

                            <span>
                                Industry
                            </span>

                            <strong>
                                {{ company.industry || 'N/A' }}
                            </strong>

                        </div>


                        <!-- Phone -->
                        <div class="company-detail-item">

                            <span>
                                Phone
                            </span>

                            <strong>
                                {{ company.phone || 'N/A' }}
                            </strong>

                        </div>


                        <!-- Website -->
                        <div class="company-detail-item">

                            <span>
                                Website
                            </span>

                            <strong>

                                <a
                                    v-if="company.website"
                                    :href="
                                        company.website.startsWith('http')
                                            ? company.website
                                            : `https://${company.website}`
                                    "
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Visit Website ↗
                                </a>

                                <span v-else>
                                    N/A
                                </span>

                            </strong>

                        </div>


                        <!-- Location -->
                        <div class="company-detail-item">

                            <span>
                                Location
                            </span>

                            <strong>
                                {{
                                    [company.city, company.country]
                                        .filter(Boolean)
                                        .join(', ') || 'N/A'
                                }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </div>
</template>


<script setup>

import { computed } from 'vue';


const props = defineProps({

    user: {
        type: Object,
        default: () => ({})
    },

    company: {
        type: Object,
        default: null
    },

    stats: {
        type: Object,
        default: () => ({})
    },

    jobStats: {
        type: Object,
        default: () => ({})
    },

    applicationStats: {
        type: Object,
        default: () => ({})
    },

    recentJobs: {
        type: Array,
        default: () => []
    }

});


const companyCreateUrl = '/company/create';


const companyEditUrl = computed(() => {

    if (!props.company) {
        return companyCreateUrl;
    }

    return `/company/${props.company.id}/edit`;

});


const companyViewUrl = computed(() => {

    if (!props.company) {
        return companyCreateUrl;
    }

    return `/company/${props.company.id}`;

});


const handleLogoError = (event) => {

    event.target.src = '/images/company-logo-placeholder.svg';

};
const goToJobs = () => {
    window.location.href = '/employer/jobs';
};

const goToPublishedJobs = () => {
    window.location.href = '/employer/jobs?status=published';
};

const goToDraftJobs = () => {
    window.location.href = '/employer/jobs?status=draft';
};

const goToApplications = () => {
    window.location.href = '/employer/applications';
};

</script>


<style scoped>

.employer-dashboard {
    max-width: 1400px;
    margin: auto;
    padding: 30px;
    background: #f8fafc;
    min-height: 100vh;
}


/* Header */

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 20px;
}

.dashboard-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.dashboard-header p {
    color: #6b7280;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 15px;
}


/* Statistics */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.stat-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    transition: 0.25s;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.stat-card-link {
    text-decoration: none;
    color: inherit;
    cursor: pointer;
}

.stat-card-link:hover {
    color: inherit;
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 28px;
    flex-shrink: 0;
}

.blue {
    background: #dbeafe;
}

.green {
    background: #dcfce7;
}

.orange {
    background: #ffedd5;
}

.purple {
    background: #ede9fe;
}

.stat-card span {
    color: #6b7280;
    font-size: 14px;
}

.stat-card h2 {
    margin-top: 6px;
    font-size: 30px;
    font-weight: 700;
}


/* Cards */

.section-card {
    background: white;
    border-radius: 18px;
    padding: 25px;
    margin-top: 25px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
}

.section-title a {
    text-decoration: none;
    font-weight: 600;
}


/* Application */

.application-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
}

.application-box {
    background: #f9fafb;
    border-radius: 14px;
    text-align: center;
    padding: 22px;
}

.application-box h2 {
    font-size: 30px;
    margin-bottom: 8px;
}

.application-box span {
    color: #6b7280;
}


/* Quick Actions */

.quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.quick-card {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    text-decoration: none;
    color: #111827;
    transition: 0.25s;
}

.quick-card:hover {
    transform: translateY(-3px);
    border-color: #2563eb;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.08);
}

.quick-icon {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    background: #eff6ff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 25px;
    flex-shrink: 0;
}

.quick-card strong {
    display: block;
}

.quick-card small {
    color: #6b7280;
}


/* Recent Jobs */

.recent-job-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.section-subtitle {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.view-all-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}

.view-all-link:hover {
    text-decoration: underline;
}

.recent-job {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fff;
    transition: all 0.25s ease;
}

.recent-job:hover {
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.08);
}

.recent-job-info {
    flex: 1;
}

.recent-job-info h5 {
    margin: 0 0 6px;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.recent-job-info small {
    color: #6b7280;
    font-size: 14px;
}

.job-separator {
    margin: 0 6px;
}

.recent-job-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.edit-job-btn {
    border-radius: 8px;
    font-weight: 600;
}

.badge {
    padding: 8px 12px;
    font-size: 13px;
    border-radius: 8px;
}


/* Empty Jobs */

.empty-jobs {
    text-align: center;
    padding: 40px 20px;
    background: #f9fafb;
    border-radius: 14px;
    border: 1px dashed #d1d5db;
}

.empty-job-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.empty-jobs h5 {
    margin-bottom: 8px;
    font-size: 18px;
    font-weight: 600;
}

.empty-jobs p {
    color: #6b7280;
    margin-bottom: 20px;
}


/* Company */

/* ===========================
   Company Profile
=========================== */

.company-profile-content {
    display: flex;
    align-items: flex-start;
    gap: 30px;
    width: 100%;
}

/* Company Logo */

.company-logo-wrapper {
    width: 120px;
    height: 120px;
    min-width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.company-logo {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 12px;
    background: #ffffff;
}

.company-logo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    background: #f3f4f6;
}

/* Company Information */

.company-info {
    flex: 1;
    min-width: 0;
}

.company-info h2 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.company-description {
    margin: 0;
    color: #6b7280;
    line-height: 1.7;
    max-width: 850px;
}

/* Company Details */

.company-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-top: 25px;
}

.company-grid > div {
    background: #f9fafb;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 15px 16px;
    min-width: 0;
}

.company-grid > div > span:first-child {
    display: block;
    color: #9ca3af;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.company-grid strong {
    display: block;
    color: #111827;
    font-size: 14px;
    font-weight: 600;
    word-break: break-word;
}

.company-grid strong a {
    color: #2563eb;
    text-decoration: none;
}

.company-grid strong a:hover {
    text-decoration: underline;
}


/* Buttons */

.btn-primary {
    border-radius: 12px;
    padding: 11px 22px;
    font-weight: 600;
}


/* Responsive */

@media (max-width: 1200px) {

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .application-grid {
        grid-template-columns: repeat(3, 1fr);
    }

}


@media (max-width: 768px) {

    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quick-grid {
        grid-template-columns: 1fr;
    }

    .application-grid {
        grid-template-columns: 1fr;
    }

    .recent-job {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .recent-job-actions {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .company-grid {
        grid-template-columns: 1fr;
    }

    .employer-dashboard {
        padding: 18px;
    }

}

</style>