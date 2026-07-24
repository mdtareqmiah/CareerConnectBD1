<template>
    <div class="rf-page">
        <section class="rf-header">
            <div class="rf-header-inner">
                <div class="rf-header-content">
                    <nav aria-label="breadcrumb" class="rf-breadcrumb">
                        <ol class="rf-breadcrumb-list">
                            <li class="rf-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="rf-breadcrumb-item">
                                <a :href="routes.resumes"><FileText :size="14" /> Resume</a>
                            </li>
                            <li class="rf-breadcrumb-item rf-breadcrumb-item--active" aria-current="page">
                                {{ isEdit ? 'Edit' : 'Upload Resume' }}
                            </li>
                        </ol>
                    </nav>
                    <span class="rf-pill"><Sparkles :size="14" /> {{ isEdit ? 'Resume update' : 'Resume upload' }}</span>
                    <h1 class="rf-title">{{ isEdit ? 'Edit resume' : 'Upload resume' }}</h1>
                    <p class="rf-sub">{{ isEdit ? 'Update your resume title or replace the file.' : 'Upload a PDF, DOC, or DOCX resume.' }}</p>
                </div>
                <div class="rf-header-ico">
                    <div class="rf-img-wrap">
                        <img src="/images/resume.png" alt="Resume illustration" class="rf-header-img">
                    </div>
                </div>
            </div>
        </section>

        <div class="rf-card">
            <div class="rf-card-body">
                <div v-if="hasErrors" class="rf-alert rf-alert--danger" role="alert">
                    <div class="rf-alert-head"><AlertCircle :size="18" /> Please fix the following errors</div>
                    <ul class="rf-alert-list">
                        <li v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</li>
                    </ul>
                </div>

                <form :action="actionUrl" :method="method" class="rf-form" enctype="multipart/form-data">
                    <input v-if="method !== 'GET'" type="hidden" name="_token" :value="csrfToken">
                    <input v-if="isEdit" type="hidden" name="_method" value="PATCH">

                    <div class="rf-grid">
                        <div class="rf-field rf-field--full" :class="{ 'rf-field--error': errors.title }">
                            <label class="rf-label" for="title"><FileText :size="14" /> Resume Title <span class="rf-req">*</span></label>
                            <input id="title" name="title" type="text" class="rf-input" :value="fields.title" required placeholder="e.g. Frontend Developer Resume">
                            <div v-if="errors.title" class="rf-error">{{ errors.title[0] }}</div>
                        </div>

                        <div class="rf-field rf-field--full" :class="{ 'rf-field--error': errors.file_path }">
                            <label class="rf-label" for="file_path"><Upload :size="14" /> Resume File <span class="rf-req">*</span></label>
                            <input id="file_path" name="file_path" type="file" class="rf-input rf-file" accept=".pdf,.doc,.docx">
                            <div v-if="errors.file_path" class="rf-error">{{ errors.file_path[0] }}</div>
                            <div v-if="fields.file_name" class="rf-hint">Current file: {{ fields.file_name }}</div>
                        </div>

                        <div class="rf-field rf-field--full">
                            <label class="rf-check">
                                <input type="checkbox" name="is_default" value="1" :checked="fields.is_default">
                                <span class="rf-check-box"><CircleCheck :size="14" /></span>
                                <span>Set as default resume</span>
                            </label>
                        </div>
                    </div>

                    <div class="rf-actions">
                        <a :href="cancelRoute" class="rf-btn rf-btn-ghost"><ArrowLeft :size="15" /> Cancel</a>
                        <button type="submit" class="rf-btn rf-btn-primary"><Save :size="15" /> {{ isEdit ? 'Update Resume' : 'Upload Resume' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, FileText, Sparkles, Upload, Save, ArrowLeft, AlertCircle, CircleCheck,
} from '@lucide/vue';

export default {
    name: 'ResumeForm',
    components: {
        Home, FileText, Sparkles, Upload, Save, ArrowLeft, AlertCircle, CircleCheck,
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
.rf-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: rf-fade 0.5s ease both;
}
@keyframes rf-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.rf-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.rf-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.25), transparent 55%),
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.2), transparent 55%);
    pointer-events: none;
}
.rf-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.rf-breadcrumb { margin-bottom: 0.75rem; }
.rf-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.rf-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.rf-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.rf-breadcrumb-item a:hover { color: #fff; }
.rf-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.rf-breadcrumb-item + .rf-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.rf-pill {
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
.rf-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
}
.rf-sub {
    color: rgba(255,255,255,0.8);
    margin: 0;
    font-size: 0.95rem;
}
.rf-header-ico {
    flex-shrink: 0;
}
.rf-img-wrap {
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
.rf-header-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: drop-shadow(0 12px 24px rgba(37,99,235,0.35));
}

.rf-card {
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 18px;
    box-shadow: 0 8px 28px -16px rgba(37,99,235,0.1);
    overflow: hidden;
}
.rf-card-body {
    padding: clamp(1.5rem, 3vw, 2.25rem);
}

.rf-alert {
    padding: 0.9rem 1.1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.rf-alert--danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.rf-alert-head {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    font-size: 0.88rem;
}
.rf-alert-list {
    margin: 0;
    padding-left: 1.25rem;
    font-size: 0.84rem;
}
.rf-alert-list li + li { margin-top: 0.2rem; }

.rf-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem 1.25rem;
}
.rf-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.rf-field--full { grid-column: 1 / -1; }
.rf-field--error .rf-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
}
.rf-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
}
.rf-label svg { color: #2563eb; }
.rf-req { color: #ef4444; }
.rf-input {
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
.rf-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
    transform: translateY(-1px);
}
.rf-file { padding: 0.5rem 0; }
.rf-hint {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 0.25rem;
}
.rf-error {
    font-size: 0.76rem;
    color: #ef4444;
    font-weight: 500;
}

.rf-check {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    font-size: 0.88rem;
    font-weight: 500;
    color: #374151;
    user-select: none;
}
.rf-check input { display: none; }
.rf-check-box {
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
.rf-check input:checked + .rf-check-box {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}

.rf-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f3f4f6;
}
.rf-btn {
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
.rf-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.rf-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.rf-btn-ghost {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e7eb;
}
.rf-btn-ghost:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

@media (max-width: 767.98px) {
    .rf-grid { grid-template-columns: 1fr; }
    .rf-header-inner { flex-direction: column; text-align: center; }
    .rf-actions { flex-direction: column; }
    .rf-btn { width: 100%; justify-content: center; }
}
</style>
