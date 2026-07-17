<template>
    <div class="jobs-page">
        <!-- Jobs Header Section -->
        <section class="jobs-header">
            <div class="jobs-header-bg">
                <div class="jobs-header-shape jobs-header-shape-1"></div>
                <div class="jobs-header-shape jobs-header-shape-2"></div>
                <div class="jobs-header-shape jobs-header-shape-3"></div>
            </div>
            <div class="container-xl position-relative">
                <div class="row align-items-center py-5">
                    <div class="col-lg-7">
                        <div class="jobs-header-badge">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>Discover opportunities</span>
                        </div>
                        <h1 class="jobs-header-title">Browse current openings</h1>
                        <p class="jobs-header-subtitle">Explore the latest published roles from employers across the platform. Find your next career move with AI-powered matching.</p>

                        <div class="jobs-header-stats row g-3 mt-2">
                            <div class="col-6 col-md-3" v-for="stat in stats" :key="stat.label">
                                <div class="jobs-stat-card">
                                    <div class="jobs-stat-value">{{ stat.value }}</div>
                                    <div class="jobs-stat-label">{{ stat.label }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="jobs-header-illustration">
                            <img src="/images/jobs.png" alt="AI recruitment" class="img-fluid rounded-4 shadow-elevated">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search & Filter Section -->
        <section class="jobs-search-section py-4">
            <div class="container-xl">
                <div class="jobs-search-card">
                    <form @submit.prevent="handleSearch" class="jobs-search-form">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-lg-4">
                                <label class="jobs-search-label">Job Title / Keyword</label>
                                <div class="jobs-search-input-group">
                                    <i class="bi bi-search"></i>
                                    <input
                                        type="text"
                                        v-model="filters.search"
                                        class="form-control jobs-search-input"
                                        placeholder="Title, company, keyword..."
                                    >
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="jobs-search-label">Job Type</label>
                                <select v-model="filters.job_type" class="form-select jobs-search-select">
                                    <option value="">All types</option>
                                    <option v-for="type in jobTypes" :key="type" :value="type">{{ type }}</option>
                                </select>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="jobs-search-label">Min Salary</label>
                                <div class="jobs-search-input-group">
                                    <i class="bi bi-currency-dollar"></i>
                                    <input
                                        type="number"
                                        v-model="filters.salary_min"
                                        class="form-control jobs-search-input"
                                        placeholder="Min"
                                        min="0"
                                    >
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="jobs-search-label">Max Salary</label>
                                <div class="jobs-search-input-group">
                                    <i class="bi bi-currency-dollar"></i>
                                    <input
                                        type="number"
                                        v-model="filters.salary_max"
                                        class="form-control jobs-search-input"
                                        placeholder="Max"
                                        min="0"
                                    >
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 d-grid gap-2">
                                <button type="submit" class="btn btn-primary jobs-search-btn">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                                <button type="button" @click="resetFilters" class="btn btn-outline-secondary jobs-reset-btn">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters -->
                    <div v-if="hasActiveFilters" class="jobs-active-filters mt-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="text-muted small">Active filters:</span>
                            <span v-if="filters.search" class="jobs-filter-tag">
                                "{{ filters.search }}"
                                <button @click="filters.search = ''" class="jobs-filter-tag-remove">&times;</button>
                            </span>
                            <span v-if="filters.job_type" class="jobs-filter-tag">
                                {{ filters.job_type }}
                                <button @click="filters.job_type = ''" class="jobs-filter-tag-remove">&times;</button>
                            </span>
                            <span v-if="filters.salary_min" class="jobs-filter-tag">
                                Min: {{ filters.salary_min }}
                                <button @click="filters.salary_min = ''" class="jobs-filter-tag-remove">&times;</button>
                            </span>
                            <span v-if="filters.salary_max" class="jobs-filter-tag">
                                Max: {{ filters.salary_max }}
                                <button @click="filters.salary_max = ''" class="jobs-filter-tag-remove">&times;</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Results Header -->
        <section class="jobs-results-header py-3">
            <div class="container-xl">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h2 class="jobs-results-title">Available Positions</h2>
                        <p class="jobs-results-count text-muted mb-0">
                            Showing {{ jobs.length }} result{{ jobs.length === 1 ? '' : 's' }}
                            <span v-if="hasActiveFilters" class="text-primary">(filtered)</span>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <select v-model="sortBy" @change="handleSort" class="form-select jobs-sort-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!-- Job Listings -->
        <section class="jobs-listings py-4">
            <div class="container-xl">
                <!-- Loading State -->
                <div v-if="loading" class="jobs-loading">
                    <div class="row g-4">
                        <div class="col-lg-6" v-for="i in 6" :key="i">
                            <div class="jobs-skeleton-card">
                             <div class="skeleton skeleton-header"></div>
                                <div class="skeleton skeleton-body"></div>
                                <div class="skeleton skeleton-footer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="jobs.length === 0" class="jobs-empty-state text-center py-5">
                    <div class="jobs-empty-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 class="jobs-empty-title">No jobs found</h3>
                    <p class="jobs-empty-text text-muted">Try adjusting your search criteria or browse all available positions.</p>
                    <button @click="resetFilters" class="btn btn-primary jobs-empty-btn">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                    </button>
                </div>

                <!-- Job Cards Grid -->
                <div v-else class="row g-4">
                    <div class="col-lg-6" v-for="job in jobs" :key="job.id">
                        <job-card
                            :job="job"
                            @save="handleSave"
                            @apply="handleApply"
                        ></job-card>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="jobs.length > 0 && pagination" class="jobs-pagination mt-5">
                    <nav aria-label="Jobs pagination">
                        <ul class="jobs-pagination-list">
                            <li class="jobs-pagination-item" :class="{ disabled: !pagination.prev_page_url }">
                                <a
                                    v-if="pagination.prev_page_url"
                                    :href="pagination.prev_page_url"
                                    class="jobs-pagination-link"
                                    aria-label="Previous page"
                                >
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                                <span v-else class="jobs-pagination-link disabled" aria-disabled="true">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                            <li
                                v-for="page in visiblePages"
                                :key="page"
                                class="jobs-pagination-item"
                                :class="{ active: page === currentPage }"
                            >
                                <a
                                    v-if="page !== '...'"
                                    :href="page === currentPage ? '#' : paginationPath(page)"
                                    class="jobs-pagination-link"
                                    :aria-current="page === currentPage ? 'page' : null"
                                >
                                    {{ page }}
                                </a>
                                <span v-else class="jobs-pagination-link disabled">...</span>
                            </li>
                            <li class="jobs-pagination-item" :class="{ disabled: !pagination.next_page_url }">
                                <a
                                    v-if="pagination.next_page_url"
                                    :href="pagination.next_page_url"
                                    class="jobs-pagination-link"
                                    aria-label="Next page"
                                >
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                                <span v-else class="jobs-pagination-link disabled" aria-disabled="true">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import JobCard from './JobCard.vue';

const props = defineProps({
    jobs: { type: Array, default: () => [] },
    jobTypes: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => null },
});

const loading = ref(false);
const sortBy = ref('newest');

const filters = ref({
    search: '',
    job_type: '',
    salary_min: '',
    salary_max: '',
});

const pagination = ref(null);

const stats = [
    { label: 'Available Jobs', value: '1200+' },
    { label: 'Companies', value: '350+' },
    { label: 'Remote Jobs', value: '200+' },
    { label: 'New Today', value: '50+' },
];

const currentPage = computed(() => pagination.value?.current_page || 1);
const lastPage = computed(() => pagination.value?.last_page || 1);

const visiblePages = computed(() => {
    const pages = [];
    const total = lastPage.value;
    const current = currentPage.value;

    if (total <= 7) {
        for (let i = 1; i <= total; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);
        if (current > 3) pages.push('...');

        const start = Math.max(2, current - 1);
        const end = Math.min(total - 1, current + 1);

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        if (current < total - 2) pages.push('...');
        pages.push(total);
    }

    return pages;
});

const hasActiveFilters = computed(() => {
    return filters.value.search || filters.value.job_type || filters.value.salary_min || filters.value.salary_max;
});

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    filters.value = {
        search: urlParams.get('search') || '',
        job_type: urlParams.get('job_type') || '',
        salary_min: urlParams.get('salary_min') || '',
        salary_max: urlParams.get('salary_max') || '',
    };

    if (urlParams.get('sort')) {
        sortBy.value = urlParams.get('sort');
    }

    buildPagination();
});

function buildPagination() {
    if (props.pagination) {
        const current = props.pagination.current_page || 1;
        const last = props.pagination.last_page || 1;
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.searchParams);

        const prevParams = new URLSearchParams(params);
        prevParams.set('page', current - 1);

        const nextParams = new URLSearchParams(params);
        nextParams.set('page', current + 1);

        pagination.value = {
            current_page: current,
            last_page: last,
            prev_page_url: current > 1 ? `${url.pathname}?${prevParams.toString()}` : null,
            next_page_url: current < last ? `${url.pathname}?${nextParams.toString()}` : null,
        };
    } else {
        pagination.value = {
            current_page: 1,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null,
        };
    }
}

function handleSearch() {
    const params = new URLSearchParams();

    if (filters.value.search) params.set('search', filters.value.search);
    if (filters.value.job_type) params.set('job_type', filters.value.job_type);
    if (filters.value.salary_min) params.set('salary_min', filters.value.salary_min);
    if (filters.value.salary_max) params.set('salary_max', filters.value.salary_max);
    if (sortBy.value !== 'newest') params.set('sort', sortBy.value);

    const url = `${window.location.pathname}?${params.toString()}`;
    window.location.href = url;
}

function handleSort() {
    handleSearch();
}

function resetFilters() {
    filters.value = {
        search: '',
        job_type: '',
        salary_min: '',
        salary_max: '',
    };
    sortBy.value = 'newest';
    window.location.href = window.location.pathname;
}

function handleSave(payload) {
    console.log('Save job:', payload);
}

function handleApply(payload) {
    if (payload && payload.jobId) {
        window.location.href = `/jobs/${payload.jobId}/apply`;
    }
}

function paginationPath(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page);
    return url.toString();
}
</script>

<style scoped>
.jobs-page {
    min-height: 100vh;
}

/* Header Section */
.jobs-header {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    overflow: hidden;
}

.jobs-header-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.jobs-header-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.4;
}

.jobs-header-shape-1 {
    width: 400px;
    height: 400px;
    background: #2563eb;
    top: -100px;
    right: -100px;
    animation: float 8s ease-in-out infinite;
}

.jobs-header-shape-2 {
    width: 300px;
    height: 300px;
    background: #7c3aed;
    bottom: -80px;
    left: -80px;
    animation: float 10s ease-in-out infinite reverse;
}

.jobs-header-shape-3 {
    width: 250px;
    height: 250px;
    background: #2563eb;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: pulse-glow 4s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse-glow {
    0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.1); }
}

.jobs-header-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(37, 99, 235, 0.15);
    border: 1px solid rgba(37, 99, 235, 0.3);
    border-radius: 999px;
    color: #93c5fd;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.jobs-header-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: #ffffff;
    line-height: 1.2;
    margin-bottom: 1rem;
}

.jobs-header-subtitle {
    font-size: 1.05rem;
    color: #94a3b8;
    line-height: 1.7;
    max-width: 36rem;
}

.jobs-header-stats {
    margin-top: 2rem;
}

.jobs-stat-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 1.25rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.jobs-stat-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.jobs-stat-value {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
}

.jobs-stat-label {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-top: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.jobs-header-illustration {
    animation: fade-in-up 0.8s ease-out;
}

/* Search Section */
.jobs-search-section {
    position: relative;
    z-index: 10;
    margin-top: -1.5rem;
    padding: 2rem 0;
    background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 50%, #f0f9ff 100%);
    overflow: hidden;
}

.jobs-search-section::before {
    content: '';
    position: absolute;
    top: -60px;
    left: -80px;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.jobs-search-section::after {
    content: '';
    position: absolute;
    bottom: -40px;
    right: -60px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(14, 165, 233, 0.07) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.jobs-search-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(99, 102, 241, 0.08);
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 25px 50px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.jobs-search-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 25%, #ec4899 50%, #f59e0b 75%, #6366f1 100%);
    background-size: 200% 100%;
    animation: gradient-shift 6s ease infinite;
}

@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.jobs-search-card:hover {
    box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
    transform: translateY(-2px);
}

.jobs-search-form {
    display: contents;
}

.jobs-search-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    transition: color 0.3s ease;
}

.jobs-search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.jobs-search-input-group i {
    position: absolute;
    left: 1rem;
    color: #6366f1;
    font-size: 1rem;
    z-index: 2;
    pointer-events: none;
    transition: all 0.3s ease;
}

.jobs-search-input {
    padding-left: 2.75rem !important;
    border: 2px solid #e2e8f0;
    border-radius: 0.875rem;
    min-height: 48px;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #f8fafc;
    color: #1e293b;
}

.jobs-search-input::placeholder {
    color: #94a3b8;
}

.jobs-search-input:hover {
    border-color: #6366f1;
    background: #ffffff;
}

.jobs-search-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 4px 12px rgba(99, 102, 241, 0.08);
    background: #ffffff;
    outline: none;
}

.jobs-search-input-group:focus-within i {
    color: #4f46e5;
    transform: scale(1.1);
}

.jobs-search-select {
    border: 2px solid #e2e8f0;
    border-radius: 0.875rem;
    min-height: 48px;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    background: #f8fafc;
    color: #1e293b;
    padding-right: 2.5rem;
}

.jobs-search-select:hover {
    border-color: #6366f1;
    background: #ffffff;
}

.jobs-search-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 4px 12px rgba(99, 102, 241, 0.08);
    background: #ffffff;
    outline: none;
}

.jobs-search-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.875rem;
    font-weight: 600;
    min-height: 48px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    color: white;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.jobs-search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.jobs-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(99, 102, 241, 0.35);
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}

.jobs-search-btn:hover::before {
    left: 100%;
}

.jobs-search-btn:active {
    transform: translateY(0);
}

.jobs-reset-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.875rem;
    font-weight: 600;
    min-height: 48px;
    border: 2px solid #e2e8f0;
    color: #64748b;
    background: #f8fafc;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.jobs-reset-btn:hover {
    background: #ffffff;
    border-color: #6366f1;
    color: #4f46e5;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
}

.jobs-active-filters {
    padding-top: 1rem;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    animation: slide-down 0.3s ease;
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.jobs-filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.85rem;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    color: #4f46e5;
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    animation: pop-in 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes pop-in {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.jobs-filter-tag:hover {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
    border-color: rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
}

.jobs-filter-tag-remove {
    background: none;
    border: none;
    color: #6366f1;
    font-size: 1.1rem;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    width: 1.2rem;
    height: 1.2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.jobs-filter-tag-remove:hover {
    background: rgba(99, 102, 241, 0.2);
    color: #4f46e5;
    transform: rotate(90deg);
}

/* Results Header */
.jobs-results-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.25rem;
}

.jobs-results-count {
    font-size: 0.9rem;
}

.jobs-sort-select {
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 0.75rem;
    min-height: 40px;
    font-size: 0.9rem;
    padding: 0.5rem 2rem 0.5rem 1rem;
    cursor: pointer;
}

/* Loading State */
.jobs-loading {
    animation: fade-in 0.3s ease;
}

.jobs-skeleton-card {
    background: white;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 1.25rem;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.jobs-skeleton-card .skeleton {
    background: linear-gradient(90deg, #f1f5f9, #e2e8f0, #f1f5f9);
    background-size: 200% 100%;
    animation: pulse 1.5s infinite ease-in-out;
    border-radius: 0.5rem;
}

.jobs-skeleton-card .skeleton-header {
    height: 3.5rem;
    margin-bottom: 1rem;
}

.jobs-skeleton-card .skeleton-body {
    height: 6rem;
    margin-bottom: 1rem;
}

.jobs-skeleton-card .skeleton-footer {
    height: 2.5rem;
}

/* Empty State */
.jobs-empty-state {
    background: white;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 1.5rem;
    padding: 4rem 2rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.jobs-empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.jobs-empty-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.5rem;
}

.jobs-empty-text {
    max-width: 28rem;
    margin: 0 auto 1.5rem;
    font-size: 1rem;
}

.jobs-empty-btn {
    padding: 0.75rem 2rem;
    border-radius: 0.875rem;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
}

/* Pagination */
.jobs-pagination {
    display: flex;
    justify-content: center;
    padding-top: 2rem;
}

.jobs-pagination-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.jobs-pagination-item {
    display: flex;
}

.jobs-pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 0.75rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: white;
    color: #475569;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.jobs-pagination-link:hover:not(.disabled) {
    background: #f8fafc;
    border-color: #2563eb;
    color: #2563eb;
    transform: translateY(-1px);
}

.jobs-pagination-link.active {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.jobs-pagination-link.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 991.98px) {
    .jobs-search-card {
        padding: 1.25rem;
    }

    .jobs-header-stats {
        margin-top: 1.5rem;
    }
}

@media (max-width: 575.98px) {
    .jobs-search-card {
        padding: 1rem;
    }

    .jobs-header-illustration {
        display: none;
    }
}
</style>
