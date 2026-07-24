<template>
    <div class="jsd">
        <!-- WELCOME BANNER -->
        <section class="jsd-welcome">
            <div class="jsd-welcome-bg" aria-hidden="true">
                <div class="jsd-welcome-glow"></div>
                <div class="jsd-shape jsd-shape-1"></div>
                <div class="jsd-shape jsd-shape-2"></div>
                <div class="jsd-shape jsd-shape-3"></div>
            </div>

            <!-- left -->
            <div class="jsd-welcome-main">
                <span class="jsd-pill"><Sparkles :size="14" /> Career Dashboard</span>
                <h1 class="jsd-welcome-title">
                    Welcome back, <span class="jsd-grad">{{ firstName }}</span>
                </h1>
                <p class="jsd-welcome-sub">{{ motivationalText }}</p>
                <div class="jsd-welcome-date">
                    <CalendarDays :size="16" /> {{ todayLabel }}
                </div>

                <div class="jsd-welcome-actions" v-if="profile">
                    <a :href="routes.profile" class="jsd-btn jsd-btn-primary"><UserCog :size="16" /> Manage Profile</a>
                    <a :href="routes.jobs" class="jsd-btn jsd-btn-ghost"><BriefcaseBusiness :size="16" /> Browse Jobs</a>
                </div>
                <div class="jsd-welcome-actions" v-else>
                    <a :href="routes.profile" class="jsd-btn jsd-btn-primary"><UserPlus :size="16" /> Complete Your Profile</a>
                </div>
            </div>

            <!-- right: completion ring -->
            <div class="jsd-welcome-card">
                <div class="jsd-ring" :style="ringStyle">
                    <div class="jsd-ring-inner">
                        <span class="jsd-ring-pct">{{ completion.percentage }}<small>%</small></span>
                        <span class="jsd-ring-label">Profile Complete</span>
                    </div>
                </div>
                <div class="jsd-welcome-card-meta">
                    <span class="jsd-status" :class="completion.percentage >= 100 ? 'is-done' : ''">
                        <CircleCheck :size="15" /> {{ profileStatus }}
                    </span>
                    <span class="jsd-avail"><span class="jsd-avail-dot"></span>{{ availabilityStatus }}</span>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section class="jsd-stats">
            <article
                v-for="stat in statCards"
                :key="stat.key"
                class="jsd-stat"
                :class="'jsd-stat--' + stat.key"
            >
                <div class="jsd-stat-ico"><component :is="stat.icon" :size="22" /></div>
                <div class="jsd-stat-body">
                    <div class="jsd-stat-value">{{ stat.value }}</div>
                    <div class="jsd-stat-title">{{ stat.title }}</div>
                </div>
                <span class="jsd-stat-badge" :class="stat.badgeClass">{{ stat.status }}</span>
            </article>
        </section>

        <!-- MAIN GRID -->
        <div class="jsd-grid">
            <!-- LEFT COLUMN -->
            <div class="jsd-col-main">
                <!-- PROFILE COMPLETION -->
                <section class="jsd-card jsd-completion" v-if="profile">
                    <div class="jsd-card-head">
                        <div class="jsd-card-ico jsd-card-ico--blue"><Target :size="18" /></div>
                        <div>
                            <h2 class="jsd-card-h">Profile Completion</h2>
                            <p class="jsd-card-sub">Finish these sections to boost your visibility.</p>
                        </div>
                    </div>

                    <div class="jsd-completion-bar">
                        <span :style="{ width: completion.percentage + '%' }"></span>
                    </div>
                    <div class="jsd-completion-row">
                        <span class="jsd-muted">Completion</span>
                        <span class="jsd-fw">{{ completion.percentage }}%</span>
                    </div>

                    <div class="jsd-chips">
                        <span v-for="s in completion.completed_sections" :key="'c'+s" class="jsd-chip jsd-chip--ok">
                            <CircleCheck :size="13" /> {{ humanize(s) }}
                        </span>
                        <span v-for="s in completion.missing_sections" :key="'m'+s" class="jsd-chip jsd-chip--miss">
                            {{ humanize(s) }}
                        </span>
                    </div>

                    <a :href="routes.profile" class="jsd-btn jsd-btn-primary jsd-btn-block mt-3">
                        <UserCog :size="16" /> Complete Profile
                    </a>
                </section>

                <!-- RECENT APPLICATIONS (recent activity) -->
                <section class="jsd-card">
                    <div class="jsd-card-head">
                        <div class="jsd-card-ico jsd-card-ico--violet"><History :size="18" /></div>
                        <div>
                            <h2 class="jsd-card-h">Recent Activity</h2>
                            <p class="jsd-card-sub">Your latest profile updates.</p>
                        </div>
                    </div>

                    <ul v-if="recentActivities.length" class="jsd-activity">
                        <li v-for="(a, i) in recentActivities" :key="i" class="jsd-activity-item">
                            <span class="jsd-activity-ico"><component :is="activityIcon(a.type)" :size="16" /></span>
                            <div class="jsd-activity-body">
                                <div class="jsd-activity-title">{{ a.title }}</div>
                                <div class="jsd-activity-sub">{{ a.type }} · {{ a.description }}</div>
                            </div>
                            <span class="jsd-activity-time">{{ relativeTime(a.created_at) }}</span>
                        </li>
                    </ul>
                    <div v-else class="jsd-empty">
                        <div class="jsd-empty-ico"><Sparkles :size="26" /></div>
                        <p class="mb-0 jsd-muted">No recent activity yet.</p>
                    </div>
                </section>

                <!-- RECOMMENDED JOBS -->
                <section class="jsd-card">
                    <div class="jsd-card-head jsd-card-head--row">
                        <div class="d-flex align-items-center gap-2">
                            <div class="jsd-card-ico jsd-card-ico--emerald"><TrendingUp :size="18" /></div>
                            <div>
                                <h2 class="jsd-card-h">Recommended Jobs</h2>
                                <p class="jsd-card-sub">Matched to your profile.</p>
                            </div>
                        </div>
                        <a :href="routes.recommendedJobs" class="jsd-btn jsd-btn-ghost jsd-btn-sm">Explore All</a>
                    </div>

                    <div class="jsd-rec-grid">
                        <article v-for="rec in recommendedJobs" :key="rec.job.id" class="jsd-rec">
                            <div class="jsd-rec-top">
                                <div class="jsd-rec-logo">
                                    <img v-if="jobLogo(rec.job)" :src="jobLogo(rec.job)" :alt="companyName(rec.job) + ' logo'" @error="logoFail">
                                    <span v-else class="jsd-rec-logo-ph">{{ initials(companyName(rec.job)) }}</span>
                                </div>
                                <div class="jsd-rec-headtext">
                                    <div class="jsd-rec-title">{{ rec.job.title }}</div>
                                    <div class="jsd-rec-company">{{ companyName(rec.job) }}</div>
                                </div>
                                <span class="jsd-rec-level">{{ rec.level }}</span>
                            </div>

                            <div class="jsd-rec-meta">
                                <span><MapPin :size="14" /> {{ rec.job.location }}</span>
                                <span><BriefcaseBusiness :size="14" /> {{ rec.job.job_type }}</span>
                                <span v-if="rec.job.experience_level"><Clock3 :size="14" /> {{ rec.job.experience_level }}</span>
                            </div>

                            <p class="jsd-rec-reason">{{ rec.reason }}</p>

                            <div class="jsd-rec-foot">
                                <a :href="jobUrl(rec.job)" class="jsd-btn jsd-btn-primary jsd-btn-sm">View Job</a>
                                <span class="jsd-rec-score">Match {{ rec.score }}%</span>
                            </div>
                        </article>
                    </div>

                    <div v-if="!recommendedJobs.length" class="jsd-empty">
                        <div class="jsd-empty-ico"><Search :size="26" /></div>
                        <p class="mb-0 jsd-muted">No recommended jobs yet. Update your profile to get tailored suggestions.</p>
                    </div>
                </section>

                <!-- HIGHLIGHTS -->
                <section class="jsd-card" v-if="profile">
                    <div class="jsd-card-head">
                        <div class="jsd-card-ico jsd-card-ico--amber"><Star :size="18" /></div>
                        <div>
                            <h2 class="jsd-card-h">Your Highlights</h2>
                            <p class="jsd-card-sub">A snapshot of what you've added.</p>
                        </div>
                    </div>
                    <div class="jsd-highlight-grid">
                        <div v-for="h in highlightCards" :key="h.key" class="jsd-highlight">
                            <div class="jsd-highlight-ico"><component :is="h.icon" :size="20" /></div>
                            <div class="jsd-highlight-title">{{ h.title }}</div>
                            <div class="jsd-highlight-sub">{{ h.sub }}</div>
                            <a v-if="h.action" :href="h.action" class="jsd-highlight-link">{{ h.link }}</a>
                        </div>
                    </div>
                </section>
            </div>

            <!-- RIGHT SIDEBAR -->
            <aside class="jsd-col-side">
                <div class="jsd-side-sticky">
                    <!-- PROFILE SUMMARY -->
                    <section class="jsd-card jsd-profile">
                        <div class="jsd-profile-cover"></div>
                        <div class="jsd-profile-body">
                            <img :src="profilePhoto" :alt="fullName" class="jsd-profile-photo">
                            <div class="jsd-profile-name">{{ fullName }}</div>
                            <div class="jsd-profile-title">{{ professionalTitle }}</div>
                            <div class="jsd-profile-loc" v-if="location"><MapPin :size="14" /> {{ location }}</div>

                            <div class="jsd-profile-facts">
                                <div class="jsd-fact"><Mail :size="14" /><span class="jsd-fact-val">{{ user.email }}</span></div>
                                <div class="jsd-fact" v-if="profile && profile.phone"><Phone :size="14" /><span class="jsd-fact-val">{{ profile.phone }}</span></div>
                                <div class="jsd-fact" v-if="experienceYears !== null"><Briefcase :size="14" /><span class="jsd-fact-val">{{ experienceYears }} yrs experience</span></div>
                                <div class="jsd-fact" v-if="educationLevel"><GraduationCap :size="14" /><span class="jsd-fact-val">{{ educationLevel }}</span></div>
                            </div>

                            <div class="jsd-profile-actions">
                                <a :href="routes.profile" class="jsd-btn jsd-btn-outline jsd-btn-sm jsd-btn-block"><UserCog :size="15" /> Edit Profile</a>
                                <a v-if="defaultResume" :href="downloadResumeUrl" class="jsd-btn jsd-btn-ghost jsd-btn-sm jsd-btn-block"><Download :size="15" /> Download Resume</a>
                                <a v-else :href="routes.resumes" class="jsd-btn jsd-btn-ghost jsd-btn-sm jsd-btn-block"><FileText :size="15" /> Add Resume</a>
                            </div>
                        </div>
                    </section>

                    <!-- PROFILE COMPLETION SIDEBAR -->
                    <section class="jsd-card jsd-side-completion" v-if="profile">
                        <div class="jsd-side-completion-head">
                            <span class="jsd-side-completion-ring" :style="sideRingStyle">
                                <span>{{ completion.percentage }}%</span>
                            </span>
                            <div>
                                <div class="jsd-fw">Profile Completion</div>
                                <div class="jsd-muted small">{{ completion.missing_sections.length }} to go</div>
                            </div>
                        </div>
                        <div class="jsd-side-completion-list">
                            <div v-for="s in completion.missing_sections" :key="'sm'+s" class="jsd-side-completion-item">
                                <CircleDashed :size="14" /> {{ humanize(s) }}
                            </div>
                            <div v-if="!completion.missing_sections.length" class="jsd-side-completion-done">
                                <CircleCheck :size="14" /> All set! Profile complete.
                            </div>
                        </div>
                    </section>

                    <!-- QUICK ACTIONS -->
                    <section class="jsd-card">
                        <h3 class="jsd-side-h">Quick Actions</h3>
                        <div class="jsd-qa">
                            <a v-for="q in quickActions" :key="q.key" :href="q.href" class="jsd-qa-item">
                                <span class="jsd-qa-ico" :class="'jsd-qa-ico--' + q.key"><component :is="q.icon" :size="18" /></span>
                                <span class="jsd-qa-label">{{ q.label }}</span>
                            </a>
                        </div>
                    </section>

                    <!-- RESUME STATUS -->
                    <section class="jsd-card">
                        <h3 class="jsd-side-h">Resume Status</h3>
                        <div class="jsd-resume">
                            <div class="jsd-resume-ico"><FileText :size="20" /></div>
                            <div class="jsd-resume-body">
                                <div class="jsd-fw" v-if="defaultResume">{{ defaultResume.title || 'Default Resume' }}</div>
                                <div class="jsd-fw" v-else-if="resumeCount">Resume uploaded</div>
                                <div class="jsd-fw" v-else>No resume yet</div>
                                <div class="jsd-muted small">
                                    {{ resumeCount }} resume{{ resumeCount === 1 ? '' : 's' }}
                                    <template v-if="defaultResume"> · Default set</template>
                                </div>
                            </div>
                            <a :href="routes.resumes" class="jsd-resume-go"><ArrowRight :size="16" /></a>
                        </div>
                    </section>

                    <!-- SKILLS -->
                    <section class="jsd-card" v-if="skills.length">
                        <h3 class="jsd-side-h">Skills</h3>
                        <div class="jsd-skill-chips">
                            <span v-for="(s, i) in skills" :key="i" class="jsd-skill-chip" :class="'jsd-skill-chip--' + (i % 4)">
                                {{ s.skill_name }}
                            </span>
                        </div>
                    </section>

                    <!-- UPCOMING / NOTES -->
                    <section class="jsd-card">
                        <h3 class="jsd-side-h">Upcoming & Tips</h3>
                        <div class="jsd-tip">
                            <Sparkles :size="16" />
                            <span>Keep your profile above 80% to appear in more employer searches.</span>
                        </div>
                    </section>
                </div>
            </aside>
        </div>
    </div>
</template>

<script>
import {
    Sparkles, CalendarDays, UserCog, BriefcaseBusiness, UserPlus, CircleCheck, Target,
    History, TrendingUp, MapPin, Clock3, Star, Search, Mail, Phone, Briefcase, GraduationCap,
    Download, FileText, CircleDashed, ArrowRight, User, Bookmark, Settings, Send, Award,
    MessageSquare, BadgeCheck, Building2, Users, SquarePen,
} from '@lucide/vue';

function humanize(str) {
    return String(str || '').replace(/[_-]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
}

function initials(name) {
    const parts = (name || 'C').trim().split(/\s+/);
    return (parts.slice(0, 2).map((p) => p[0]).join('') || 'C').toUpperCase();
}

function jobLogoUrl(job) {
    const raw = job?.company?.company_logo;
    if (!raw) return null;
    if (/^https?:\/\//.test(raw)) return raw;
    if (raw.startsWith('/')) return raw;
    return `/storage/company-logos/${raw}`;
}

export default {
    name: 'JobSeekerDashboard',
    components: {
        Sparkles, CalendarDays, UserCog, BriefcaseBusiness, UserPlus, CircleCheck, Target,
        History, TrendingUp, MapPin, Clock3, Star, Search, Mail, Phone, Briefcase, GraduationCap,
        Download, FileText, CircleDashed, ArrowRight, User, Bookmark, Settings, Send, Award,
        MessageSquare, BadgeCheck, Building2, Users, SquarePen,
    },
    props: {
        user: { type: Object, default: () => ({}) },
        profile: { type: Object, default: null },
        completion: { type: Object, default: () => ({ percentage: 0, completed_sections: [], missing_sections: [] }) },
        completionDetails: { type: Object, default: null },
        educationCount: { type: Number, default: 0 },
        experienceCount: { type: Number, default: 0 },
        skillsCount: { type: Number, default: 0 },
        resumeCount: { type: Number, default: 0 },
        defaultResume: { type: Object, default: null },
        profileStatus: { type: String, default: 'Incomplete' },
        availabilityStatus: { type: String, default: 'Not Available' },
        recentActivities: { type: Array, default: () => [] },
        educations: { type: Array, default: () => [] },
        experiences: { type: Array, default: () => [] },
        skills: { type: Array, default: () => [] },
        resumes: { type: Array, default: () => [] },
        recommendedJobs: { type: Array, default: () => [] },
        routes: { type: Object, default: () => ({}) },
        todayLabel: { type: String, default: '' },
    },
    data() {
        return { logoFailed: false };
    },
    computed: {
        completionData() {
            return this.completionDetails || this.completion;
        },
        firstName() {
            return this.profile?.first_name || (this.user?.name ? this.user.name.split(' ')[0] : 'there');
        },
        fullName() {
            if (this.profile?.first_name || this.profile?.last_name) {
                return `${this.profile.first_name || ''} ${this.profile.last_name || ''}`.trim();
            }
            return this.user?.name || 'Job Seeker';
        },
        professionalTitle() {
            return this.profile?.professional_title || this.profile?.current_job_title || 'Professional';
        },
        location() {
            const p = this.profile;
            if (!p) return '';
            return [p.city, p.country].filter(Boolean).join(', ') || p.preferred_location || '';
        },
        experienceYears() {
            return this.profile?.years_of_experience ?? null;
        },
        educationLevel() {
            const top = [...this.educations].sort((a, b) => (b.id || 0) - (a.id || 0))[0];
            return top?.degree || '';
        },
        profilePhoto() {
            return this.profile?.profile_photo_url || '/images/default-avatar.svg';
        },
        downloadResumeUrl() {
            return this.routes.resumeDownload || this.routes.resumes;
        },
        motivationalText() {
            const pct = this.completionData.percentage || 0;
            if (!this.profile) return 'Let\'s build your profile and unlock tailored job matches.';
            if (pct >= 100) return 'Your profile is complete — you\'re ready to land your next role!';
            if (pct >= 75) return 'Almost there! A few more steps and employers will find you faster.';
            if (pct >= 40) return 'Great start. Keep going to boost your matching score.';
            return 'Welcome aboard! Complete your profile to get the best recommendations.';
        },
        ringStyle() {
            const pct = this.completionData.percentage || 0;
            return {
                background: `conic-gradient(#2563eb ${pct * 3.6}deg, rgba(37,99,235,0.12) 0deg)`,
            };
        },
        sideRingStyle() {
            const pct = this.completionData.percentage || 0;
            return {
                background: `conic-gradient(#10b981 ${pct * 3.6}deg, rgba(16,185,129,0.14) 0deg)`,
            };
        },
        statCards() {
            const c = this.completionData;
            const pct = c.percentage || 0;
            return [
                { key: 'completion', icon: 'Target', title: 'Profile Completion', value: pct + '%', status: pct >= 100 ? 'Complete' : 'In progress', badgeClass: pct >= 100 ? 'is-ok' : 'is-warn' },
                { key: 'education', icon: 'GraduationCap', title: 'Education', value: this.educationCount, status: this.educationCount ? 'Added' : 'Pending', badgeClass: this.educationCount ? 'is-ok' : 'is-miss' },
                { key: 'experience', icon: 'Briefcase', title: 'Experience', value: this.experienceCount, status: this.experienceCount ? 'Added' : 'Pending', badgeClass: this.experienceCount ? 'is-ok' : 'is-miss' },
                { key: 'skills', icon: 'Award', title: 'Skills', value: this.skillsCount, status: this.skillsCount ? 'Added' : 'Pending', badgeClass: this.skillsCount ? 'is-ok' : 'is-miss' },
                { key: 'resumes', icon: 'FileText', title: 'Resumes', value: this.resumeCount, status: this.resumeCount ? (this.defaultResume ? 'Default set' : 'Uploaded') : 'Pending', badgeClass: this.resumeCount ? 'is-ok' : 'is-miss' },
                { key: 'availability', icon: 'CircleCheck', title: 'Availability', value: this.profile?.is_available_for_work ? 'Open' : '—', status: this.profile?.is_available_for_work ? 'Available' : 'Closed', badgeClass: this.profile?.is_available_for_work ? 'is-ok' : 'is-warn' },
            ];
        },
        highlightCards() {
            return [
                this.educationCount
                    ? { key: 'education', icon: 'GraduationCap', title: 'Education', sub: `${this.educationCount} record${this.educationCount === 1 ? '' : 's'} ready`, action: this.routes.educations, link: 'Manage' }
                    : { key: 'education', icon: 'GraduationCap', title: 'No education yet', sub: 'Add your academic background', action: this.routes.educations, link: 'Add Education' },
                this.experienceCount
                    ? { key: 'experience', icon: 'Briefcase', title: 'Experience', sub: `${this.experienceCount} record${this.experienceCount === 1 ? '' : 's'} ready`, action: this.routes.experiences, link: 'Manage' }
                    : { key: 'experience', icon: 'Briefcase', title: 'No experience yet', sub: 'Show your work history', action: this.routes.experiences, link: 'Add Experience' },
                this.skillsCount
                    ? { key: 'skills', icon: 'Award', title: 'Skills', sub: `${this.skillsCount} skill${this.skillsCount === 1 ? '' : 's'} added`, action: this.routes.skills, link: 'Manage' }
                    : { key: 'skills', icon: 'Award', title: 'No skills yet', sub: 'List your strengths', action: this.routes.skills, link: 'Add Skills' },
                this.resumeCount
                    ? { key: 'resumes', icon: 'FileText', title: 'Resumes', sub: `${this.resumeCount} resume${this.resumeCount === 1 ? '' : 's'} ready`, action: this.routes.resumes, link: 'Manage' }
                    : { key: 'resumes', icon: 'FileText', title: 'No resume yet', sub: 'Upload a polished resume', action: this.routes.resumes, link: 'Upload Resume' },
            ];
        },
        quickActions() {
            return [
                { key: 'browse', label: 'Browse Jobs', icon: 'BriefcaseBusiness', href: this.routes.jobs },
                { key: 'resume', label: 'Update Resume', icon: 'FileText', href: this.routes.resumes },
                { key: 'build-resume', label: 'Build Resume', icon: 'SquarePen', href: this.routes.resumeBuilder },
                { key: 'profile', label: 'Complete Profile', icon: 'UserCog', href: this.routes.profile },
                { key: 'saved', label: 'Saved Jobs', icon: 'Bookmark', href: this.routes.savedJobs },
                { key: 'apps', label: 'Applications', icon: 'Send', href: this.routes.applications },
                { key: 'messages', label: 'Messages', icon: 'MessageSquare', href: this.routes.notifications },
                { key: 'settings', label: 'Settings', icon: 'Settings', href: this.routes.settings },
                { key: 'bookmarks', label: 'Bookmarks', icon: 'Star', href: this.routes.savedJobs },
            ];
        },
    },
    methods: {
        humanize,
        initials,
        logoFail() { this.logoFailed = true; },
        companyName(job) { return job?.company?.company_name || 'Company'; },
        jobLogo(job) { return this.logoFailed ? null : jobLogoUrl(job); },
        jobUrl(job) { return `/jobs/${job.id}`; },
        activityIcon(type) {
            switch ((type || '').toLowerCase()) {
                case 'education': return 'GraduationCap';
                case 'experience': return 'Briefcase';
                case 'skill': return 'Award';
                case 'resume': return 'FileText';
                default: return 'Sparkles';
            }
        },
        relativeTime(value) {
            if (!value) return '';
            const d = new Date(value);
            if (isNaN(d)) return '';
            const diff = (Date.now() - d.getTime()) / 1000;
            if (diff < 60) return 'just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },
    },
};
</script>

<style scoped>
.jsd {
    --jsd-primary: #2563eb;
    --jsd-secondary: #10b981;
    --jsd-accent: #f59e0b;
    --jsd-bg: #f8fafc;
    --jsd-ink: #111827;
    --jsd-muted: #6b7280;
    --jsd-border: #e5e7eb;
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--jsd-ink);
    animation: jsd-fade 0.5s ease both;
}
@keyframes jsd-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.jsd-muted { color: var(--jsd-muted); }
.jsd-fw { font-weight: 700; }
.small { font-size: 0.82rem; }
.mt-3 { margin-top: 1rem; }

/* ===== WELCOME ===== */
.jsd-welcome {
    position: relative;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: center;
    border-radius: 28px;
    overflow: hidden;
    padding: clamp(1.75rem, 4vw, 3rem);
    margin-bottom: 1.75rem;
    background: radial-gradient(120% 130% at 0% 0%, #1e3a8a 0%, #1d4ed8 35%, #2563eb 65%, #3b82f6 100%);
    box-shadow: 0 30px 60px -28px rgba(37, 99, 235, 0.55);
    isolation: isolate;
}
.jsd-welcome-bg { position: absolute; inset: 0; overflow: hidden; z-index: -1; }
.jsd-welcome-glow {
    position: absolute; width: 480px; height: 480px; border-radius: 50%;
    background: radial-gradient(circle, rgba(59,130,246,0.55), transparent 70%);
    top: -160px; right: -120px; filter: blur(40px);
    animation: jsd-float 9s ease-in-out infinite;
}
.jsd-shape { position: absolute; border-radius: 50%; filter: blur(70px); opacity: 0.45; }
.jsd-shape-1 { width: 240px; height: 240px; background: #7c3aed; bottom: -90px; left: -40px; animation: jsd-float 11s ease-in-out infinite reverse; }
.jsd-shape-2 { width: 180px; height: 180px; background: #06b6d4; top: 25%; right: 22%; animation: jsd-pulse 6s ease-in-out infinite; }
.jsd-shape-3 { width: 150px; height: 150px; background: #f59e0b; bottom: 18%; left: 28%; opacity: 0.3; animation: jsd-pulse 8s ease-in-out infinite; }
@keyframes jsd-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-22px); } }
@keyframes jsd-pulse { 0%,100% { opacity: 0.25; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.12); } }

.jsd-pill {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.4rem 0.9rem; border-radius: 999px;
    background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25);
    color: #e5e7eb; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.03em;
    margin-bottom: 1rem;
}
.jsd-welcome-title {
    font-family: 'Poppins', sans-serif; color: #fff;
    font-size: clamp(1.7rem, 3.5vw, 2.7rem); font-weight: 700; line-height: 1.15;
    margin: 0 0 0.6rem;
}
.jsd-grad { background: linear-gradient(135deg, #fde68a, #f59e0b); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
.jsd-welcome-sub { color: rgba(255,255,255,0.9); font-size: 1rem; margin: 0 0 1rem; max-width: 46ch; }
.jsd-welcome-date {
    display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.85rem;
    color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2); padding: 0.45rem 0.9rem; border-radius: 999px;
    margin-bottom: 1.25rem;
}
.jsd-welcome-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; }

.jsd-welcome-card {
    display: flex; flex-direction: column; align-items: center; gap: 1rem;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.28);
    backdrop-filter: blur(10px); border-radius: 24px; padding: 1.75rem;
}
.jsd-ring {
    width: 150px; height: 150px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}
.jsd-ring-inner {
    width: 118px; height: 118px; border-radius: 50%; background: #1d4ed8;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: #fff; box-shadow: inset 0 2px 8px rgba(0,0,0,0.25);
}
.jsd-ring-pct { font-family: 'Poppins', sans-serif; font-size: 2.1rem; font-weight: 700; line-height: 1; }
.jsd-ring-pct small { font-size: 1rem; opacity: 0.85; }
.jsd-ring-label { font-size: 0.72rem; color: rgba(255,255,255,0.85); margin-top: 2px; }
.jsd-welcome-card-meta { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
.jsd-status {
    display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; font-weight: 600;
    color: #fde68a; background: rgba(245,158,11,0.18); border: 1px solid rgba(245,158,11,0.4);
    padding: 0.35rem 0.8rem; border-radius: 999px;
}
.jsd-status.is-done { color: #a7f3d0; background: rgba(16,185,129,0.18); border-color: rgba(16,185,129,0.4); }
.jsd-avail { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: rgba(255,255,255,0.9); }
.jsd-avail-dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,0.25); }

/* ===== BUTTONS ===== */
.jsd-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.7rem 1.3rem; border-radius: 0.85rem; font-weight: 600; font-size: 0.9rem;
    border: 0; cursor: pointer; text-decoration: none; transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap;
}
.jsd-btn-sm { padding: 0.5rem 0.9rem; font-size: 0.82rem; border-radius: 0.7rem; }
.jsd-btn-block { width: 100%; }
.jsd-btn-primary { background: #fff; color: #1d4ed8; box-shadow: 0 10px 26px rgba(0,0,0,0.18); }
.jsd-btn-primary:hover { transform: translateY(-2px); color: #1e40af; box-shadow: 0 14px 34px rgba(0,0,0,0.24); }
.jsd-btn-ghost { background: rgba(255,255,255,0.14); color: #fff; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(6px); }
.jsd-btn-ghost:hover { background: rgba(255,255,255,0.24); color: #fff; transform: translateY(-2px); }
.jsd-btn-outline { background: #fff; color: var(--jsd-primary); border: 1px solid var(--jsd-border); }
.jsd-btn-outline:hover { border-color: var(--jsd-primary); color: var(--jsd-primary); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.12); }

/* ===== STATS ===== */
.jsd-stats {
    display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; margin-bottom: 1.75rem;
}
.jsd-stat {
    position: relative; overflow: hidden;
    background: #fff; border: 1px solid var(--jsd-border); border-radius: 18px;
    padding: 1.25rem 1.1rem; display: flex; flex-direction: column; gap: 0.75rem;
    box-shadow: 0 6px 24px rgba(15,23,42,0.05); transition: transform 0.3s cubic-bezier(0.22,1,0.36,1), box-shadow 0.3s;
    animation: jsd-rise 0.5s ease both;
}
.jsd-stat:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(15,23,42,0.12); }
.jsd-stat-ico {
    width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    color: #fff;
}
.jsd-stat--completion .jsd-stat-ico { background: linear-gradient(135deg, #2563eb, #4f46e5); }
.jsd-stat--education .jsd-stat-ico { background: linear-gradient(135deg, #10b981, #059669); }
.jsd-stat--experience .jsd-stat-ico { background: linear-gradient(135deg, #f59e0b, #d97706); }
.jsd-stat--skills .jsd-stat-ico { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.jsd-stat--resumes .jsd-stat-ico { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.jsd-stat--availability .jsd-stat-ico { background: linear-gradient(135deg, #ec4899, #db2777); }
.jsd-stat-value { font-family: 'Poppins', sans-serif; font-size: 1.7rem; font-weight: 700; line-height: 1; color: var(--jsd-ink); }
.jsd-stat-title { font-size: 0.8rem; color: var(--jsd-muted); font-weight: 500; }
.jsd-stat-badge {
    position: absolute; top: 1rem; right: 1rem; font-size: 0.66rem; font-weight: 700;
    padding: 0.2rem 0.55rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.04em;
}
.jsd-stat-badge.is-ok { background: rgba(16,185,129,0.12); color: #059669; }
.jsd-stat-badge.is-warn { background: rgba(245,158,11,0.14); color: #d97706; }
.jsd-stat-badge.is-miss { background: #f1f5f9; color: #64748b; }
@keyframes jsd-rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

/* ===== GRID ===== */
.jsd-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.5rem; align-items: start; }
.jsd-col-main { display: flex; flex-direction: column; gap: 1.5rem; min-width: 0; }
.jsd-col-side { min-width: 0; }

/* ===== CARDS ===== */
.jsd-card {
    background: #fff; border: 1px solid var(--jsd-border); border-radius: 22px;
    box-shadow: 0 6px 24px rgba(15,23,42,0.05); padding: clamp(1.25rem, 2.5vw, 1.75rem);
    transition: box-shadow 0.3s, transform 0.3s;
}
.jsd-card:hover { box-shadow: 0 14px 40px rgba(15,23,42,0.1); }
.jsd-card-head { display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem; }
.jsd-card-head--row { justify-content: space-between; }
.jsd-card-ico {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.jsd-card-ico--blue { background: rgba(37,99,235,0.12); color: #2563eb; }
.jsd-card-ico--violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
.jsd-card-ico--emerald { background: rgba(16,185,129,0.12); color: #059669; }
.jsd-card-ico--amber { background: rgba(245,158,11,0.12); color: #d97706; }
.jsd-card-h { font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 600; color: var(--jsd-ink); margin: 0; }
.jsd-card-sub { color: var(--jsd-muted); margin: 0; font-size: 0.85rem; }

/* completion */
.jsd-completion-bar { height: 10px; border-radius: 999px; background: #eef2ff; overflow: hidden; }
.jsd-completion-bar span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, #2563eb, #4f46e5); transition: width 1s ease; }
.jsd-completion-row { display: flex; justify-content: space-between; margin: 0.6rem 0 1rem; font-size: 0.85rem; }
.jsd-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.jsd-chip {
    display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 600;
    padding: 0.35rem 0.75rem; border-radius: 999px;
}
.jsd-chip--ok { background: rgba(16,185,129,0.12); color: #059669; border: 1px solid rgba(16,185,129,0.22); }
.jsd-chip--miss { background: #f1f5f9; color: #64748b; border: 1px solid #e5e7eb; }

/* activity */
.jsd-activity { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.25rem; }
.jsd-activity-item { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
.jsd-activity-item:last-child { border-bottom: 0; }
.jsd-activity-ico {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: rgba(37,99,235,0.08); color: var(--jsd-primary);
    display: flex; align-items: center; justify-content: center;
}
.jsd-activity-body { flex: 1; min-width: 0; }
.jsd-activity-title { font-weight: 600; font-size: 0.9rem; color: var(--jsd-ink); }
.jsd-activity-sub { font-size: 0.78rem; color: var(--jsd-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.jsd-activity-time { font-size: 0.74rem; color: #9ca3af; flex-shrink: 0; }

.jsd-empty { text-align: center; padding: 2rem 1rem; }
.jsd-empty-ico { width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 0.85rem; display: flex; align-items: center; justify-content: center; background: rgba(37,99,235,0.08); color: var(--jsd-primary); }

/* recommended */
.jsd-rec-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.jsd-rec {
    border: 1px solid #e0e7ff; border-radius: 16px; padding: 1.1rem; background: linear-gradient(180deg, #fbfcff, #ffffff);
    display: flex; flex-direction: column; gap: 0.7rem; transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
}
.jsd-rec:hover { transform: translateY(-4px); border-color: rgba(37,99,235,0.4); box-shadow: 0 14px 32px rgba(37,99,235,0.1); }
.jsd-rec-top { display: flex; align-items: flex-start; gap: 0.7rem; }
.jsd-rec-logo { width: 46px; height: 46px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; border: 1px solid var(--jsd-border); display: flex; align-items: center; justify-content: center; }
.jsd-rec-logo img { width: 100%; height: 100%; object-fit: contain; }
.jsd-rec-logo-ph { font-family: 'Poppins', sans-serif; font-weight: 800; color: var(--jsd-primary); }
.jsd-rec-headtext { flex: 1; min-width: 0; }
.jsd-rec-title { font-weight: 700; font-size: 0.92rem; color: var(--jsd-ink); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.jsd-rec-company { font-size: 0.78rem; color: var(--jsd-muted); }
.jsd-rec-level { font-size: 0.66rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 999px; background: rgba(16,185,129,0.12); color: #059669; text-transform: uppercase; white-space: nowrap; }
.jsd-rec-meta { display: flex; flex-wrap: wrap; gap: 0.4rem 0.75rem; font-size: 0.76rem; color: var(--jsd-muted); }
.jsd-rec-meta span { display: inline-flex; align-items: center; gap: 0.3rem; }
.jsd-rec-meta svg { color: var(--jsd-primary); }
.jsd-rec-reason { font-size: 0.8rem; color: #4b5563; line-height: 1.5; margin: 0; }
.jsd-rec-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.jsd-rec-score { font-size: 0.78rem; font-weight: 600; color: #059669; }

/* highlights */
.jsd-highlight-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.jsd-highlight {
    border: 1px solid var(--jsd-border); border-radius: 16px; padding: 1.1rem;
    background: linear-gradient(180deg, #f8fafc, #ffffff); display: flex; flex-direction: column; gap: 0.4rem;
    transition: all 0.25s;
}
.jsd-highlight:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15,23,42,0.08); }
.jsd-highlight-ico { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background: rgba(37,99,235,0.1); color: var(--jsd-primary); }
.jsd-highlight-title { font-weight: 700; font-size: 0.92rem; }
.jsd-highlight-sub { font-size: 0.8rem; color: var(--jsd-muted); }
.jsd-highlight-link { font-size: 0.8rem; font-weight: 600; color: var(--jsd-primary); text-decoration: none; margin-top: 0.2rem; }
.jsd-highlight-link:hover { color: #1d4ed8; text-decoration: underline; }

/* ===== SIDEBAR ===== */
.jsd-side-sticky { position: sticky; top: 92px; display: flex; flex-direction: column; gap: 1.25rem; }

.jsd-side-h { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600; color: var(--jsd-ink); margin: 0 0 1rem; }

.jsd-profile { padding: 0; overflow: hidden; }
.jsd-profile-cover { height: 70px; background: linear-gradient(135deg, #2563eb, #6366f1); }
.jsd-profile-body { padding: 0 1.25rem 1.25rem; margin-top: -34px; text-align: center; }
.jsd-profile-photo { width: 76px; height: 76px; border-radius: 20px; border: 4px solid #fff; object-fit: cover; box-shadow: 0 10px 24px rgba(0,0,0,0.15); margin: 0 auto; display: block; }
.jsd-profile-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.1rem; margin-top: 0.65rem; }
.jsd-profile-title { font-size: 0.85rem; color: var(--jsd-primary); font-weight: 600; }
.jsd-profile-loc { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; color: var(--jsd-muted); margin-top: 0.3rem; }
.jsd-profile-facts { text-align: left; margin: 1rem 0; display: flex; flex-direction: column; gap: 0.5rem; }
.jsd-fact { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--jsd-muted); }
.jsd-fact svg { color: var(--jsd-primary); flex-shrink: 0; }
.jsd-fact-val { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.jsd-profile-actions { display: flex; flex-direction: column; gap: 0.5rem; }

.jsd-side-completion-head { display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1rem; }
.jsd-side-completion-ring {
    width: 56px; height: 56px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.jsd-side-completion-ring span {
    width: 42px; height: 42px; border-radius: 50%; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 700; color: #059669;
}
.jsd-side-completion-list { display: flex; flex-direction: column; gap: 0.4rem; }
.jsd-side-completion-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #64748b; }
.jsd-side-completion-item svg { color: #f59e0b; flex-shrink: 0; }
.jsd-side-completion-done { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #059669; }
.jsd-side-completion-done svg { color: #10b981; }

.jsd-qa { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
.jsd-qa-item {
    display: flex; align-items: center; gap: 0.6rem; padding: 0.7rem 0.8rem; border-radius: 12px;
    background: #f8fafc; border: 1px solid var(--jsd-border); text-decoration: none; color: #334155;
    font-size: 0.82rem; font-weight: 600; transition: all 0.2s;
}
.jsd-qa-item:hover { background: #fff; border-color: rgba(37,99,235,0.4); color: var(--jsd-primary); transform: translateY(-2px); box-shadow: 0 8px 18px rgba(37,99,235,0.1); }
.jsd-qa-ico { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
.jsd-qa-ico--browse, .jsd-qa-ico--apps, .jsd-qa-ico--bookmarks { background: linear-gradient(135deg, #2563eb, #4f46e5); }
.jsd-qa-ico--resume, .jsd-qa-ico--saved { background: linear-gradient(135deg, #10b981, #059669); }
.jsd-qa-ico--profile, .jsd-qa-ico--settings { background: linear-gradient(135deg, #f59e0b, #d97706); }
.jsd-qa-ico--messages { background: linear-gradient(135deg, #ec4899, #db2777); }
.jsd-qa-ico--build-resume { background: linear-gradient(135deg, #6366f1, #4f46e5); }

.jsd-resume { display: flex; align-items: center; gap: 0.75rem; }
.jsd-resume-ico { width: 42px; height: 42px; border-radius: 12px; background: rgba(6,182,212,0.12); color: #0891b2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.jsd-resume-body { flex: 1; min-width: 0; }
.jsd-resume-go { width: 34px; height: 34px; border-radius: 10px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; }
.jsd-resume-go:hover { background: var(--jsd-primary); color: #fff; }

.jsd-skill-chips { display: flex; flex-wrap: wrap; gap: 0.45rem; }
.jsd-skill-chip {
    font-size: 0.78rem; font-weight: 600; padding: 0.35rem 0.8rem; border-radius: 999px;
    border: 1px solid transparent; transition: transform 0.2s;
}
.jsd-skill-chip:hover { transform: translateY(-2px); }
.jsd-skill-chip--0 { background: rgba(37,99,235,0.1); color: #1d4ed8; border-color: rgba(37,99,235,0.2); }
.jsd-skill-chip--1 { background: rgba(16,185,129,0.1); color: #059669; border-color: rgba(16,185,129,0.2); }
.jsd-skill-chip--2 { background: rgba(245,158,11,0.12); color: #d97706; border-color: rgba(245,158,11,0.22); }
.jsd-skill-chip--3 { background: rgba(139,92,246,0.12); color: #7c3aed; border-color: rgba(139,92,246,0.22); }

.jsd-tip {
    display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.82rem; color: #64748b;
    background: linear-gradient(135deg, rgba(37,99,235,0.06), rgba(139,92,246,0.06));
    border: 1px solid rgba(37,99,235,0.12); border-radius: 14px; padding: 0.85rem;
}
.jsd-tip svg { color: var(--jsd-accent); flex-shrink: 0; margin-top: 2px; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1199.98px) {
    .jsd-stats { grid-template-columns: repeat(3, 1fr); }
    .jsd-grid { grid-template-columns: 1fr 320px; }
}
@media (max-width: 991.98px) {
    .jsd-welcome { grid-template-columns: 1fr; text-align: center; }
    .jsd-welcome-actions { justify-content: center; }
    .jsd-welcome-card { margin: 0 auto; }
    .jsd-grid { grid-template-columns: 1fr; }
    .jsd-side-sticky { position: static; }
}
@media (max-width: 767.98px) {
    .jsd-stats { grid-template-columns: repeat(2, 1fr); }
    .jsd-rec-grid, .jsd-highlight-grid { grid-template-columns: 1fr; }
}
@media (max-width: 575.98px) {
    .jsd-qa { grid-template-columns: 1fr; }
}
</style>
