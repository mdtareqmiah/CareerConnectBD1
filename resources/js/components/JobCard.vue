<template>
    <article
        class="job-card"
        :class="{ 'job-card--featured': isFeatured }"
        tabindex="0"
        @keydown.enter="$emit('quickview', job)"
    >
        <!-- Top accent strip -->
        <div v-if="isFeatured" class="job-card-accent"></div>
        <div v-else class="job-card-accent job-card-accent--default"></div>

        <div class="job-card-inner">
            <!-- Header -->
            <header class="job-card-head">
                <div class="job-card-logo">
                    <img
                        v-if="logoUrl"
                        :src="logoUrl"
                        :alt="companyName + ' logo'"
                        class="job-card-logo-img"
                        loading="lazy"
                        @error="logoFailed = true"
                    >
                    <span v-else class="job-card-logo-ph">{{ companyInitials }}</span>

                    <span v-if="job.company?.verified" class="job-card-verified" title="Verified company">
                        <BadgeCheck :size="14" stroke-width="2.5" />
                    </span>
                </div>

                <div class="job-card-headtext">
                    <div class="job-card-titlerow">
                        <h3 class="job-card-title" :title="job.title">{{ job.title }}</h3>
                        <span v-if="isUrgent" class="job-badge job-badge--urgent">
                            <Flame :size="12" />Urgent
                        </span>
                    </div>
                    <div class="job-card-company">
                        <span class="job-card-companyname">{{ companyName }}</span>
                        <span class="job-card-rating" v-if="companyRating">
                            <Star :size="12" fill="#f59e0b" stroke="#f59e0b" />
                            {{ companyRating }}
                        </span>
                    </div>
                    <div class="job-card-badges">
                        <span v-if="isFeatured" class="job-badge job-badge--featured">
                            <Sparkles :size="12" />Featured
                        </span>
                        <span class="job-badge" :class="jobTypeBadgeClass">{{ job.job_type }}</span>
                        <span v-if="employmentLabel" class="job-badge" :class="employmentBadgeClass">
                            {{ employmentLabel }}
                        </span>
                        <span v-if="isRemote" class="job-badge job-badge--remote">
                            <Globe :size="12" />Remote
                        </span>
                        <span v-else-if="job.workplace === 'hybrid'" class="job-badge job-badge--hybrid">
                            <Laptop :size="12" />Hybrid
                        </span>
                        <span v-else-if="job.workplace === 'on-site'" class="job-badge job-badge--onsite">
                            <Building2 :size="12" />On-site
                        </span>
                    </div>
                </div>

                <button
                    type="button"
                    class="job-card-save"
                    :class="{ 'is-saved': isSaved }"
                    :aria-pressed="isSaved"
                    :aria-label="isSaved ? 'Remove bookmark' : 'Bookmark job'"
                    @click="toggleSave"
                >
                    <Bookmark :size="18" :fill="isSaved ? '#f59e0b' : 'none'" :stroke="isSaved ? '#f59e0b' : 'currentColor'" />
                </button>
            </header>

            <!-- Meta -->
            <div class="job-card-meta">
                <span class="job-meta">
                    <span class="job-meta-icon job-meta-icon--location"><MapPin :size="15" /></span>
                    {{ job.location || 'Bangladesh' }}
                </span>
                <span class="job-meta" v-if="experienceLabel">
                    <span class="job-meta-icon job-meta-icon--experience"><Briefcase :size="15" /></span>
                    {{ experienceLabel }}
                </span>
                <span class="job-meta job-meta--salary">
                    <span class="job-meta-icon job-meta-icon--salary"><Banknote :size="15" /></span>
                    {{ salaryText }}
                </span>
            </div>

            <p v-if="job.description" class="job-card-desc">{{ excerpt }}</p>

            <!-- Footer -->
            <footer class="job-card-foot">
                <div class="job-card-times">
                    <span class="job-time" :class="{ 'job-time--soon': deadlineSoon }">
                        <CalendarClock :size="14" />{{ deadlineText }}
                    </span>
                    <span class="job-time">
                        <Clock :size="14" />{{ postedText }}
                    </span>
                </div>

                <div class="job-card-actions">
                    <button
                        type="button"
                        class="job-act job-act--ghost"
                        @click="$emit('quickview', job)"
                    >
                        <Eye :size="15" />Quick View
                    </button>
                    <a :href="`/jobs/${job.id}`" class="job-act job-act--details">Details</a>
                    <button
                        type="button"
                        class="job-act job-act--apply"
                        @click="$emit('apply', { jobId: job.id })"
                    >
                        Apply <ArrowRight :size="15" />
                    </button>
                </div>
            </footer>
        </div>
    </article>
</template>

<script>
import {
    Bookmark,
    BadgeCheck,
    Star,
    Globe,
    Laptop,
    Building2,
    MapPin,
    Briefcase,
    Banknote,
    CalendarClock,
    Clock,
    Eye,
    ArrowRight,
    Sparkles,
    Flame,
} from '@lucide/vue';

export default {
    name: 'JobCard',
    components: {
        Bookmark,
        BadgeCheck,
        Star,
        Globe,
        Laptop,
        Building2,
        MapPin,
        Briefcase,
        Banknote,
        CalendarClock,
        Clock,
        Eye,
        ArrowRight,
        Sparkles,
        Flame,
    },
    props: {
        job: { type: Object, required: true },
        saved: { type: Boolean, default: false },
    },
    emits: ['save', 'apply', 'quickview'],
    data() {
        return {
            isSaved: this.saved,
            logoFailed: false,
        };
    },
    computed: {
        logoUrl() {
            const raw = this.job.company?.company_logo;
            if (!raw || this.logoFailed) return null;
            if (/^https?:\/\//.test(raw)) return raw;
            if (raw.startsWith('/')) return raw;
            return `/storage/company-logos/${raw}`;
        },
        companyName() {
            return this.job.company?.company_name || 'Company';
        },
        companyInitials() {
            const parts = (this.companyName || 'C').trim().split(/\s+/);
            const initials = parts.slice(0, 2).map((p) => p[0]).join('');
            return (initials || 'C').toUpperCase();
        },
        companyRating() {
            const r = this.job.company?.rating;
            return r ? Number(r).toFixed(1) : null;
        },
        isFeatured() {
            return this.job.featured === true || this.job.is_featured === true;
        },
        isUrgent() {
            if (this.job.urgent === true || this.job.is_urgent === true) return true;
            const d = this.job.deadline ? new Date(this.job.deadline) : null;
            if (!d) return false;
            return (d - new Date()) / (1000 * 60 * 60 * 24) <= 3;
        },
        isRemote() {
            return this.job.workplace === 'remote' || /remote/i.test(this.job.job_type || '');
        },
        jobTypeBadgeClass() {
            const type = (this.job.job_type || '').toLowerCase();
            if (/\bpart[\s-]?time\b/.test(type)) return 'job-badge--parttime';
            if (/\bfull[\s-]?time\b/.test(type)) return 'job-badge--fulltime';
            if (/\bcontract\b/.test(type)) return 'job-badge--contract';
            if (/\bintern\b/.test(type)) return 'job-badge--intern';
            if (/\bfreelance\b/.test(type)) return 'job-badge--freelance';
            return 'job-badge--type';
        },
        employmentBadgeClass() {
            const status = (this.employmentLabel || '').toLowerCase();
            if (/\bpart[\s-]?time\b/.test(status)) return 'job-badge--parttime';
            if (/\b(must|mast)[\s-]?be[\s-]?pro\b/.test(status)) return 'job-badge--mustbepro';
            return 'job-badge--status';
        },
        employmentLabel() {
            return this.job.employment_status || '';
        },
        experienceLabel() {
            const map = {
                'entry': 'Entry Level',
                'mid': 'Mid Level',
                'senior': 'Senior Level',
                'entry-level': 'Entry Level',
                'mid-level': 'Mid Level',
                'senior-level': 'Senior Level',
            };
            return map[this.job.experience_level] || this.job.experience_level || '';
        },
        salaryText() {
            const min = this.job.salary_min ? Number(this.job.salary_min) : null;
            const max = this.job.salary_max ? Number(this.job.salary_max) : null;
            if (!min && !max) return 'Negotiable';
            const fmt = (v) => v >= 1000 ? `${(v / 1000).toFixed(0)}k` : `${v}`;
            const type = this.job.salary_type ? `${this.job.salary_type} ` : '';
            if (min && max) return `৳${fmt(min)}–${fmt(max)}`;
            if (min) return `৳${fmt(min)}+`;
            return `Up to ৳${fmt(max)}`;
        },
        excerpt() {
            const text = (this.job.description || '').replace(/<[^>]+>/g, '').trim();
            return text.length > 140 ? text.slice(0, 137) + '…' : text;
        },
        deadlineText() {
            if (!this.job.deadline) return 'No deadline';
            const d = new Date(this.job.deadline);
            return `Closes ${d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}`;
        },
        deadlineSoon() {
            const d = this.job.deadline ? new Date(this.job.deadline) : null;
            if (!d) return false;
            return (d - new Date()) / (1000 * 60 * 60 * 24) <= 3;
        },
        postedText() {
            const d = this.job.published_at ? new Date(this.job.published_at) : null;
            if (!d) return 'Recently';
            const diff = Math.floor((Date.now() - d) / 86400000);
            if (diff <= 0) return 'Today';
            if (diff === 1) return 'Yesterday';
            if (diff < 7) return `${diff}d ago`;
            if (diff < 30) return `${Math.floor(diff / 7)}w ago`;
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },
    },
    methods: {
        toggleSave() {
            this.isSaved = !this.isSaved;
            this.$emit('save', { jobId: this.job.id, saved: this.isSaved });
        },
    },
};
</script>

<style scoped>
.job-card {
    position: relative;
    height: 100%;
    border-radius: 1.4rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px rgba(15, 23, 42, 0.04), 0 10px 30px rgba(15, 23, 42, 0.08);
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.4s ease, border-color 0.4s ease;
    outline: none;
    overflow: hidden;
    animation: card-rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.job-card:hover,
.job-card:focus-visible {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.18), 0 8px 16px rgba(15, 23, 42, 0.08);
    border-color: #2563eb;
}

.job-card:focus-visible {
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.4), 0 20px 40px rgba(37, 99, 235, 0.18);
}

.job-card--featured {
    border-color: #f59e0b;
    box-shadow: 0 4px 6px rgba(15, 23, 42, 0.04), 0 10px 30px rgba(245, 158, 11, 0.15);
}

.job-card--featured:hover {
    box-shadow: 0 20px 40px rgba(245, 158, 11, 0.25), 0 8px 16px rgba(15, 23, 42, 0.08);
}

.job-card-accent {
    height: 5px;
    background: linear-gradient(90deg, #2563eb, #3b82f6, #6366f1);
    position: relative;
    overflow: hidden;
}

.job-card-accent::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

.job-card-accent--default {
    background: linear-gradient(90deg, #2563eb, #3b82f6);
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.job-card-inner {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    height: 100%;
    position: relative;
}

.job-card-inner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(180deg, rgba(37, 99, 235, 0.02) 0%, transparent 100%);
    pointer-events: none;
    border-radius: 0 0 1rem 1rem;
}

.job-card-head {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
}

.job-card-logo {
    position: relative;
    flex-shrink: 0;
}

.job-card-logo-img,
.job-card-logo-ph {
    width: 72px;
    height: 72px;
    border-radius: 1.1rem;
    object-fit: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.job-card:hover .job-card-logo-img,
.job-card:hover .job-card-logo-ph {
    transform: scale(1.05);
}

.job-card-logo-img {
    border: 2px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}

.job-card-logo-ph {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.5rem;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.28);
}

.job-card-verified {
    position: absolute;
    bottom: -4px;
    right: -4px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
    animation: pop-in 0.3s ease;
}

@keyframes pop-in {
    from { transform: scale(0); }
    to { transform: scale(1); }
}

.job-card-headtext {
    flex: 1;
    min-width: 0;
}

.job-card-titlerow {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.job-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #000000;
    margin: 0;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.job-card-company {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    margin-top: 0.3rem;
}

.job-card-companyname {
    font-size: 0.9rem;
    font-weight: 500;
    color: #000000;
}

.job-card-rating {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #f59e0b;
}

.job-card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-top: 0.6rem;
}

.job-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    line-height: 1.4;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.job-badge:hover {
    transform: translateY(-1px);
}

.job-badge--type {
    background: rgba(37, 99, 235, 0.1);
    color: #2563eb;
}
.job-badge--parttime {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
}
.job-badge--fulltime {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
}
.job-badge--contract {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
}
.job-badge--intern {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: #fff;
    box-shadow: 0 2px 6px rgba(139, 92, 246, 0.3);
}
.job-badge--freelance {
    background: linear-gradient(135deg, #ec4899, #db2777);
    color: #fff;
    box-shadow: 0 2px 6px rgba(236, 72, 153, 0.3);
}
.job-badge--status {
    background: rgba(100, 116, 139, 0.1);
    color: #475569;
}
.job-badge--mustbepro {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
}
.job-badge--remote {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}
.job-badge--hybrid {
    background: rgba(139, 92, 246, 0.1);
    color: #7c3aed;
}
.job-badge--onsite {
    background: rgba(100, 116, 139, 0.1);
    color: #475569;
}
.job-badge--featured {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    box-shadow: 0 3px 8px rgba(245, 158, 11, 0.35);
}
.job-badge--urgent {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 3px 8px rgba(239, 68, 68, 0.35);
    animation: pulse-soft 2s ease-in-out infinite;
}

.job-card-save {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 0.8rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
}

.job-card-save:hover {
    color: #f59e0b;
    border-color: #f59e0b;
    background: rgba(245, 158, 11, 0.08);
    transform: scale(1.05);
}

.job-card-save.is-saved {
    color: #f59e0b;
    border-color: #f59e0b;
}

.job-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem 1rem;
}

.job-meta {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.83rem;
    color: #64748b;
    font-weight: 500;
}

.job-meta-icon {
    display: inline-flex;
    align-items: center;
    color: #6366f1;
}

.job-meta-icon--location {
    color: #ef4444;
}

.job-meta-icon--experience {
    color: #6F4E37;
}

.job-meta--salary {
    color: #f59e0b;
    font-weight: 600;
}

.job-meta--salary .job-meta-icon {
    color: #f59e0b;
}

.job-card-desc {
    margin: 0;
    font-size: 0.87rem;
    line-height: 1.6;
    color: #475569;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-line-orient: vertical;
    overflow: hidden;
    background: #f0fdf4;
    padding: 0.75rem;
    border-radius: 0.75rem;
    border-left: 3px solid #10b981;
    transition: background 0.3s ease;
}

.job-card:hover .job-card-desc {
    background: #ecfdf5;
}

.job-card-foot {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
}

.job-card-times {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem 1rem;
}

.job-time {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    color: #94a3b8;
}

.job-time--soon {
    color: #dc2626;
    font-weight: 600;
}

.job-card-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.job-act {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    border-radius: 0.75rem;
    font-size: 0.84rem;
    font-weight: 600;
    padding: 0.55rem 0.85rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s ease;
    border: 1px solid transparent;
}

.job-act--ghost {
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
    border-color: rgba(37, 99, 235, 0.2);
}

.job-act--ghost:hover {
    background: rgba(37, 99, 235, 0.15);
    border-color: #2563eb;
    transform: translateY(-1px);
}

.job-act--details {
    color: #475569;
    border-color: #e2e8f0;
    background: #fff;
}

.job-act--details:hover {
    border-color: #2563eb;
    color: #2563eb;
    background: rgba(37, 99, 235, 0.04);
    transform: translateY(-1px);
}

.job-act--apply {
    margin-left: auto;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    border: none;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.job-act--apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
}

.job-act:focus-visible {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.4);
}

@keyframes card-rise {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse-soft {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.04); }
}

@media (max-width: 575.98px) {
    .job-card-inner { padding: 1.15rem; }
    .job-card-logo-img, .job-card-logo-ph { width: 56px; height: 56px; }
    .job-card-title { font-size: 1rem; white-space: normal; }
    .job-act--ghost { display: none; }
}
</style>
