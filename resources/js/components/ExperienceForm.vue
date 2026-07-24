<template>
    <div class="xf-page">
        <section class="xf-header">
            <div class="xf-header-inner">
                <div class="xf-header-content">
                    <nav aria-label="breadcrumb" class="xf-breadcrumb">
                        <ol class="xf-breadcrumb-list">
                            <li class="xf-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="xf-breadcrumb-item">
                                <a :href="routes.experiences"><BriefcaseBusiness :size="14" /> Experience</a>
                            </li>
                            <li class="xf-breadcrumb-item xf-breadcrumb-item--active" aria-current="page">
                                {{ isEdit ? 'Edit' : 'Add Experience' }}
                            </li>
                        </ol>
                    </nav>
                    <span class="xf-pill"><BriefcaseBusiness :size="14" /> {{ isEdit ? 'Experience update' : 'Experience entry' }}</span>
                    <h1 class="xf-title">{{ isEdit ? 'Edit experience' : 'Add experience' }}</h1>
                    <p class="xf-sub">{{ isEdit ? 'Update your professional history.' : 'Share your job history with employers.' }}</p>
                </div>
                <div class="xf-header-ico">
                    <div class="xf-img-wrap">
                        <img src="/images/experience.png" alt="Experience illustration" class="xf-header-img">
                    </div>
                </div>
            </div>
        </section>

        <div class="xf-card">
            <div class="xf-card-body">
                <div v-if="hasErrors" class="xf-alert xf-alert--danger" role="alert">
                    <div class="xf-alert-head"><AlertCircle :size="18" /> Please fix the following errors</div>
                    <ul class="xf-alert-list">
                        <li v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</li>
                    </ul>
                </div>

                <form :action="actionUrl" :method="method" class="xf-form">
                    <input v-if="method !== 'GET'" type="hidden" name="_token" :value="csrfToken">
                    <input v-if="isEdit" type="hidden" name="_method" value="PATCH">

                    <div class="xf-grid">
                        <div class="xf-field" :class="{ 'xf-field--error': errors.company_name }">
                            <label class="xf-label" for="company_name"><Building2 :size="14" /> Company Name <span class="xf-req">*</span></label>
                            <input id="company_name" name="company_name" type="text" class="xf-input" :value="fields.company_name" required placeholder="e.g. Acme Corp">
                            <div v-if="errors.company_name" class="xf-error">{{ errors.company_name[0] }}</div>
                        </div>

                        <div class="xf-field" :class="{ 'xf-field--error': errors.job_title }">
                            <label class="xf-label" for="job_title"><BriefcaseBusiness :size="14" /> Job Title <span class="xf-req">*</span></label>
                            <input id="job_title" name="job_title" type="text" class="xf-input" :value="fields.job_title" required placeholder="e.g. Software Engineer">
                            <div v-if="errors.job_title" class="xf-error">{{ errors.job_title[0] }}</div>
                        </div>

                        <div class="xf-field" :class="{ 'xf-field--error': errors.employment_type }">
                            <label class="xf-label" for="employment_type"><Clock3 :size="14" /> Employment Type <span class="xf-req">*</span></label>
                            <input id="employment_type" name="employment_type" type="text" class="xf-input" :value="fields.employment_type" required placeholder="e.g. Full-time">
                            <div v-if="errors.employment_type" class="xf-error">{{ errors.employment_type[0] }}</div>
                        </div>

                        <div class="xf-field" :class="{ 'xf-field--error': errors.location }">
                            <label class="xf-label" for="location"><MapPin :size="14" /> Location</label>
                            <input id="location" name="location" type="text" class="xf-input" :value="fields.location" placeholder="e.g. Dhaka, Bangladesh">
                            <div v-if="errors.location" class="xf-error">{{ errors.location[0] }}</div>
                        </div>

                        <div class="xf-field" :class="{ 'xf-field--error': errors.start_date }">
                            <label class="xf-label" for="start_date"><Calendar :size="14" /> Start Date <span class="xf-req">*</span></label>
                            <input id="start_date" name="start_date" type="date" class="xf-input" :value="fields.start_date" required>
                            <div v-if="errors.start_date" class="xf-error">{{ errors.start_date[0] }}</div>
                        </div>

                        <div class="xf-field" :class="{ 'xf-field--error': errors.end_date }">
                            <label class="xf-label" for="end_date"><Calendar :size="14" /> End Date</label>
                            <input id="end_date" name="end_date" type="date" class="xf-input" :value="fields.end_date">
                            <div v-if="errors.end_date" class="xf-error">{{ errors.end_date[0] }}</div>
                        </div>

                        <div class="xf-field xf-field--full">
                            <label class="xf-check">
                                <input type="checkbox" name="currently_working" value="1" :checked="fields.currently_working">
                                <span class="xf-check-box"><CircleCheck :size="14" /></span>
                                <span>Currently Working</span>
                            </label>
                        </div>

                        <div class="xf-field xf-field--full" :class="{ 'xf-field--error': errors.job_description }">
                            <label class="xf-label" for="job_description"><FileText :size="14" /> Job Description</label>
                            <textarea id="job_description" name="job_description" rows="4" class="xf-input xf-textarea" placeholder="Describe your responsibilities and achievements...">{{ fields.job_description }}</textarea>
                            <div v-if="errors.job_description" class="xf-error">{{ errors.job_description[0] }}</div>
                        </div>
                    </div>

                    <div class="xf-actions">
                        <a :href="cancelRoute" class="xf-btn xf-btn-ghost"><ArrowLeft :size="15" /> Cancel</a>
                        <button type="submit" class="xf-btn xf-btn-primary"><Save :size="15" /> {{ isEdit ? 'Update Experience' : 'Save Experience' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, BriefcaseBusiness, Building2, Clock3, MapPin, Calendar, FileText, CircleCheck,
    ArrowLeft, Save, AlertCircle,
} from '@lucide/vue';

export default {
    name: 'ExperienceForm',
    components: {
        Home, BriefcaseBusiness, Building2, Clock3, MapPin, Calendar, FileText, CircleCheck,
        ArrowLeft, Save, AlertCircle,
    },
    props: {
        actionUrl: { type: String, default: '' },
        method: { type: String, default: 'POST' },
        isEdit: { type: Boolean, default: false },
        csrfToken: { type: String, default: '' },
        cancelRoute: { type: String, default: '#' },
        routes: { type: Object, default: () => ({}) },
        fields: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: {
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    },
};
</script>

<style scoped>
.xf-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: xf-fade 0.5s ease both;
}
@keyframes xf-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.xf-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.xf-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.25), transparent 55%),
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.2), transparent 55%);
    pointer-events: none;
}
.xf-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.xf-breadcrumb { margin-bottom: 0.75rem; }
.xf-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.xf-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.xf-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.xf-breadcrumb-item a:hover { color: #fff; }
.xf-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.xf-breadcrumb-item + .xf-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.xf-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    margin-bottom: 0.75rem;
}
.xf-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
}
.xf-sub {
    color: rgba(255,255,255,0.8);
    margin: 0;
    font-size: 0.95rem;
}
.xf-header-ico {
    flex-shrink: 0;
}
.xf-img-wrap {
    width: 260px;
    height: 260px;
    border-radius: 32px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 24px 60px -20px rgba(15, 23, 42, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    overflow: hidden;
}
.xf-header-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: drop-shadow(0 12px 24px rgba(37,99,235,0.35));
}

.xf-card {
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 18px;
    box-shadow: 0 8px 28px -16px rgba(37,99,235,0.1);
    overflow: hidden;
}
.xf-card-body {
    padding: clamp(1.5rem, 3vw, 2.25rem);
}

.xf-alert {
    padding: 0.9rem 1.1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.xf-alert--danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.xf-alert-head {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    font-size: 0.88rem;
}
.xf-alert-list {
    margin: 0;
    padding-left: 1.25rem;
    font-size: 0.84rem;
}
.xf-alert-list li + li { margin-top: 0.2rem; }

.xf-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem 1.25rem;
}
.xf-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.xf-field--full { grid-column: 1 / -1; }
.xf-field--error .xf-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
}
.xf-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
}
.xf-label svg { color: #2563eb; }
.xf-req { color: #ef4444; }
.xf-input {
    width: 100%;
    padding: 0.7rem 0.9rem;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9rem;
    color: #111827;
    background: #fff;
    transition: all 0.2s ease;
    outline: none;
}
.xf-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
    transform: translateY(-1px);
}
.xf-textarea {
    resize: vertical;
    min-height: 100px;
}
.xf-error {
    font-size: 0.76rem;
    color: #ef4444;
    font-weight: 500;
}

.xf-check {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    font-size: 0.88rem;
    font-weight: 500;
    color: #374151;
    user-select: none;
}
.xf-check input { display: none; }
.xf-check-box {
    width: 22px;
    height: 22px;
    border-radius: 7px;
    border: 2px solid #d1d5db;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.2s;
    background: #fff;
}
.xf-check input:checked + .xf-check-box {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}

.xf-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f3f4f6;
}
.xf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.92rem;
    border: 0;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap;
}
.xf-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.xf-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.xf-btn-ghost {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e7eb;
}
.xf-btn-ghost:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

@media (max-width: 767.98px) {
    .xf-grid { grid-template-columns: 1fr; }
    .xf-header-inner { flex-direction: column; text-align: center; }
    .xf-actions { flex-direction: column; }
    .xf-btn { width: 100%; justify-content: center; }
}
</style>
