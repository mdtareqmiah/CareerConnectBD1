<template>
  <article class="saved-card">
    <div class="saved-card-accent"></div>

    <div class="saved-card-inner">
      <header class="saved-card-head">
        <div class="saved-card-logo">
          <img
            v-if="logoUrl"
            :src="logoUrl"
            :alt="companyName + ' logo'"
            class="saved-card-logo-img"
            loading="lazy"
            @error="logoFailed = true"
          >
          <div v-else class="saved-card-logo-ph">
            {{ companyInitials }}
          </div>
        </div>

        <div class="saved-card-headtext">
          <h3 class="saved-card-title" :title="job.title">{{ job.title }}</h3>
          <p class="saved-card-company">{{ companyName }}</p>
          <div class="saved-card-badges">
            <span class="saved-badge saved-badge--type">{{ job.job_type }}</span>
            <span v-if="job.workplace" class="saved-badge" :class="workplaceBadgeClass">{{ workplaceLabel }}</span>
            <span v-if="experienceLabel" class="saved-badge saved-badge--exp">{{ experienceLabel }}</span>
          </div>
        </div>

        <div class="saved-card-bookmark">
          <BookmarkCheck :size="20" :stroke-width="2.5" />
        </div>
      </header>

      <div class="saved-card-meta">
        <span class="saved-meta" v-if="job.location">
          <span class="saved-meta-icon"><MapPin :size="15" /></span>
          {{ job.location }}
        </span>
        <span class="saved-meta saved-meta--salary" v-if="salaryText">
          <span class="saved-meta-icon saved-meta-icon--salary"><Banknote :size="15" /></span>
          {{ salaryText }}
        </span>
      </div>

      <footer class="saved-card-foot">
        <div class="saved-card-times">
          <span class="saved-time" v-if="job.published_at">
            <Clock3 :size="14" />
            {{ postedText }}
          </span>
          <span class="saved-time" v-if="job.deadline">
            <Calendar :size="14" />
            Closes {{ deadlineText }}
          </span>
        </div>

        <div class="saved-card-actions">
          <a :href="job.url" class="saved-act saved-act--view">
            <Eye :size="15" />
            View Job
          </a>
          <a :href="applyUrl" class="saved-act saved-act--apply">
            Apply
            <ArrowRight :size="15" />
          </a>
          <form method="POST" :action="job.remove_url" class="saved-act-form">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit" class="saved-act saved-act--remove">
              <Trash2 :size="15" />
              Remove
            </button>
          </form>
        </div>
      </footer>
    </div>
  </article>
</template>

<script setup>
import { ref, computed } from 'vue';
import {
  BookmarkCheck,
  MapPin,
  Banknote,
  Clock3,
  Calendar,
  Eye,
  ArrowRight,
  Trash2,
} from '@lucide/vue';

const props = defineProps({
  job: { type: Object, required: true },
  csrfToken: { type: String, default: '' },
});

const logoFailed = ref(false);

const logoUrl = computed(() => {
  const raw = props.job.company?.company_logo;
  if (!raw || logoFailed.value) return null;
  if (/^https?:\/\//.test(raw)) return raw;
  if (raw.startsWith('/')) return raw;
  return `/storage/company-logos/${raw}`;
});

const companyName = computed(() => {
  return props.job.company?.company_name || 'Company';
});

const companyInitials = computed(() => {
  const parts = (companyName.value || 'C').trim().split(/\s+/);
  const initials = parts.slice(0, 2).map((p) => p[0]).join('');
  return (initials || 'C').toUpperCase();
});

const workplaceLabel = computed(() => {
  const map = {
    'remote': 'Remote',
    'hybrid': 'Hybrid',
    'on-site': 'On-site',
  };
  return map[props.job.workplace] || props.job.workplace;
});

const workplaceBadgeClass = computed(() => {
  const w = (props.job.workplace || '').toLowerCase();
  if (w === 'remote') return 'saved-badge--remote';
  if (w === 'hybrid') return 'saved-badge--hybrid';
  if (w === 'on-site') return 'saved-badge--onsite';
  return 'saved-badge--default';
});

const experienceLabel = computed(() => {
  const map = {
    'entry': 'Entry Level',
    'mid': 'Mid Level',
    'senior': 'Senior Level',
    'entry-level': 'Entry Level',
    'mid-level': 'Mid Level',
    'senior-level': 'Senior Level',
  };
  return map[props.job.experience_level] || props.job.experience_level || '';
});

const salaryText = computed(() => {
  const min = props.job.salary_min ? Number(props.job.salary_min) : null;
  const max = props.job.salary_max ? Number(props.job.salary_max) : null;
  if (!min && !max) return '';
  const fmt = (v) => v >= 1000 ? `${(v / 1000).toFixed(0)}k` : `${v}`;
  const type = props.job.salary_type ? `${props.job.salary_type} ` : '';
  if (min && max) return `${type}৳${fmt(min)} – ৳${fmt(max)}`;
  if (min) return `${type}৳${fmt(min)}+`;
  return `${type}Up to ৳${fmt(max)}`;
});

const applyUrl = computed(() => {
  if (props.job.apply_url) return props.job.apply_url;
  if (props.job.url) return props.job.url;
  return '/jobs';
});

const postedText = computed(() => {
  if (!props.job.published_at) return 'Recently posted';
  const d = new Date(props.job.published_at);
  const diff = Math.floor((Date.now() - d.getTime()) / 86400000);
  if (diff <= 0) return 'Today';
  if (diff === 1) return 'Yesterday';
  if (diff < 7) return `${diff} days ago`;
  if (diff < 30) return `${Math.floor(diff / 7)} weeks ago`;
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});

const deadlineText = computed(() => {
  if (!props.job.deadline) return '';
  const d = new Date(props.job.deadline);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});
</script>

<style scoped>
.saved-card {
  position: relative;
  background: #ffffff;
  border-radius: 22px;
  border: 1px solid #E7E5E4;
  box-shadow: 0 4px 6px rgba(28, 25, 23, 0.03), 0 10px 30px rgba(28, 25, 23, 0.06);
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  overflow: hidden;
  animation: card-rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.saved-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(37, 99, 235, 0.02) 0%, transparent 50%);
  pointer-events: none;
  z-index: 0;
}

.saved-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12), 0 8px 16px rgba(28, 25, 23, 0.06);
  border-color: #2563eb;
}

.saved-card-accent {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 5px;
  background: linear-gradient(180deg, #2563eb, #3b82f6);
  border-radius: 22px 0 0 22px;
  transition: width 0.3s ease;
}

.saved-card:hover .saved-card-accent {
  width: 6px;
}

.saved-card-inner {
  padding: 1.5rem;
  padding-left: 1.75rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  position: relative;
  z-index: 1;
}

.saved-card-head {
  display: flex;
  align-items: flex-start;
  gap: 0.9rem;
}

.saved-card-logo {
  position: relative;
  flex-shrink: 0;
}

.saved-card-logo-img,
.saved-card-logo-ph {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  object-fit: cover;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}

.saved-card:hover .saved-card-logo-img,
.saved-card:hover .saved-card-logo-ph {
  transform: scale(1.05);
}

.saved-card-logo-img {
  border: 1px solid #E7E5E4;
  box-shadow: 0 2px 8px rgba(28, 25, 23, 0.06);
}

.saved-card-logo-ph {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1.2rem;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.saved-card-headtext {
  flex: 1;
  min-width: 0;
}

.saved-card-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: #1C1917;
  margin: 0;
  line-height: 1.35;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.saved-card-company {
  font-size: 0.88rem;
  font-weight: 500;
  color: #78716C;
  margin-top: 0.25rem;
}

.saved-card-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  margin-top: 0.6rem;
}

.saved-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.3rem 0.7rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.02em;
  line-height: 1.4;
  transition: transform 0.2s ease;
}

.saved-badge:hover {
  transform: translateY(-1px);
}

.saved-badge--type {
  background: rgba(37, 99, 235, 0.1);
  color: #2563eb;
}

.saved-badge--remote {
  background: rgba(37, 99, 235, 0.1);
  color: #2563eb;
}

.saved-badge--hybrid {
  background: rgba(99, 102, 241, 0.1);
  color: #6366f1;
}

.saved-badge--onsite {
  background: rgba(100, 116, 139, 0.1);
  color: #475569;
}

.saved-badge--default {
  background: rgba(100, 116, 139, 0.08);
  color: #64748b;
}

.saved-badge--exp {
  background: rgba(245, 158, 11, 0.12);
  color: #d97706;
}

.saved-card-bookmark {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: rgba(37, 99, 235, 0.1);
  color: #2563eb;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.saved-card:hover .saved-card-bookmark {
  background: #2563eb;
  color: #ffffff;
  transform: scale(1.1);
}

.saved-card-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
}

.saved-meta {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.84rem;
  color: #78716C;
  font-weight: 500;
}

.saved-meta-icon {
  display: inline-flex;
  align-items: center;
  color: #2563eb;
}

.saved-meta--salary {
  color: #78716C;
  font-weight: 600;
}

.saved-meta--salary .saved-meta-icon {
  color: #3b82f6;
}

.saved-card-foot {
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid #F5F5F4;
  display: flex;
  flex-direction: column;
  gap: 0.9rem;
}

.saved-card-times {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
}

.saved-time {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8rem;
  color: #A8A29E;
  font-weight: 500;
}

.saved-time svg {
  color: #78716C;
}

.saved-card-actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  flex-wrap: wrap;
}

.saved-act {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.35rem;
  border-radius: 12px;
  font-size: 0.84rem;
  font-weight: 600;
  padding: 0.55rem 1rem;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.25s ease;
  border: 1px solid transparent;
  white-space: nowrap;
}

.saved-act--view {
  background: #FAFAF9;
  color: #78716C;
  border-color: #E7E5E4;
}

.saved-act--view:hover {
  background: #F5F5F4;
  border-color: #D6D3D1;
  color: #44403C;
  transform: translateY(-1px);
}

.saved-act--apply {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  border: none;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.saved-act--apply:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
  background: linear-gradient(135deg, #1d4ed8, #2563eb);
}

.saved-act--remove {
  background: #ffffff;
  color: #78716C;
  border-color: #E7E5E4;
  font-family: inherit;
}

.saved-act--remove:hover {
  background: #FEF2F2;
  border-color: #FECACA;
  color: #DC2626;
  transform: translateY(-1px);
}

.saved-act-form {
  display: inline-flex;
}

@keyframes card-rise {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 575.98px) {
  .saved-card-inner {
    padding: 1.25rem;
    padding-left: 1.5rem;
  }

  .saved-card-logo-img,
  .saved-card-logo-ph {
    width: 48px;
    height: 48px;
  }

  .saved-card-title {
    font-size: 0.95rem;
    white-space: normal;
  }

  .saved-card-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .saved-act {
    justify-content: center;
  }
}
</style>
