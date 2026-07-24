<template>
    <div class="resume-page">
        <section class="resume-header">
            <div class="resume-header-inner">
                <div class="resume-header-content">
                    <nav aria-label="breadcrumb" class="resume-breadcrumb">
                        <ol class="resume-breadcrumb-list">
                            <li class="resume-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="resume-breadcrumb-item resume-breadcrumb-item--active" aria-current="page">Resume Management</li>
                        </ol>
                    </nav>
                    <div class="resume-title-row">
                        <div class="resume-title-ico"><FileText :size="28" /></div>
                        <div>
                            <span class="resume-pill"><Sparkles :size="14" /> Resume library</span>
                            <h1 class="resume-title">Resume Management</h1>
                            <p class="resume-sub">Manage your resume to improve your chances of getting hired.</p>
                        </div>
                    </div>
                </div>
                <div class="resume-header-ico">
                    <div class="resume-icon-badge"><Upload :size="36" /></div>
                    <div class="resume-icon-badge resume-icon-badge--secondary"><Download :size="28" /></div>
                    <div class="resume-icon-badge resume-icon-badge--tertiary"><Eye :size="24" /></div>
                </div>
            </div>
        </section>

        <div class="resume-actions">
            <div class="resume-count">
                <span class="resume-count-badge">{{ resumes.length }} resume{{ resumes.length === 1 ? '' : 's' }}</span>
            </div>
            <a :href="routes.create" class="resume-btn resume-btn-primary"><Plus :size="16" /> Upload Resume</a>
        </div>

        <div class="resume-builder-options">
            <a :href="builderRoutes.ai || '#'" class="resume-builder-card resume-builder-card--ai">
                <div class="resume-builder-ico"><Sparkles :size="28" /></div>
                <div>
                    <h3 class="resume-builder-title">AI Resume Builder</h3>
                    <p class="resume-builder-text">Generate a professional resume automatically with AI.</p>
                </div>
                <ArrowRight :size="18" class="resume-builder-arrow" />
            </a>
            <a :href="builderRoutes.manual" class="resume-builder-card resume-builder-card--manual">
                <div class="resume-builder-ico"><SquarePen :size="28" /></div>
                <div>
                    <h3 class="resume-builder-title">Manual Resume Builder</h3>
                    <p class="resume-builder-text">Build and customize your resume step by step.</p>
                </div>
                <ArrowRight :size="18" class="resume-builder-arrow" />
            </a>
        </div>

        <div v-if="!resumes.length" class="resume-empty">
            <div class="resume-empty-ico"><FileText :size="32" /></div>
            <h2 class="resume-empty-title">No resumes uploaded yet</h2>
            <p class="resume-empty-text">Add your latest resume to make a strong impression across recruiter workflows.</p>
            <a :href="routes.create" class="resume-btn resume-btn-primary"><Plus :size="16" /> Upload Resume</a>
        </div>

        <div v-else class="resume-grid">
            <div v-for="resume in resumes" :key="resume.id" class="resume-card">
                <div class="resume-card-head">
                    <div class="resume-card-ico"><FileCheck :size="20" /></div>
                    <div class="resume-card-title-group">
                        <h3 class="resume-card-title">{{ resume.title }}</h3>
                        <p class="resume-card-sub">{{ resume.file_name }}</p>
                    </div>
                    <span v-if="resume.is_default" class="resume-badge resume-badge--default">Default</span>
                </div>
                <div class="resume-card-body">
                    <div class="resume-card-row">
                        <span class="resume-card-label"><FileText :size="14" /> Type</span>
                        <span class="resume-card-value">{{ resume.file_type ? resume.file_type.toUpperCase() : 'FILE' }}</span>
                    </div>
                    <div class="resume-card-row" v-if="resume.file_size">
                        <span class="resume-card-label"><Download :size="14" /> Size</span>
                        <span class="resume-card-value">{{ formatSize(resume.file_size) }}</span>
                    </div>
                    <div class="resume-card-row" v-if="resume.uploaded_at">
                        <span class="resume-card-label"><Calendar :size="14" /> Uploaded</span>
                        <span class="resume-card-value">{{ formatDate(resume.uploaded_at) }}</span>
                    </div>
                </div>
                <div class="resume-card-actions">
                    <a :href="resume.file_path" target="_blank" class="resume-icon-btn" aria-label="Preview"><Eye :size="16" /></a>
                    <a :href="downloadRoute(resume.id)" class="resume-icon-btn" aria-label="Download"><Download :size="16" /></a>
                    <a :href="editRoute(resume.id)" class="resume-icon-btn" aria-label="Edit"><SquarePen :size="16" /></a>
                    <button type="button" class="resume-icon-btn resume-icon-btn--danger" @click="confirmDelete(resume)" aria-label="Delete"><Trash2 :size="16" /></button>
                </div>

                <div v-if="deleteTarget && deleteTarget.id === resume.id" class="resume-modal-overlay" @click.self="deleteTarget = null">
                    <div class="resume-modal">
                        <div class="resume-modal-head">
                            <h3>Delete Resume</h3>
                            <button type="button" class="resume-modal-close" @click="deleteTarget = null">&times;</button>
                        </div>
                        <div class="resume-modal-body">
                            Are you sure you want to delete <strong>{{ deleteTarget.title }}</strong>?
                        </div>
                        <div class="resume-modal-foot">
                            <button type="button" class="resume-btn resume-btn-ghost" @click="deleteTarget = null">Cancel</button>
                            <form :action="deleteRoute(deleteTarget.id)" method="POST" style="display:inline">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="resume-btn resume-btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, FileText, Sparkles, Upload, Download, Eye, Plus, SquarePen, Trash2, Calendar, FileCheck, ArrowRight,
} from '@lucide/vue';

export default {
    name: 'ResumeIndex',
    components: { Home, FileText, Sparkles, Upload, Download, Eye, Plus, SquarePen, Trash2, Calendar, FileCheck, ArrowRight },
    props: {
        resumes: { type: Array, default: () => [] },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
        builderRoutes: { type: Object, default: () => ({}) },
    },
    data() {
        return { deleteTarget: null };
    },
    methods: {
        formatSize(bytes) {
            if (!bytes && bytes !== 0) return '—';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },
        formatDate(v) {
            if (!v) return '—';
            const s = typeof v === 'string' ? v : (v.date || '');
            if (!s) return '—';
            const d = new Date(s + 'T00:00:00');
            if (isNaN(d)) return '—';
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        editRoute(id) {
            const pattern = this.routes.edit || '/job-seeker/resumes/__ID__/edit';
            return pattern.replace('__ID__', String(id));
        },
        deleteRoute(id) {
            const pattern = this.routes.delete || '/job-seeker/resumes/__ID__';
            return pattern.replace('__ID__', String(id));
        },
        downloadRoute(id) {
            const pattern = this.routes.download || '/job-seeker/resumes/__ID__/download';
            return pattern.replace('__ID__', String(id));
        },
        confirmDelete(resume) {
            this.deleteTarget = resume;
        },
    },
};
</script>

<style scoped>
.resume-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: resume-fade 0.5s ease both;
}
@keyframes resume-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.resume-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.resume-header::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    pointer-events: none;
}
.resume-header::after {
    content: '';
    position: absolute;
    bottom: -60px;
    left: -60px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
    pointer-events: none;
}
.resume-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.resume-breadcrumb { margin-bottom: 0.75rem; }
.resume-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.resume-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.resume-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.resume-breadcrumb-item a:hover { color: #fff; }
.resume-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.resume-breadcrumb-item + .resume-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.resume-title-row {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.resume-title-ico {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.resume-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 1rem;
    border-radius: 999px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    margin-bottom: 0.6rem;
}
.resume-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
    line-height: 1.2;
}
.resume-sub {
    color: rgba(255,255,255,0.85);
    margin: 0;
    font-size: 0.95rem;
}
.resume-header-ico {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
}
.resume-icon-badge {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 28px rgba(245, 158, 11, 0.35);
}
.resume-icon-badge--secondary {
    background: linear-gradient(135deg, #34d399, #10b981);
    box-shadow: 0 12px 28px rgba(16, 185, 129, 0.35);
}
.resume-icon-badge--tertiary {
    background: linear-gradient(135deg, #a78bfa, #8b5cf6);
    box-shadow: 0 12px 28px rgba(139, 92, 246, 0.35);
}

.resume-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.resume-builder-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.resume-builder-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    background: #fff;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 6px 18px rgba(15,23,42,0.05);
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
}
.resume-builder-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(15,23,42,0.1);
    border-color: rgba(37,99,235,0.35);
    color: inherit;
}
.resume-builder-card--ai:hover { border-color: rgba(245,158,11,0.5); }
.resume-builder-card--manual:hover { border-color: rgba(16,185,129,0.5); }
.resume-builder-ico {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
}
.resume-builder-card--ai .resume-builder-ico { background: linear-gradient(135deg, #fbbf24, #f59e0b); box-shadow: 0 10px 22px rgba(245,158,11,0.3); }
.resume-builder-card--manual .resume-builder-ico { background: linear-gradient(135deg, #34d399, #10b981); box-shadow: 0 10px 22px rgba(16,185,129,0.3); }
.resume-builder-title { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 0.2rem; }
.resume-builder-text { font-size: 0.84rem; color: #6b7280; margin: 0; }
.resume-builder-arrow { margin-left: auto; color: #9ca3af; transition: all 0.2s; }
.resume-builder-card:hover .resume-builder-arrow { color: #2563eb; transform: translateX(3px); }
.resume-count-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    background: rgba(37,99,235,0.08);
    color: #2563eb;
    font-size: 0.82rem;
    font-weight: 600;
}
.resume-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    padding: 0.7rem 1.3rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    border: 0;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap;
}
.resume-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.resume-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.resume-btn-ghost {
    background: #f1f5f9;
    color: #475569;
}
.resume-btn-ghost:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.resume-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 8px 18px rgba(239,68,68,0.25);
}
.resume-btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(239,68,68,0.35);
}

.resume-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 24px;
    box-shadow: 0 10px 30px -20px rgba(37,99,235,0.12);
}
.resume-empty-ico {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(37,99,235,0.08);
    color: #2563eb;
}
.resume-empty-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}
.resume-empty-text {
    color: #6b7280;
    margin: 0 0 1.5rem;
    font-size: 0.95rem;
}

.resume-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}
.resume-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 8px 24px rgba(15,23,42,0.05);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    animation: resume-fade 0.5s ease both;
}
.resume-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(15,23,42,0.1);
    border-color: rgba(37,99,235,0.3);
}
.resume-card-head {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    margin-bottom: 1rem;
}
.resume-card-ico {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(37,99,235,0.1);
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.resume-card-title-group { flex: 1; min-width: 0; }
.resume-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1.3;
}
.resume-card-sub {
    font-size: 0.82rem;
    color: #6b7280;
    margin: 0.15rem 0 0;
}
.resume-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    white-space: nowrap;
}
.resume-badge--default {
    background: rgba(16,185,129,0.12);
    color: #059669;
}

.resume-card-body {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.resume-card-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.45rem 0;
    border-bottom: 1px solid #f3f4f6;
}
.resume-card-row:last-of-type {
    border-bottom: 0;
}
.resume-card-label {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #6b7280;
}
.resume-card-label svg { color: #9ca3af; }
.resume-card-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: #111827;
    text-align: right;
}

.resume-card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f3f4f6;
}
.resume-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}
.resume-icon-btn:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
}
.resume-icon-btn--danger:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #dc2626;
}

.resume-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 1rem;
    animation: resume-fade 0.2s ease;
}
.resume-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 24px 60px -20px rgba(15,23,42,0.3);
    overflow: hidden;
}
.resume-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem 0;
}
.resume-modal-head h3 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0;
}
.resume-modal-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.25rem;
    transition: all 0.2s;
}
.resume-modal-close:hover {
    background: #f1f5f9;
    color: #111827;
}
.resume-modal-body {
    padding: 1rem 1.5rem 1.25rem;
    color: #4b5563;
    font-size: 0.92rem;
    line-height: 1.6;
}
.resume-modal-foot {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
}

@media (max-width: 767.98px) {
    .resume-header-inner { flex-direction: column; text-align: center; }
    .resume-card-row { flex-direction: column; align-items: flex-start; gap: 0.2rem; }
    .resume-card-value { text-align: left; }
}
</style>
