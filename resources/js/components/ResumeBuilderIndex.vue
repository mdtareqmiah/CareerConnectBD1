<template>
    <div class="rb-studio">
        <!-- Hero Section -->
        <section class="rb-hero">
            <div class="rb-hero-bg" aria-hidden="true">
                <div class="rb-hero-shape rb-hero-shape--1"></div>
                <div class="rb-hero-shape rb-hero-shape--2"></div>
                <div class="rb-hero-shape rb-hero-shape--3"></div>
                <div class="rb-hero-glow"></div>
            </div>
            <div class="rb-hero-inner">
                <div class="rb-hero-content">
                    <span class="rb-hero-pill">
                        <Sparkles :size="16" />
                        <span>Resume Builder Studio</span>
                    </span>
                    <h1 class="rb-hero-title">Resume Builder Studio</h1>
                    <p class="rb-hero-sub">Design a professional resume that helps you stand out and get hired faster. Live preview, premium templates, and a workspace built for focus.</p>
                    <div class="rb-hero-actions">
                        <a :href="builderRoutes.create" class="rb-btn rb-btn-primary">
                            <Plus :size="18" /><span>Create New Resume</span>
                        </a>
                        <a :href="homeRoute" class="rb-btn rb-btn-ghost">Back to Dashboard</a>
                    </div>
                </div>
                <div class="rb-hero-visual">
                    <div class="rb-hero-card">
                        <div class="rb-hero-card-header">
                            <span class="rb-dot rb-dot--r"></span>
                            <span class="rb-dot rb-dot--y"></span>
                            <span class="rb-dot rb-dot--g"></span>
                        </div>
                        <div class="rb-hero-card-body">
                            <div class="rb-hero-avatar">CC</div>
                            <div class="rb-hero-preview-line rb-hero-preview-line--title"></div>
                            <div class="rb-hero-preview-line rb-hero-preview-line--sub"></div>
                            <div class="rb-hero-preview-block">
                                <div class="rb-hero-preview-line rb-hero-preview-line--sm"></div>
                                <div class="rb-hero-preview-line rb-hero-preview-line--sm"></div>
                                <div class="rb-hero-preview-line rb-hero-preview-line--sm rb-hero-preview-line--short"></div>
                            </div>
                            <div class="rb-hero-preview-chips">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                    </div>
                    <div class="rb-hero-float rb-hero-float--palette"><Palette :size="18" /></div>
                    <div class="rb-hero-float rb-hero-float--spark"><Sparkles :size="18" /></div>
                </div>
            </div>
        </section>

        <!-- Resume Builders Section -->
        <section class="rb-section">
            <div class="rb-section-inner">
                <div class="rb-section-head">
                    <div>
                        <h2 class="rb-section-title">Your Resume Builders</h2>
                        <p class="rb-section-sub">Manage your in-app resume drafts and published builder profiles.</p>
                    </div>
                    <a :href="builderRoutes.create" class="rb-btn rb-btn-primary rb-btn-sm">
                        <Plus :size="16" /><span>Create Resume</span>
                    </a>
                </div>

                <div v-if="!resumeBuilders.length" class="rb-empty">
                    <div class="rb-empty-visual">
                        <div class="rb-empty-card"><FileText :size="48" /></div>
                        <div class="rb-empty-shape rb-empty-shape--1"></div>
                        <div class="rb-empty-shape rb-empty-shape--2"></div>
                    </div>
                    <h3 class="rb-empty-title">No resume builders yet</h3>
                    <p class="rb-empty-text">Create a resume draft that you can update later. Build your CV step by step and preview it live.</p>
                    <a :href="builderRoutes.create" class="rb-btn rb-btn-primary">
                        <Plus :size="18" /><span>Create Your First Resume</span>
                    </a>
                </div>

                <div v-else class="rb-grid">
                    <div v-for="builder in resumeBuilders" :key="builder.id" class="rb-card">
                        <div class="rb-card-header">
                            <div class="rb-card-ico"><FileText :size="22" /></div>
                            <div class="rb-card-meta">
                                <h3 class="rb-card-title">{{ builder.title || 'Untitled Resume' }}</h3>
                                <div class="rb-card-badges">
                                    <span class="rb-badge" :class="'rb-badge--' + (builder.status || 'draft')">
                                        {{ builder.status ? builder.status.charAt(0).toUpperCase() + builder.status.slice(1) : 'Draft' }}
                                    </span>
                                    <span v-if="builder.is_default" class="rb-badge rb-badge--default">Default</span>
                                </div>
                            </div>
                        </div>
                        <p class="rb-card-summary">
                            {{ builder.professional_summary ? truncate(builder.professional_summary, 120) : 'No professional summary provided yet.' }}
                        </p>
                        <div class="rb-card-footer">
                            <span class="rb-card-date">{{ formatDate(builder.updated_at) }}</span>
                            <div class="rb-card-actions">
                                <a :href="builder.preview_url" class="rb-card-link" target="_blank" rel="noopener">
                                    <Eye :size="15" /> Preview
                                </a>
                                <button type="button" class="rb-card-link" @click="downloadPdf(builder)">
                                    <Download :size="15" /> PDF
                                </button>
                                <a :href="builder.edit_url" class="rb-card-link rb-card-link--primary">
                                    <SquarePen :size="15" /> Edit
                                </a>
                                <button type="button" class="rb-card-link rb-card-link--danger" :disabled="isDeleting(builder.id)" @click="deleteBuilder(builder)">
                                    <Loader2 v-if="isDeleting(builder.id)" :size="15" class="rb-spin" />
                                    <Trash2 v-else :size="15" /> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Template Showcase Section -->
        <section class="rb-templates">
            <div class="rb-section-inner">
                <div class="rb-section-head rb-section-head--center">
                    <div>
                        <span class="rb-kicker"><LayoutTemplate :size="15" /> Premium Templates</span>
                        <h2 class="rb-section-title">Start with a template that fits you</h2>
                        <p class="rb-section-sub">Every template is A4-ready and previews instantly while you build.</p>
                    </div>
                </div>

                <div class="rb-template-grid">
                    <article
                        v-for="template in templates"
                        :key="template.key"
                        class="rb-template-card"
                        :style="{ '--tone-a': template.colors[0], '--tone-b': template.colors[1] }"
                    >
                        <div class="rb-template-preview">
                            <div class="rb-template-mini">
                                <div class="rb-template-mini-bar"></div>
                                <div class="rb-template-mini-line rb-template-mini-line--lg"></div>
                                <div class="rb-template-mini-line rb-template-mini-line--sm"></div>
                                <div class="rb-template-mini-line rb-template-mini-line--sm rb-template-mini-line--half"></div>
                                <div class="rb-template-mini-chip"></div>
                                <div class="rb-template-mini-chip"></div>
                            </div>
                            <div class="rb-template-overlay">
                                <a :href="builderRoutes.create + '?template=' + template.key" class="rb-btn rb-btn-ghost-light">
                                    <Brush :size="15" /> Use {{ template.name }}
                                </a>
                            </div>
                        </div>
                        <div class="rb-template-meta">
                            <div class="rb-template-meta-text">
                                <h3 class="rb-template-name">{{ template.name }}</h3>
                                <p class="rb-template-desc">{{ template.description }}</p>
                            </div>
                            <span class="rb-template-swatch"></span>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import {
    Sparkles, Plus, FileText, Eye, SquarePen, Palette, LayoutTemplate, Brush,
    Download, Trash2, Loader2,
} from '@lucide/vue';

export default {
    name: 'ResumeBuilderIndex',
    components: { Sparkles, Plus, FileText, Eye, SquarePen, Palette, LayoutTemplate, Brush, Download, Trash2, Loader2 },
    props: {
        resumeBuilders: { type: Array, default: () => [] },
        templates: { type: Array, default: () => [] },
        routes: { type: Object, default: () => ({}) },
        builderRoutes: { type: Object, default: () => ({}) },
        csrfToken: { type: String, default: '' },
        homeRoute: { type: String, default: '/job-seeker/dashboard' },
    },
    data() {
        return {
            deletingIds: [],
            downloadError: '',
        };
    },
    computed: {
        createRoute() {
            return this.builderRoutes.create || this.routes.create || '/job-seeker/resume-builders/create';
        },
    },
    methods: {
        currentCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            const fromMeta = meta ? meta.getAttribute('content') : '';
            return fromMeta || this.csrfToken || '';
        },
        isDeleting(id) {
            return this.deletingIds.includes(id);
        },
        downloadPdf(builder) {
            if (!builder.download_url) return;
            window.open(builder.download_url, '_blank', 'noopener');
        },
        deleteUrl(builder) {
            if (builder.delete_url) return builder.delete_url;
            return `/job-seeker/resume-builders/${builder.id}`;
        },
        async deleteBuilder(builder) {
            if (!builder || !builder.id) return;
            if (this.isDeleting(builder.id)) return;
            const name = builder.title || 'Untitled Resume';
            if (!window.confirm(`Delete "${name}"? This action cannot be undone.`)) return;
            this.deletingIds.push(builder.id);
            try {
                const res = await fetch(this.deleteUrl(builder), {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.currentCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                if (!res.ok) {
                    let message = 'Could not delete the resume. Please try again.';
                    try {
                        const data = await res.json();
                        if (data && data.message) message = data.message;
                        else if (res.status === 405) message = 'Delete is not allowed for this resume. Please refresh the page and try again.';
                    } catch (e) { /* ignore */ }
                    this.downloadError = message;
                    alert(message);
                    return;
                }
                const idx = this.resumeBuilders.findIndex((b) => b.id === builder.id);
                if (idx !== -1) this.resumeBuilders.splice(idx, 1);
            } catch (err) {
                const msg = (err && err.message) ? err.message : 'Network error while deleting.';
                this.downloadError = msg;
                alert('Delete failed: ' + msg);
            } finally {
                this.deletingIds = this.deletingIds.filter((id) => id !== builder.id);
            }
        },
        truncate(text, length) {
            if (!text) return '';
            return text.length > length ? text.slice(0, length) + '...' : text;
        },
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            });
        },
    },
};
</script>

<style scoped>
.rb-studio {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ---------- HERO ---------- */
.rb-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 38%, #7c3aed 100%);
    color: #fff;
    padding: 4.5rem 0 4rem;
}
.rb-hero-bg { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
.rb-hero-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.16;
    background: #fff;
}
.rb-hero-shape--1 { width: 520px; height: 520px; top: -160px; right: -90px; }
.rb-hero-shape--2 { width: 320px; height: 320px; bottom: -110px; left: -70px; }
.rb-hero-shape--3 { width: 220px; height: 220px; top: 42%; left: 58%; }
.rb-hero-glow {
    position: absolute; inset: 0;
    background: radial-gradient(circle at 78% 18%, rgba(139,92,246,0.45), transparent 45%),
                radial-gradient(circle at 18% 82%, rgba(6,182,212,0.35), transparent 50%);
    pointer-events: none;
}
@keyframes rb-float {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-22px) scale(1.06); }
}
.rb-hero-inner {
    position: relative;
    z-index: 1;
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem);
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 3rem;
    align-items: center;
}
.rb-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 1rem;
    border-radius: 999px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    color: #e0e7ff;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
    backdrop-filter: blur(8px);
}
.rb-hero-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(2.1rem, 4.4vw, 3rem);
    font-weight: 800;
    line-height: 1.12;
    margin: 0 0 1rem;
    letter-spacing: -0.02em;
}
.rb-hero-sub {
    font-size: 1.05rem;
    line-height: 1.65;
    color: rgba(255,255,255,0.86);
    margin: 0 0 2rem;
    max-width: 520px;
}
.rb-hero-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

/* Hero visual */
.rb-hero-visual { position: relative; display: flex; justify-content: center; align-items: center; }
.rb-hero-card {
    width: 100%;
    max-width: 380px;
    background: rgba(255,255,255,0.96);
    border-radius: 22px;
    box-shadow: 0 36px 70px -22px rgba(0,0,0,0.45);
    overflow: hidden;
    transform: rotate(-2.5deg);
}

.rb-hero-card-header {
    padding: 0.9rem 1.2rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; gap: 0.45rem;
}
.rb-dot { width: 11px; height: 11px; border-radius: 50%; }
.rb-dot--r { background: #ef4444; }
.rb-dot--y { background: #f59e0b; }
.rb-dot--g { background: #10b981; }
.rb-hero-card-body { padding: 1.6rem 1.25rem; }
.rb-hero-avatar {
    width: 56px; height: 56px; border-radius: 16px;
    background: linear-gradient(135deg, #2563eb, #8b5cf6);
    color: #fff; font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 12px 24px rgba(37,99,235,0.3);
    margin-bottom: 1rem;
}
.rb-hero-preview-line { height: 10px; border-radius: 6px; background: #e5e7eb; margin-bottom: 0.75rem; }
.rb-hero-preview-line--title { width: 72%; height: 16px; background: #1e293b; margin-bottom: 0.5rem; }
.rb-hero-preview-line--sub { width: 46%; }
.rb-hero-preview-block { margin-top: 1.25rem; }
.rb-hero-preview-line--sm { width: 100%; }
.rb-hero-preview-line--short { width: 60%; }
.rb-hero-preview-chips { display: flex; gap: 0.5rem; margin-top: 1.25rem; }
.rb-hero-preview-chips span { height: 22px; width: 64px; border-radius: 999px; background: #eef2ff; }
.rb-hero-float {
    position: absolute;
    width: 54px; height: 54px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    box-shadow: 0 18px 36px -12px rgba(0,0,0,0.4);
}
.rb-hero-float--palette { top: 8%; left: -4%; background: linear-gradient(135deg, #8b5cf6, #6366f1); }
.rb-hero-float--spark { bottom: 10%; right: -3%; background: linear-gradient(135deg, #06b6d4, #2563eb); }

/* ---------- BUTTONS ---------- */
.rb-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 14px;
    font-size: 0.92rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    border: 0;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
}
.rb-btn-primary {
    background: #fff;
    color: #1e3a8a;
    box-shadow: 0 10px 26px rgba(0,0,0,0.18);
}
.rb-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 34px rgba(0,0,0,0.24); color: #1e3a8a; }
.rb-btn-ghost {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.22);
    backdrop-filter: blur(8px);
}
.rb-btn-ghost:hover { background: rgba(255,255,255,0.2); }
.rb-btn-sm { padding: 0.55rem 1.1rem; font-size: 0.85rem; border-radius: 12px; }
.rb-btn-ghost-light {
    background: rgba(255,255,255,0.92);
    color: #1e3a8a;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}
.rb-btn-ghost-light:hover { background: #fff; transform: translateY(-2px); }

/* ---------- SECTIONS ---------- */
.rb-section { flex: 1; padding: 3.5rem 0; }
.rb-section-inner { max-width: 1320px; margin: 0 auto; padding: 0 clamp(1rem, 3vw, 2.25rem); width: 100%; }
.rb-section-head {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    margin-bottom: 2.25rem; flex-wrap: wrap;
}
.rb-section-head--center { justify-content: center; text-align: center; }
.rb-section-head--center > div { display: flex; flex-direction: column; align-items: center; }
.rb-section-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.35rem;
    letter-spacing: -0.01em;
}
.rb-section-sub { font-size: 0.95rem; color: #64748b; margin: 0; }
.rb-kicker {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.35rem 0.9rem; border-radius: 999px;
    background: rgba(37,99,235,0.1);
    color: #2563eb; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.02em;
    margin-bottom: 1rem;
}

/* ---------- GRID CARDS ---------- */
.rb-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.25rem; }
.rb-card {
    position: relative;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(226,232,240,0.9);
    border-radius: 22px;
    padding: 1.5rem;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    box-shadow: 0 6px 18px rgba(15,23,42,0.05);
    display: flex; flex-direction: column;
}
.rb-card::before {
    content: '';
    position: absolute; inset: 0;
    border-radius: 22px;
    padding: 1px;
    background: linear-gradient(135deg, rgba(37,99,235,0.5), rgba(139,92,246,0.4));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    opacity: 0; transition: opacity 0.3s;
    pointer-events: none;
}
.rb-card:hover { transform: translateY(-6px); box-shadow: 0 26px 50px -18px rgba(15,23,42,0.2); border-color: rgba(37,99,235,0.3); }
.rb-card:hover::before { opacity: 1; }
.rb-card-header { display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1rem; }
.rb-card-ico {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #2563eb, #6366f1);
    color: #fff; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 10px 20px rgba(37,99,235,0.28);
}
.rb-card-meta { flex: 1; min-width: 0; }
.rb-card-title { font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.3; }
.rb-card-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.rb-badge { display: inline-flex; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: capitalize; }
.rb-badge--draft { background: #fef3c7; color: #92400e; }
.rb-badge--published { background: #d1fae5; color: #065f46; }
.rb-badge--archived { background: #f1f5f9; color: #475569; }
.rb-badge--default { background: #dbeafe; color: #1e40af; }
.rb-card-summary { font-size: 0.88rem; color: #64748b; line-height: 1.6; margin: 0 0 1.25rem; flex: 1; }
.rb-card-footer { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
.rb-card-date { font-size: 0.78rem; color: #94a3b8; font-weight: 500; }
.rb-card-actions { display: flex; gap: 0.5rem; }
.rb-card-link {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.4rem 0.85rem; border-radius: 10px;
    font-size: 0.8rem; font-weight: 600; text-decoration: none;
    color: #475569; background: #f8fafc; border: 1px solid #e5e7eb;
    transition: all 0.2s;
}
.rb-card-link:hover { background: #f1f5f9; color: #0f172a; }
.rb-card-link--primary { background: #2563eb; color: #fff; border-color: #2563eb; }
.rb-card-link--primary:hover { background: #1d4ed8; color: #fff; }
.rb-card-link--danger { color: #dc2626; background: #fef2f2; border-color: #fecaca; }
.rb-card-link--danger:hover { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }
.rb-card-link:disabled { opacity: 0.6; cursor: not-allowed; }
.rb-spin { animation: rb-spin 0.8s linear infinite; }
@keyframes rb-spin { to { transform: rotate(360deg); } }

/* ---------- EMPTY ---------- */
.rb-empty { text-align: center; padding: 4rem 1rem; }
.rb-empty-visual { position: relative; display: inline-block; margin-bottom: 1.5rem; }
.rb-empty-card {
    width: 120px; height: 150px; background: rgba(255,255,255,0.9);
    border-radius: 18px; border: 2px dashed #cbd5e1;
    display: flex; align-items: center; justify-content: center; color: #cbd5e1;
    position: relative; z-index: 1;
    box-shadow: 0 20px 40px -16px rgba(15,23,42,0.18);
}
.rb-empty-shape { position: absolute; border-radius: 50%; opacity: 0.18; background: #2563eb; }
.rb-empty-shape--1 { width: 84px; height: 84px; top: -22px; right: -32px; }
.rb-empty-shape--2 { width: 54px; height: 54px; bottom: -16px; left: -22px; }
.rb-empty-title { font-family: 'Poppins', sans-serif; font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; }
.rb-empty-text { font-size: 0.95rem; color: #64748b; max-width: 440px; margin: 0 auto 1.5rem; line-height: 1.6; }

/* ---------- TEMPLATES ---------- */
.rb-templates { padding: 1rem 0 4rem; }
.rb-template-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; }
.rb-template-card {
    background: #fff;
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(15,23,42,0.05);
    transition: all 0.35s cubic-bezier(0.22,1,0.36,1);
    display: flex; flex-direction: column;
}
.rb-template-card:hover { transform: translateY(-8px); box-shadow: 0 30px 56px -20px rgba(15,23,42,0.25); border-color: rgba(37,99,235,0.3); }
.rb-template-preview {
    position: relative;
    aspect-ratio: 3 / 4;
    background: linear-gradient(160deg, var(--tone-b, #334155) 0%, var(--tone-a, #2563eb) 100%);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.rb-template-mini {
    width: 58%; max-width: 130px;
    background: #fff; border-radius: 8px; padding: 0.9rem 0.7rem;
    box-shadow: 0 14px 30px rgba(0,0,0,0.25);
    transform: rotate(-4deg);
}
.rb-template-mini-bar { height: 26px; width: 40%; border-radius: 5px; background: var(--tone-a, #2563eb); margin-bottom: 0.6rem; }
.rb-template-mini-line { height: 6px; border-radius: 4px; background: #e2e8f0; margin-bottom: 0.4rem; }
.rb-template-mini-line--lg { height: 8px; width: 80%; }
.rb-template-mini-line--sm { width: 100%; }
.rb-template-mini-line--half { width: 55%; }
.rb-template-mini-chip { height: 14px; width: 42%; border-radius: 999px; background: #eef2ff; margin-top: 0.5rem; }
.rb-template-overlay {
    position: absolute; inset: 0;
    display: flex; align-items: flex-end; justify-content: center;
    padding: 1rem;
    background: linear-gradient(to top, rgba(15,23,42,0.55), transparent 60%);
    opacity: 0; transition: opacity 0.3s;
}
.rb-template-card:hover .rb-template-overlay { opacity: 1; }
.rb-template-meta { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 1.1rem 1.25rem; }
.rb-template-name { font-family: 'Poppins', sans-serif; font-size: 1.02rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem; }
.rb-template-desc { font-size: 0.82rem; color: #64748b; margin: 0; line-height: 1.45; }
.rb-template-swatch {
    width: 34px; height: 34px; border-radius: 11px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--tone-a, #2563eb), var(--tone-b, #334155));
    box-shadow: 0 8px 16px -6px var(--tone-a, #2563eb);
}

@media (max-width: 991.98px) {
    .rb-hero-inner { grid-template-columns: 1fr; }
    .rb-hero-visual { display: none; }
}
@media (max-width: 575.98px) {
    .rb-grid { grid-template-columns: 1fr; }
    .rb-hero { padding: 3.5rem 0 3rem; }
}
</style>
