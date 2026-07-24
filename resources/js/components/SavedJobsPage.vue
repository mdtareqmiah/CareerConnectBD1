<template>
  <div class="saved-jobs-page">
    <!-- Hero Section -->
    <section class="saved-hero">
      <div class="saved-hero-bg">
        <div class="saved-hero-shape saved-hero-shape-1"></div>
        <div class="saved-hero-shape saved-hero-shape-2"></div>
        <div class="saved-hero-shape saved-hero-shape-3"></div>
        <div class="saved-hero-shape saved-hero-shape-4"></div>
        <div class="saved-hero-grid-overlay"></div>
      </div>

      <div class="saved-hero-inner">
        <div class="saved-hero-content">
          <div class="saved-hero-badge">
            <BookmarkCheck :size="16" :stroke-width="2.5" />
            Career Wishlist
          </div>
          <h1 class="saved-hero-title">
            My <span class="saved-hero-highlight">Saved</span> Jobs
          </h1>
          <p class="saved-hero-subtitle">
            Keep track of opportunities you're interested in and apply whenever you're ready.
          </p>
          <div class="saved-hero-actions">
            <a :href="jobsRoute" class="saved-hero-btn-primary">
              Browse Jobs
              <ArrowRight :size="16" />
            </a>
            <a href="#" class="saved-hero-btn-secondary">
              <Sparkles :size="16" />
              Get Recommendations
            </a>
          </div>
        </div>

        <div class="saved-hero-visual">
          <div class="saved-hero-card saved-hero-card-1">
            <div class="saved-hero-card-icon">
              <BriefcaseBusiness :size="20" :stroke-width="2" />
            </div>
            <div class="saved-hero-card-text">
              <div class="saved-hero-card-title">Senior Developer</div>
              <div class="saved-hero-card-sub">TechCorp Inc.</div>
            </div>
            <div class="saved-hero-card-badge">New</div>
          </div>

          <div class="saved-hero-card saved-hero-card-2">
            <div class="saved-hero-card-icon">
              <Building2 :size="20" :stroke-width="2" />
            </div>
            <div class="saved-hero-card-text">
              <div class="saved-hero-card-title">Product Designer</div>
              <div class="saved-hero-card-sub">DesignStudio</div>
            </div>
            <div class="saved-hero-card-badge saved-hero-card-badge--hot">Hot</div>
          </div>

          <div class="saved-hero-card saved-hero-card-3">
            <div class="saved-hero-card-icon">
              <HeartHandshake :size="20" :stroke-width="2" />
            </div>
            <div class="saved-hero-card-text">
              <div class="saved-hero-card-title">Marketing Lead</div>
              <div class="saved-hero-card-sub">BrandPlus</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Bar -->
    <div class="saved-stats-bar" v-if="!isEmpty">
      <div class="saved-stats-inner">
        <div class="saved-stat">
          <div class="saved-stat-value">{{ total }}</div>
          <div class="saved-stat-label">Saved Jobs</div>
        </div>
        <div class="saved-stat-divider"></div>
        <div class="saved-stat">
          <div class="saved-stat-value">{{ newSetCount }}</div>
          <div class="saved-stat-label">New This Week</div>
        </div>
        <div class="saved-stat-divider"></div>
        <div class="saved-stat">
          <div class="saved-stat-value">{{ expiringCount }}</div>
          <div class="saved-stat-label">Expiring Soon</div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="saved-main">
      <div class="saved-container">
        <!-- Jobs Count Header -->
        <div class="saved-header" v-if="!isEmpty">
          <div class="saved-header-left">
            <h2 class="saved-heading">Your Saved Opportunities</h2>
            <p class="saved-subheading">
              {{ total }} {{ total === 1 ? 'job' : 'jobs' }} saved for later
            </p>
          </div>
          <div class="saved-header-right">
            <div class="saved-search-box">
              <Search :size="16" :stroke-width="2" />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search saved jobs..."
                class="saved-search-input"
              />
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="isEmpty" class="saved-empty">
          <div class="saved-empty-visual">
            <div class="saved-empty-circle saved-empty-circle-1"></div>
            <div class="saved-empty-circle saved-empty-circle-2"></div>
            <div class="saved-empty-icon">
              <Bookmark :size="40" :stroke-width="1.5" />
            </div>
          </div>
          <h3 class="saved-empty-title">You haven't saved any jobs yet.</h3>
          <p class="saved-empty-text">
            Explore opportunities that match your skills and save the ones you love. They'll appear here for easy access.
          </p>
          <a :href="jobsRoute" class="saved-empty-btn">
            Explore Jobs
            <ArrowRight :size="16" />
          </a>
        </div>

        <!-- Cards Grid -->
        <div v-if="!isEmpty" class="saved-grid">
          <SavedJobCard
            v-for="job in filteredJobs"
            :key="job.id"
            :job="job"
            :csrf-token="csrfToken"
          />
        </div>

        <!-- No Results -->
        <div v-if="!isEmpty && filteredJobs.length === 0" class="saved-no-results">
          <Search :size="32" :stroke-width="1.5" />
          <h3 class="saved-no-results-title">No jobs found</h3>
          <p class="saved-no-results-text">Try adjusting your search to find what you're looking for.</p>
        </div>

        <!-- Pagination -->
        <div v-if="!isEmpty && pagination.last_page > 1 && filteredJobs.length > 0" class="saved-pagination">
          <nav class="saved-pagination-nav">
            <template v-for="link in pagination.links" :key="link.label">
              <a
                v-if="link.url"
                v-html="link.label"
                :href="link.url"
                :class="['saved-page-link', { 'saved-page-link--active': link.active }]"
                :aria-current="link.active ? 'page' : undefined"
              ></a>
              <span
                v-else
                v-html="link.label"
                :class="['saved-page-link', 'saved-page-link--disabled']"
              ></span>
            </template>
          </nav>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import SavedJobCard from './SavedJobCard.vue';
import {
  BookmarkCheck,
  Bookmark,
  ArrowRight,
  Sparkles,
  Search,
  BriefcaseBusiness,
  Building2,
  HeartHandshake,
} from '@lucide/vue';

const props = defineProps({
  jobs: { type: Array, default: () => [] },
  jobsRoute: { type: String, default: '/jobs' },
  csrfToken: { type: String, default: '' },
  pagination: {
    type: Object,
    default: () => ({
      total: 0,
      last_page: 1,
      links: [],
    }),
  },
});

const searchQuery = ref('');

const isEmpty = computed(() => props.jobs.length === 0);
const total = computed(() => props.pagination.total || props.jobs.length);

const newSetCount = computed(() => {
  const oneWeekAgo = new Date();
  oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
  return props.jobs.filter(job => {
    const published = job.published_at ? new Date(job.published_at) : null;
    return published && published >= oneWeekAgo;
  }).length;
});

const expiringCount = computed(() => {
  const threeDaysFromNow = new Date();
  threeDaysFromNow.setDate(threeDaysFromNow.getDate() + 3);
  return props.jobs.filter(job => {
    if (!job.deadline) return false;
    const deadline = new Date(job.deadline);
    return deadline <= threeDaysFromNow && deadline >= new Date();
  }).length;
});

const filteredJobs = computed(() => {
  if (!searchQuery.value.trim()) return props.jobs;
  const query = searchQuery.value.toLowerCase();
  return props.jobs.filter(job => {
    const title = (job.title || '').toLowerCase();
    const company = (job.company?.company_name || '').toLowerCase();
    const location = (job.location || '').toLowerCase();
    const type = (job.job_type || '').toLowerCase();
    return title.includes(query) || company.includes(query) || location.includes(query) || type.includes(query);
  });
});
</script>

<style scoped>
.saved-jobs-page {
  min-height: 100vh;
  background: #FAFAF9;
  position: relative;
}

/* Hero Section */
.saved-hero {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 50%, #ffffff 100%);
  padding: 4rem 1.5rem 5rem;
  border-bottom: 1px solid #E7E5E4;
}

.saved-hero-bg {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
}

.saved-hero-shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.4;
  will-change: transform, opacity;
}

.saved-hero-shape-1 {
  width: 500px;
  height: 500px;
  background: #2563eb;
  top: -200px;
  right: -100px;
}

.saved-hero-shape-2 {
  width: 400px;
  height: 400px;
  background: #3b82f6;
  bottom: -150px;
  left: -100px;
}

.saved-hero-shape-3 {
  width: 300px;
  height: 300px;
  background: #6366f1;
  top: 50%;
  left: 30%;
  transform: translate(-50%, -50%);
}

.saved-hero-shape-4 {
  width: 200px;
  height: 200px;
  background: #60a5fa;
  top: 20%;
  right: 20%;
}

.saved-hero-grid-overlay {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(37, 99, 235, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(37, 99, 235, 0.03) 1px, transparent 1px);
  background-size: 60px 60px;
  mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
  -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
}

.saved-hero-inner {
  position: relative;
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 3rem;
}

.saved-hero-content {
  flex: 1;
  min-width: 0;
  z-index: 2;
}

.saved-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.1rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(37, 99, 235, 0.2);
  border-radius: 999px;
  color: #2563eb;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  font-family: 'Inter', sans-serif;
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
}

.saved-hero-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  font-weight: 700;
  color: #1C1917;
  line-height: 1.15;
  margin-bottom: 1rem;
  letter-spacing: -0.03em;
}

.saved-hero-highlight {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.saved-hero-subtitle {
  font-size: 1.1rem;
  color: #78716C;
  line-height: 1.7;
  max-width: 32rem;
  margin-bottom: 2rem;
  font-family: 'Inter', sans-serif;
}

.saved-hero-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.saved-hero-btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.85rem 1.75rem;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  border-radius: 14px;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
  transition: all 0.3s ease;
  font-family: 'Inter', sans-serif;
}

.saved-hero-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
  color: #ffffff;
}

.saved-hero-btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.85rem 1.75rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  color: #2563eb;
  border-radius: 14px;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid rgba(37, 99, 235, 0.2);
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
  transition: all 0.3s ease;
  font-family: 'Inter', sans-serif;
}

.saved-hero-btn-secondary:hover {
  transform: translateY(-2px);
  background: #ffffff;
  border-color: #2563eb;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15);
  color: #2563eb;
}

.saved-hero-visual {
  flex-shrink: 0;
  width: 280px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  z-index: 2;
}

.saved-hero-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(37, 99, 235, 0.12);
  border-radius: 16px;
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
  transition: all 0.3s ease;
  will-change: transform;
}

.saved-hero-card:hover {
  transform: translateX(-8px);
  box-shadow: 0 8px 30px rgba(37, 99, 235, 0.15);
}

.saved-hero-card-1 { }
.saved-hero-card-2 { }
.saved-hero-card-3 { }

.saved-hero-card-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.saved-hero-card-text {
  flex: 1;
  min-width: 0;
}

.saved-hero-card-title {
  font-family: 'Poppins', sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  color: #1C1917;
  margin: 0;
  line-height: 1.3;
}

.saved-hero-card-sub {
  font-size: 0.8rem;
  color: #78716C;
  margin: 0.15rem 0 0;
}

.saved-hero-card-badge {
  padding: 0.25rem 0.6rem;
  border-radius: 999px;
  font-size: 0.7rem;
  font-weight: 600;
  background: rgba(37, 99, 235, 0.1);
  color: #2563eb;
  flex-shrink: 0;
}

.saved-hero-card-badge--hot {
  background: rgba(239, 68, 68, 0.1);
  color: #DC2626;
}

/* Stats Bar */
.saved-stats-bar {
  background: #ffffff;
  border-bottom: 1px solid #E7E5E4;
  padding: 1.25rem 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  position: sticky;
  top: 0;
  z-index: 10;
}

.saved-stats-inner {
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
}

.saved-stat {
  text-align: center;
}

.saved-stat-value {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #2563eb;
  line-height: 1;
}

.saved-stat-label {
  font-size: 0.8rem;
  color: #78716C;
  margin-top: 0.35rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.saved-stat-divider {
  width: 1px;
  height: 40px;
  background: #E7E5E4;
}

/* Main Content */
.saved-main {
  padding: 2.5rem 1.5rem;
}

.saved-container {
  max-width: 1100px;
  margin: 0 auto;
}

.saved-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 2rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.saved-heading {
  font-family: 'Poppins', sans-serif;
  font-size: 1.75rem;
  font-weight: 700;
  color: #1C1917;
  margin: 0 0 0.35rem;
  letter-spacing: -0.02em;
}

.saved-subheading {
  font-size: 0.95rem;
  color: #78716C;
  margin: 0;
}

.saved-search-box {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.65rem 1rem;
  background: #ffffff;
  border: 1px solid #E7E5E4;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
  min-width: 240px;
}

.saved-search-box:focus-within {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.saved-search-box svg {
  color: #94a3b8;
  flex-shrink: 0;
}

.saved-search-input {
  border: 0;
  outline: 0;
  box-shadow: none;
  background: transparent;
  color: #1C1917;
  font-size: 0.9rem;
  font-family: 'Inter', sans-serif;
  width: 100%;
}

.saved-search-input::placeholder {
  color: #94a3b8;
}

/* Empty State */
.saved-empty {
  text-align: center;
  padding: 5rem 2rem;
  background: #ffffff;
  border-radius: 24px;
  border: 1px solid #E7E5E4;
  box-shadow: 0 4px 20px rgba(28, 25, 23, 0.04);
  max-width: 520px;
  margin: 0 auto;
  position: relative;
  overflow: hidden;
}

.saved-empty-visual {
  position: relative;
  display: inline-block;
  margin-bottom: 1.5rem;
}

.saved-empty-circle {
  position: absolute;
  border-radius: 50%;
  background: rgba(37, 99, 235, 0.08);
}

.saved-empty-circle-1 {
  width: 120px;
  height: 120px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: empty-pulse 3s ease-in-out infinite;
}

.saved-empty-circle-2 {
  width: 80px;
  height: 80px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: empty-pulse 3s ease-in-out infinite 0.5s;
}

.saved-empty-icon {
  position: relative;
  z-index: 2;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  border-radius: 20px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  box-shadow: 0 8px 24px rgba(37, 99, 235, 0.25);
}

.saved-empty-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.35rem;
  font-weight: 700;
  color: #1C1917;
  margin-bottom: 0.5rem;
}

.saved-empty-text {
  font-size: 0.95rem;
  color: #78716C;
  line-height: 1.6;
  margin-bottom: 2rem;
  max-width: 28rem;
  margin-left: auto;
  margin-right: auto;
}

.saved-empty-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.85rem 2rem;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  border-radius: 14px;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
  transition: all 0.3s ease;
}

.saved-empty-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
  color: #ffffff;
}

/* No Results */
.saved-no-results {
  text-align: center;
  padding: 3rem 2rem;
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #E7E5E4;
  max-width: 400px;
  margin: 0 auto;
}

.saved-no-results svg {
  color: #94a3b8;
  margin-bottom: 1rem;
}

.saved-no-results-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.1rem;
  font-weight: 600;
  color: #1C1917;
  margin-bottom: 0.35rem;
}

.saved-no-results-text {
  font-size: 0.9rem;
  color: #78716C;
  margin: 0;
}

/* Cards Grid */
.saved-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
  gap: 1.5rem;
}

/* Pagination */
.saved-pagination {
  display: flex;
  justify-content: center;
  padding-top: 3rem;
}

.saved-pagination-nav {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  flex-wrap: wrap;
  justify-content: center;
  list-style: none;
  padding: 0;
  margin: 0;
}

.saved-page-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 44px;
  height: 44px;
  padding: 0 0.85rem;
  border-radius: 12px;
  border: 1px solid #E7E5E4;
  background: #ffffff;
  color: #78716C;
  font-size: 0.9rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.25s ease;
  font-family: 'Inter', sans-serif;
}

.saved-page-link:hover:not(.saved-page-link--disabled) {
  background: #EFF6FF;
  border-color: #2563eb;
  color: #2563eb;
  transform: translateY(-1px);
}

.saved-page-link--active {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  border-color: transparent;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.saved-page-link--disabled {
  opacity: 0.4;
  cursor: not-allowed;
  background: #FAFAF9;
}

/* Footer */
.saved-footer {
  background: #ffffff;
  border-top: 1px solid #E7E5E4;
  padding: 1.5rem;
  min-height: 80px;
}

.saved-footer-inner {
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;
}

.saved-footer-left {
  flex-shrink: 0;
}

.saved-footer-logo {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  text-decoration: none;
}

.saved-footer-mark {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 0.85rem;
  font-family: 'Poppins', sans-serif;
}

.saved-footer-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1rem;
  color: #1C1917;
}

.saved-footer-center {
  flex: 1 1 auto;
  text-align: center;
  min-width: 200px;
}

.saved-footer-tagline {
  font-size: 0.9rem;
  color: #78716C;
  margin: 0;
  font-family: 'Inter', sans-serif;
}

.saved-footer-right {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  flex-wrap: wrap;
  flex-shrink: 0;
}

.saved-footer-link {
  font-size: 0.85rem;
  color: #64748b;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
  font-family: 'Inter', sans-serif;
}

.saved-footer-link:hover {
  color: #2563eb;
}

@keyframes hero-float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

@keyframes hero-pulse {
  0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
  50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.1); }
}

@keyframes card-float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

@keyframes empty-pulse {
  0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
  50% { transform: translate(-50%, -50%) scale(1.15); opacity: 0.6; }
}

@media (max-width: 991.98px) {
  .saved-hero-inner {
    flex-direction: column;
    text-align: center;
  }

  .saved-hero-subtitle {
    max-width: none;
    margin-left: auto;
    margin-right: auto;
  }

  .saved-hero-actions {
    justify-content: center;
  }

  .saved-hero-visual {
    width: 100%;
    max-width: 320px;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
  }

  .saved-hero-card {
    flex: 1 1 200px;
    max-width: 280px;
  }

  .saved-hero-card:hover {
    transform: translateY(-4px);
  }

  .saved-stats-inner {
    gap: 1.25rem;
  }

  .saved-footer-inner {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .saved-footer-center {
    text-align: center;
  }
}

@media (max-width: 575.98px) {
  .saved-hero {
    padding: 2.5rem 1rem 3rem;
  }

  .saved-hero-title {
    font-size: 2rem;
  }

  .saved-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .saved-main {
    padding: 2rem 1rem;
  }

  .saved-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .saved-search-box {
    width: 100%;
    min-width: auto;
  }

  .saved-stats-inner {
    flex-direction: column;
    gap: 1rem;
  }

  .saved-stat-divider {
    width: 60px;
    height: 1px;
  }

  .saved-footer-right {
    justify-content: center;
  }
}
</style>