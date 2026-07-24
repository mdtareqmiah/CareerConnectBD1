<template>
    <div class="sf-page">
        <section class="sf-header">
            <div class="sf-header-inner">
                <div class="sf-header-content">
                    <nav aria-label="breadcrumb" class="sf-breadcrumb">
                        <ol class="sf-breadcrumb-list">
                            <li class="sf-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="sf-breadcrumb-item">
                                <a :href="routes.skills"><Brain :size="14" /> Skills</a>
                            </li>
                            <li class="sf-breadcrumb-item sf-breadcrumb-item--active" aria-current="page">
                                {{ isEdit ? 'Edit' : 'Add Skill' }}
                            </li>
                        </ol>
                    </nav>
                    <span class="sf-pill"><Sparkles :size="14" /> {{ isEdit ? 'Skill update' : 'New skill' }}</span>
                    <h1 class="sf-title">{{ isEdit ? 'Edit skill' : 'Add skill' }}</h1>
                    <p class="sf-sub">{{ isEdit ? 'Update your skills and experience level.' : 'Highlight your professional skills to make your profile more attractive to employers.' }}</p>
                </div>
                <div class="sf-header-ico">
                    <div class="sf-img-wrap">
                        <img src="/images/skills.png" alt="Skills illustration" class="sf-header-img">
                    </div>
                </div>
            </div>
        </section>

        <div class="sf-card">
            <div class="sf-card-body">
                <div v-if="hasErrors" class="sf-alert sf-alert--danger" role="alert">
                    <div class="sf-alert-head"><AlertCircle :size="18" /> Please fix the following errors</div>
                    <ul class="sf-alert-list">
                        <li v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</li>
                    </ul>
                </div>

                <form :action="actionUrl" :method="method" class="sf-form">
                    <input v-if="method !== 'GET'" type="hidden" name="_token" :value="csrfToken">
                    <input v-if="isEdit" type="hidden" name="_method" value="PATCH">

                    <div class="sf-grid">
                        <div class="sf-field sf-field--full" :class="{ 'sf-field--error': errors.skill_name }">
                            <label class="sf-label" for="skill_name"><Brain :size="14" /> Skill Name <span class="sf-req">*</span></label>
                            <input id="skill_name" name="skill_name" type="text" class="sf-input" :value="fields.skill_name" required placeholder="e.g. Vue.js">
                            <div v-if="errors.skill_name" class="sf-error">{{ errors.skill_name[0] }}</div>
                        </div>

                        <div class="sf-field" :class="{ 'sf-field--error': errors.proficiency_level }">
                            <label class="sf-label" for="proficiency_level"><BadgeCheck :size="14" /> Proficiency Level <span class="sf-req">*</span></label>
                            <select id="proficiency_level" name="proficiency_level" class="sf-input sf-select" :value="fields.proficiency_level" required>
                                <option value="">Select proficiency</option>
                                <option v-for="level in levels" :key="level" :value="level" :selected="fields.proficiency_level === level">{{ level }}</option>
                            </select>
                            <div v-if="errors.proficiency_level" class="sf-error">{{ errors.proficiency_level[0] }}</div>
                        </div>

                        <div class="sf-field" :class="{ 'sf-field--error': errors.years_of_experience }">
                            <label class="sf-label" for="years_of_experience"><Clock3 :size="14" /> Years of Experience</label>
                            <input id="years_of_experience" name="years_of_experience" type="number" step="0.5" min="0" max="60" class="sf-input" :value="fields.years_of_experience" placeholder="e.g. 3">
                            <div v-if="errors.years_of_experience" class="sf-error">{{ errors.years_of_experience[0] }}</div>
                        </div>

                        <div class="sf-field sf-field--full" :class="{ 'sf-field--error': errors.notes }">
                            <label class="sf-label" for="notes"><FileText :size="14" /> Notes</label>
                            <textarea id="notes" name="notes" rows="4" class="sf-input sf-textarea" placeholder="Optional notes about this skill...">{{ fields.notes }}</textarea>
                            <div v-if="errors.notes" class="sf-error">{{ errors.notes[0] }}</div>
                        </div>
                    </div>

                    <div class="sf-actions">
                        <a :href="cancelRoute" class="sf-btn sf-btn-ghost"><ArrowLeft :size="15" /> Cancel</a>
                        <button type="submit" class="sf-btn sf-btn-primary"><Save :size="15" /> {{ isEdit ? 'Update Skill' : 'Save Skill' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, Brain, Sparkles, Code2, BadgeCheck, Clock3, FileText, ArrowLeft, Save, AlertCircle,
} from '@lucide/vue';

export default {
    name: 'SkillForm',
    components: {
        Home, Brain, Sparkles, Code2, BadgeCheck, Clock3, FileText, ArrowLeft, Save, AlertCircle,
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
    data() {
        return {
            levels: ['Beginner', 'Intermediate', 'Advanced', 'Expert'],
        };
    },
    computed: {
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    },
};
</script>

<style scoped>
.sf-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: sf-fade 0.5s ease both;
}
@keyframes sf-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.sf-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.sf-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.25), transparent 55%),
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.2), transparent 55%);
    pointer-events: none;
}
.sf-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.sf-breadcrumb { margin-bottom: 0.75rem; }
.sf-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.sf-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.sf-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.sf-breadcrumb-item a:hover { color: #fff; }
.sf-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.sf-breadcrumb-item + .sf-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.sf-pill {
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
.sf-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
}
.sf-sub {
    color: rgba(255,255,255,0.8);
    margin: 0;
    font-size: 0.95rem;
}
.sf-header-ico {
    flex-shrink: 0;
}
.sf-img-wrap {
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
.sf-header-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: drop-shadow(0 12px 24px rgba(37,99,235,0.35));
}

.sf-card {
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 18px;
    box-shadow: 0 8px 28px -16px rgba(37,99,235,0.1);
    overflow: hidden;
}
.sf-card-body {
    padding: clamp(1.5rem, 3vw, 2.25rem);
}

.sf-alert {
    padding: 0.9rem 1.1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.sf-alert--danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.sf-alert-head {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    font-size: 0.88rem;
}
.sf-alert-list {
    margin: 0;
    padding-left: 1.25rem;
    font-size: 0.84rem;
}
.sf-alert-list li + li { margin-top: 0.2rem; }

.sf-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem 1.25rem;
}
.sf-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.sf-field--full { grid-column: 1 / -1; }
.sf-field--error .sf-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
}
.sf-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
}
.sf-label svg { color: #2563eb; }
.sf-req { color: #ef4444; }
.sf-input {
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
.sf-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
    transform: translateY(-1px);
}
.sf-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}
.sf-textarea {
    resize: vertical;
    min-height: 100px;
}
.sf-error {
    font-size: 0.76rem;
    color: #ef4444;
    font-weight: 500;
}

.sf-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f3f4f6;
}
.sf-btn {
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
.sf-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.sf-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.sf-btn-ghost {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e7eb;
}
.sf-btn-ghost:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

@media (max-width: 767.98px) {
    .sf-grid { grid-template-columns: 1fr; }
    .sf-header-inner { flex-direction: column; text-align: center; }
    .sf-actions { flex-direction: column; }
    .sf-btn { width: 100%; justify-content: center; }
}
</style>
