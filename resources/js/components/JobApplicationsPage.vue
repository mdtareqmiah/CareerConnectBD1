<template>
  <div class="apps-page py-5">
    <header class="apps-header">
      <div class="apps-header-shape apps-header-shape-1"></div>
      <div class="apps-header-shape apps-header-shape-2"></div>
      <div class="apps-header-shape apps-header-shape-3"></div>
      <div class="container-xl position-relative">
        <nav class="apps-breadcrumb mb-4">
          <ol class="breadcrumb apps-breadcrumb-list">
            <li class="breadcrumb-item apps-breadcrumb-item">
              <a href="/" class="apps-breadcrumb-link">
                <Home :size="14" />
                Home
              </a>
            </li>
            <li class="breadcrumb-item apps-breadcrumb-item">
              <a :href="routes.dashboard || '/job-seeker/dashboard'" class="apps-breadcrumb-link">
                <LayoutDashboard :size="14" />
                Dashboard
              </a>
            </li>
            <li class="breadcrumb-item apps-breadcrumb-item active apps-breadcrumb-current" aria-current="page">
              <BriefcaseBusiness :size="14" />
              My Applications
            </li>
          </ol>
        </nav>
        <div class="row align-items-center">
          <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="apps-header-icon">
                <BriefcaseBusiness :size="32" />
              </div>
              <h1 class="apps-header-title">My Applications</h1>
            </div>
            <p class="apps-header-subtitle">
              Monitor the progress of every job application from one organized workspace.
            </p>
          </div>
          <div class="col-lg-4 d-none d-lg-flex justify-content-lg-end mt-4 mt-lg-0">
            <div class="apps-header-illustration">
              <div class="apps-illustration-ring"></div>
              <div class="apps-illustration-dots">
                <span></span><span></span><span></span><span></span><span></span>
              </div>
              <FileText :size="72" class="apps-illustration-icon" />
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="apps-content">
      <div v-if="flashMessage" class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="status">
        {{ flashMessage }}
      </div>

      <div class="apps-toolbar mb-4">
        <form class="row g-3" method="GET" :action="routes.index">
          <div class="col-md-4">
            <label class="apps-filter-label">Search</label>
            <div class="apps-search-wrap">
              <Search :size="16" class="apps-search-icon" />
              <input
                type="text"
                name="search"
                :value="filters.search"
                class="apps-search-input"
                placeholder="Job title or company name"
              />
            </div>
          </div>
          <div class="col-md-3">
            <label class="apps-filter-label">Status</label>
            <div class="apps-select-wrap">
              <select name="status" class="apps-select">
                <option value="">All Statuses</option>
                <option v-for="(label, value) in statusOptions" :key="value" :value="value" :selected="filters.status === value">
                  {{ label }}
                </option>
              </select>
              <ChevronDown :size="16" class="apps-select-icon" />
            </div>
          </div>
          <div class="col-md-3">
            <label class="apps-filter-label">Sort By</label>
            <div class="apps-select-wrap">
              <select name="sort" class="apps-select">
                <option value="newest" :selected="(filters.sort || 'newest') === 'newest'">Newest First</option>
                <option value="oldest" :selected="filters.sort === 'oldest'">Oldest First</option>
              </select>
              <ChevronDown :size="16" class="apps-select-icon" />
            </div>
          </div>
          <div class="col-md-2 d-grid">
            <label class="apps-filter-label d-none">Filter</label>
            <button type="submit" class="apps-filter-btn">
              <Filter :size="16" />
              Apply
            </button>
          </div>
        </form>
      </div>

      <div v-if="applications.length === 0" class="apps-empty">
        <div class="apps-empty-icon">
          <BriefcaseBusiness :size="56" />
        </div>
        <h3 class="apps-empty-title">You haven't applied for any jobs yet.</h3>
        <p class="apps-empty-text">
          Browse available positions and submit your first application to start tracking your career progress.
        </p>
        <a :href="routes.jobs || '/jobs'" class="apps-empty-btn">
          Browse Jobs
          <ArrowRight :size="16" />
        </a>
      </div>

      <div v-else class="apps-list">
        <transition-group name="app-list" tag="div" class="app-list-root">
          <div
            v-for="application in applications"
            :key="application.id"
            class="app-card"
            :class="getStatusClass(application.status)"
          >
            <div class="app-card-strip" :class="getStatusClass(application.status)"></div>
            <div class="app-card-body">
              <div class="app-card-top">
                <div class="app-company">
                  <div class="app-company-logo">
                    <img v-if="application.job?.company?.company_logo_url" :src="application.job.company.company_logo_url" :alt="application.job.company.company_name" />
                    <span v-else class="app-company-placeholder">
                      <Building2 :size="20" />
                    </span>
                  </div>
                  <div class="app-company-info">
                    <div class="app-company-name">{{ application.job?.company?.company_name }}</div>
                    <div class="app-company-industry">{{ application.job?.company?.industry }}</div>
                  </div>
                </div>
                <div class="app-status-badge" :class="getStatusBadgeClass(application.status)">
                  <span class="app-status-dot"></span>
                  {{ getStatusLabel(application.status) }}
                </div>
              </div>

              <div class="app-card-middle">
                <h3 class="app-job-title">{{ application.job?.title }}</h3>
                <div class="app-job-meta">
                  <span v-if="application.job?.workplace" class="app-meta-item">
                    <MapPin :size="14" />
                    {{ application.job.workplace }}
                  </span>
                  <span v-if="application.job?.employment_type" class="app-meta-item">
                    <Clock3 :size="14" />
                    {{ formatEmploymentType(application.job.employment_type) }}
                  </span>
                  <span v-if="application.job?.location" class="app-meta-item">
                    <MapPin :size="14" />
                    {{ application.job.location }}
                  </span>
                  <span v-if="application.job?.salary_min || application.job?.salary_max" class="app-meta-item">
                    <Wallet :size="14" />
                    {{ formatSalary(application.job) }}
                  </span>
                </div>
              </div>

              <div class="app-card-timeline">
                <div class="app-timeline-track">
                  <div class="app-timeline-step" :class="{ active: isStepActive(application.status, 'applied') }">
                    <span class="app-timeline-dot"></span>
                    <span class="app-timeline-label">Applied</span>
                  </div>
                  <div class="app-timeline-step" :class="{ active: isStepActive(application.status, 'reviewed') }">
                    <span class="app-timeline-dot"></span>
                    <span class="app-timeline-label">Under Review</span>
                  </div>
                  <div class="app-timeline-step" :class="{ active: isStepActive(application.status, 'shortlisted') }">
                    <span class="app-timeline-dot"></span>
                    <span class="app-timeline-label">Shortlisted</span>
                  </div>
                  <div class="app-timeline-step" :class="{ active: isStepActive(application.status, 'accepted'), rejected: application.status === 'rejected' }">
                    <span class="app-timeline-dot"></span>
                    <span class="app-timeline-label">{{ application.status === 'rejected' ? 'Rejected' : 'Hired' }}</span>
                  </div>
                </div>
                <div class="app-timeline-line" :class="getStatusClass(application.status)">
                  <div class="app-timeline-fill" :style="{ width: getTimelineWidth(application.status) }"></div>
                </div>
              </div>

              <div class="app-card-footer">
                <div class="app-card-date">
                  <Calendar :size="14" />
                  Applied {{ application.time }}
                </div>
                <div class="app-card-actions">
                  <a :href="applicationUrl(application.id)" class="app-action-btn app-action-primary">
                    <Eye :size="14" />
                    <span class="d-none d-sm-inline">View</span>
                  </a>
                  <a v-if="application.job?.id" :href="jobUrl(application.job.id)" class="app-action-btn app-action-secondary">
                    <ExternalLink :size="14" />
                    <span class="d-none d-sm-inline">Job</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <div v-if="paginationHtml" class="apps-pagination" v-html="paginationHtml"></div>
    </div>

    <ApplicationsFooter :routes="footerRoutes" />
  </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import {
  Home,
  LayoutDashboard,
  BriefcaseBusiness,
  Search,
  ChevronDown,
  Filter,
  ArrowRight,
  Eye,
  ExternalLink,
  Calendar,
  Clock3,
  MapPin,
  Wallet,
  Building2,
  FileText,
  CircleCheck,
  CircleAlert,
} from '@lucide/vue';

export default {
  name: 'JobApplicationsPage',
  components: {
    Home,
    LayoutDashboard,
    BriefcaseBusiness,
    Search,
    ChevronDown,
    Filter,
    ArrowRight,
    Eye,
    ExternalLink,
    Calendar,
    Clock3,
    MapPin,
    Wallet,
    Building2,
    FileText,
    CircleCheck,
    CircleAlert,
  },
  props: {
    applications: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    routes: { type: Object, default: () => ({}) },
    csrfToken: { type: String, default: '' },
    authUserId: { type: [Number, String], default: null },
    paginationHtml: { type: String, default: '' },
    footerRoutes: { type: Object, default: () => ({}) },
  },
  computed: {
    statusOptions() {
      return {
        pending: 'Pending',
        reviewed: 'Under Review',
        shortlisted: 'Shortlisted',
        rejected: 'Rejected',
        accepted: 'Hired',
      };
    },
  },
  setup() {
    const flashMessage = ref('');

    onMounted(() => {
      const params = new URLSearchParams(window.location.search);
      const submitted = params.get('submitted');

      let message = '';

      if (submitted === '1') {
        message = 'Application submitted successfully.';
        params.delete('submitted');
      }

      try {
        const stored = sessionStorage.getItem('jobApplicationsFlashMessage');
        if (stored) {
          message = stored;
          sessionStorage.removeItem('jobApplicationsFlashMessage');
        }
      } catch (error) {
        // ignore storage failures
      }

      flashMessage.value = message;

      if (submitted === '1' && window.history.replaceState) {
        const nextUrl = `${window.location.pathname}${params.toString() ? `?${params.toString()}` : ''}`;
        window.history.replaceState({}, '', nextUrl);
      }
    });

    return { flashMessage };
  },
  methods: {
    getStatusClass(status) {
      const map = {
        pending: 'status-pending',
        reviewed: 'status-reviewed',
        shortlisted: 'status-shortlisted',
        rejected: 'status-rejected',
        accepted: 'status-accepted',
      };
      return map[status] || 'status-pending';
    },
    getStatusBadgeClass(status) {
      const map = {
        pending: 'badge-pending',
        reviewed: 'badge-reviewed',
        shortlisted: 'badge-shortlisted',
        rejected: 'badge-rejected',
        accepted: 'badge-accepted',
      };
      return map[status] || 'badge-pending';
    },
    getStatusLabel(status) {
      return this.statusOptions[status] || 'Pending';
    },
    getTimelineWidth(status) {
      const map = {
        pending: '25%',
        reviewed: '50%',
        shortlisted: '75%',
        accepted: '100%',
        rejected: '75%',
      };
      return map[status] || '25%';
    },
    isStepActive(status, step) {
      const order = ['pending', 'reviewed', 'shortlisted', 'accepted'];
      const currentIdx = order.indexOf(status);
      const stepIdx = order.indexOf(step);
      if (status === 'rejected') {
        return stepIdx <= 2;
      }
      return stepIdx <= currentIdx;
    },
    formatEmploymentType(type) {
      if (!type) return '';
      return type.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase());
    },
    formatSalary(job) {
      if (!job) return '';
      const min = job.salary_min;
      const max = job.salary_max;
      const currency = job.salary_currency || '';
      const period = job.salary_type ? `/${job.salary_type}` : '';
      if (min && max) return `${currency}${min} - ${currency}${max}${period}`;
      if (min) return `${currency}${min}+${period}`;
      if (max) return `Up to ${currency}${max}${period}`;
      return '';
    },
    applicationUrl(applicationId) {
      const template = this.routes.show || '/job-seeker/applications/__APPLICATION_ID__';
      return template.replace('__APPLICATION_ID__', applicationId);
    },
    jobUrl(jobId) {
      const template = this.routes.jobsShow || '/jobs/__JOB_ID__';
      return template.replace('__JOB_ID__', jobId);
    },
  },
};
</script>

<style scoped>
.apps-page {
  animation: none;
}

@keyframes apps-fade-in {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Header */
.apps-header {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0f766e 100%);
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  padding: 2.5rem 0 3rem;
}

.apps-header-shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  opacity: 0.55;
  pointer-events: none;
}

.apps-header-shape-1 {
  width: 400px;
  height: 400px;
  background: #3b82f6;
  top: -120px;
  right: -80px;
}

.apps-header-shape-2 {
  width: 300px;
  height: 300px;
  background: #14b8a6;
  bottom: -100px;
  left: -60px;
}

.apps-header-shape-3 {
  width: 200px;
  height: 200px;
  background: #f59e0b;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

@keyframes pulse-glow {
  0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
  50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.1); }
}

.apps-breadcrumb {
  margin-bottom: 1rem;
}

.apps-breadcrumb-list {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.85rem;
}

.apps-breadcrumb-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.apps-breadcrumb-item + .apps-breadcrumb-item::before {
  content: '/';
  margin-right: 0.5rem;
  color: rgba(255, 255, 255, 0.4);
}

.apps-breadcrumb-link {
  color: rgba(255, 255, 255, 0.75);
  text-decoration: none;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  transition: color 0.2s;
}

.apps-breadcrumb-link:hover {
  color: #ffffff;
}

.apps-breadcrumb-current {
  color: #ffffff;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.apps-header-icon {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.apps-header-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 700;
  color: #ffffff;
  margin: 0;
  line-height: 1.2;
}

.apps-header-subtitle {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.8);
  max-width: 36rem;
  margin: 0;
  line-height: 1.7;
}

.apps-header-illustration {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 140px;
  height: 140px;
}

.apps-illustration-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.08);
  border: 2px solid rgba(255, 255, 255, 0.15);
}

.apps-illustration-dots {
  position: absolute;
  inset: 0;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  grid-template-rows: repeat(5, 1fr);
  gap: 8px;
  padding: 20px;
}

.apps-illustration-dots span {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.15);
}

.apps-illustration-icon {
  color: #ffffff;
  opacity: 0.95;
  position: relative;
  z-index: 1;
}

/* Toolbar */
.apps-toolbar {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  padding: 1.25rem;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.apps-filter-label {
  display: block;
  font-size: 0.78rem;
  font-weight: 600;
  color: #475569;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.apps-search-wrap {
  position: relative;
}

.apps-search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  pointer-events: none;
}

.apps-search-input {
  width: 100%;
  padding: 0.7rem 1rem 0.7rem 2.75rem;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  font-size: 0.92rem;
  color: #111827;
  background: #f8fafc;
  transition: all 0.2s ease;
}

.apps-search-input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  background: #ffffff;
}

.apps-search-input::placeholder {
  color: #94a3b8;
}

.apps-select-wrap {
  position: relative;
}

.apps-select {
  width: 100%;
  padding: 0.7rem 2.5rem 0.7rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  font-size: 0.92rem;
  color: #111827;
  background: #f8fafc;
  appearance: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.apps-select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  background: #ffffff;
}

.apps-select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  pointer-events: none;
}

.apps-filter-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.7rem 1.25rem;
  border-radius: 14px;
  border: 0;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #ffffff;
  font-size: 0.92rem;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
  transition: all 0.2s ease;
  height: 100%;
  min-height: 44px;
}

.apps-filter-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
}

/* List */
.app-list-root {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.app-list-enter-active,
.app-list-leave-active {
  transition: all 0.35s ease;
}

.app-list-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}

.app-list-leave-to {
  opacity: 0;
  transform: translateX(20px);
}

/* Card */
.app-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  box-shadow: 0 2px 12px rgba(15, 23, 42, 0.03);
  transition: all 0.25s ease;
  position: relative;
  overflow: hidden;
}

.app-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
  border-color: rgba(37, 99, 235, 0.15);
}

.app-card-strip {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  border-radius: 20px 0 0 20px;
  transition: background 0.25s ease;
}

.app-card-strip.status-pending { background: #94a3b8; }
.app-card-strip.status-reviewed { background: #3b82f6; }
.app-card-strip.status-shortlisted { background: #f59e0b; }
.app-card-strip.status-rejected { background: #ef4444; }
.app-card-strip.status-accepted { background: #22c55e; }

.app-card-body {
  padding: 1.5rem;
  padding-left: 1.75rem;
}

.app-card-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1rem;
}

.app-company {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.app-company-logo {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  overflow: hidden;
  flex-shrink: 0;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e5e7eb;
}

.app-company-logo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.app-company-placeholder {
  color: #94a3b8;
  display: flex;
  align-items: center;
  justify-content: center;
}

.app-company-info {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.app-company-name {
  font-weight: 600;
  color: #111827;
  font-size: 0.95rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.app-company-industry {
  font-size: 0.82rem;
  color: #6b7280;
}

.app-status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 0.9rem;
  border-radius: 999px;
  font-size: 0.78rem;
  font-weight: 600;
  white-space: nowrap;
  flex-shrink: 0;
}

.app-status-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  flex-shrink: 0;
}

.app-status-badge.badge-pending {
  background: rgba(148, 163, 184, 0.1);
  color: #475569;
}

.app-status-badge.badge-pending .app-status-dot { background: #94a3b8; }

.app-status-badge.badge-reviewed {
  background: rgba(59, 130, 246, 0.1);
  color: #1d4ed8;
}

.app-status-badge.badge-reviewed .app-status-dot { background: #3b82f6; }

.app-status-badge.badge-shortlisted {
  background: rgba(245, 158, 11, 0.1);
  color: #b45309;
}

.app-status-badge.badge-shortlisted .app-status-dot { background: #f59e0b; }

.app-status-badge.badge-rejected {
  background: rgba(239, 68, 68, 0.1);
  color: #b91c1c;
}

.app-status-badge.badge-rejected .app-status-dot { background: #ef4444; }

.app-status-badge.badge-accepted {
  background: rgba(34, 197, 94, 0.1);
  color: #15803d;
}

.app-status-badge.badge-accepted .app-status-dot { background: #22c55e; }

.app-card-middle {
  margin-bottom: 1.25rem;
}

.app-job-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.05rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.6rem;
  line-height: 1.4;
}

.app-job-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.app-meta-item {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: #6b7280;
}

.app-meta-item svg {
  color: #94a3b8;
  flex-shrink: 0;
}

/* Timeline */
.app-card-timeline {
  margin-bottom: 1.25rem;
}

.app-timeline-track {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 1;
}

.app-timeline-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  flex: 1;
}

.app-timeline-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #e2e8f0;
  border: 2px solid #ffffff;
  box-shadow: 0 0 0 2px #e2e8f0;
  transition: all 0.3s ease;
}

.app-timeline-step.active .app-timeline-dot {
  background: #2563eb;
  box-shadow: 0 0 0 2px #2563eb;
}

.app-timeline-step.rejected .app-timeline-dot {
  background: #ef4444;
  box-shadow: 0 0 0 2px #ef4444;
}

.app-timeline-label {
  font-size: 0.72rem;
  font-weight: 600;
  color: #94a3b8;
  text-align: center;
  transition: color 0.3s ease;
}

.app-timeline-step.active .app-timeline-label {
  color: #2563eb;
}

.app-timeline-step.rejected .app-timeline-label {
  color: #ef4444;
}

.app-timeline-line {
  position: absolute;
  top: 6px;
  left: 0;
  right: 0;
  height: 3px;
  background: #e2e8f0;
  border-radius: 999px;
  z-index: 0;
}

.app-timeline-fill {
  height: 100%;
  background: #2563eb;
  border-radius: 999px;
  transition: width 0.5s ease;
}

.app-timeline-line.status-rejected .app-timeline-fill {
  background: #ef4444;
}

.app-timeline-line.status-accepted .app-timeline-fill {
  background: #22c55e;
}

/* Footer */
.app-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
  flex-wrap: wrap;
}

.app-card-date {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: #6b7280;
}

.app-card-date svg {
  color: #94a3b8;
}

.app-card-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.app-action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.9rem;
  border-radius: 12px;
  border: 1px solid transparent;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.app-action-primary {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #ffffff;
  border-color: transparent;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.app-action-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
  color: #ffffff;
}

.app-action-secondary {
  background: #ffffff;
  color: #374151;
  border-color: #e5e7eb;
}

.app-action-secondary:hover {
  background: #f8fafc;
  border-color: #2563eb;
  color: #2563eb;
  transform: translateY(-1px);
}

/* Empty State */
.apps-empty {
  text-align: center;
  padding: 4rem 2rem;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}

.apps-empty-icon {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: rgba(37, 99, 235, 0.08);
  color: #2563eb;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  animation: float 4s ease-in-out infinite;
}

.apps-empty-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin: 0 0 0.5rem;
}

.apps-empty-text {
  font-size: 1rem;
  color: #6b7280;
  max-width: 28rem;
  margin: 0 auto 1.5rem;
  line-height: 1.7;
}

.apps-empty-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.75rem;
  border-radius: 14px;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #ffffff;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
  transition: all 0.2s ease;
}

.apps-empty-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 28px rgba(37, 99, 235, 0.35);
  color: #ffffff;
}

/* Pagination */
.apps-pagination {
  margin-top: 2rem;
}

.apps-pagination :deep(.pagination) {
  justify-content: center;
  gap: 0.4rem;
}

.apps-pagination :deep(.page-link) {
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  color: #475569;
  font-weight: 600;
  min-width: 42px;
  height: 42px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.apps-pagination :deep(.page-item.active .page-link) {
  background: #2563eb;
  border-color: #2563eb;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.apps-pagination :deep(.page-link:hover) {
  background: #f8fafc;
  border-color: #2563eb;
  color: #2563eb;
  transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 767.98px) {
  .apps-header {
    padding: 1.75rem 0 2rem;
  }

  .apps-card-body {
    padding: 1.25rem;
    padding-left: 1.25rem;
  }

  .app-card-strip {
    width: 100%;
    height: 4px;
    border-radius: 20px 20px 0 0;
    top: 0;
    left: 0;
    right: 0;
  }

  .app-card-top {
    flex-direction: column;
    gap: 0.75rem;
  }

  .app-timeline-track {
    gap: 0.25rem;
  }

  .app-timeline-label {
    font-size: 0.65rem;
  }

  .app-card-footer {
    flex-direction: column;
    align-items: flex-start;
  }

  .apps-toolbar {
    padding: 1rem;
    border-radius: 16px;
  }
}

@media (max-width: 575.98px) {
  .app-card {
    border-radius: 16px;
  }

  .app-job-title {
    font-size: 0.95rem;
  }

  .app-job-meta {
    gap: 0.75rem;
  }
}
</style>
