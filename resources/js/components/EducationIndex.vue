<template>
    <div class="edu-page">
        <section class="edu-header">
            <div class="edu-header-inner">
                <div class="edu-header-content">
                    <span class="edu-pill"><GraduationCap :size="14" /> Education</span>
                    <h1 class="edu-title">Education</h1>
                    <p class="edu-sub">Manage your academic background and qualifications.</p>
                </div>
                <div class="edu-header-ico">
                    <div class="edu-ico-circle"><BookOpen :size="28" /></div>
                </div>
            </div>
        </section>

        <div class="edu-actions">
            <div class="edu-count">
                <span class="edu-count-badge">{{ educations.length }} record{{ educations.length === 1 ? '' : 's' }}</span>
            </div>
            <a :href="routes.create" class="edu-btn edu-btn-primary"><Plus :size="16" /> Add Education</a>
        </div>

        <div v-if="!educations.length" class="edu-empty">
            <div class="edu-empty-ico"><GraduationCap :size="32" /></div>
            <h2 class="edu-empty-title">No education records yet</h2>
            <p class="edu-empty-text">Add your degrees and academic history to strengthen your profile.</p>
            <a :href="routes.create" class="edu-btn edu-btn-primary"><Plus :size="16" /> Add Education</a>
        </div>

        <div v-else class="edu-grid">
            <div v-for="edu in educations" :key="edu.id" class="edu-card">
                <div class="edu-card-head">
                    <div class="edu-card-ico"><GraduationCap :size="20" /></div>
                    <div class="edu-card-title-group">
                        <h3 class="edu-card-title">{{ edu.degree }}</h3>
                        <p class="edu-card-sub" v-if="edu.field_of_study">{{ edu.field_of_study }}</p>
                    </div>
                    <span class="edu-badge" :class="edu.is_current ? 'edu-badge--current' : 'edu-badge--completed'">
                        {{ edu.is_current ? 'Currently Studying' : 'Completed' }}
                    </span>
                </div>
                <div class="edu-card-body">
                    <div class="edu-card-row">
                        <span class="edu-card-label"><School :size="14" /> Institution</span>
                        <span class="edu-card-value">{{ edu.institution_name }}</span>
                    </div>
                    <div class="edu-card-row" v-if="edu.board_or_university">
                        <span class="edu-card-label"><BookOpen :size="14" /> Board / University</span>
                        <span class="edu-card-value">{{ edu.board_or_university }}</span>
                    </div>
                    <div class="edu-card-row" v-if="edu.education_level">
                        <span class="edu-card-label"><Award :size="14" /> Level</span>
                        <span class="edu-card-value">{{ edu.education_level }}</span>
                    </div>
                    <div class="edu-card-row" v-if="edu.result">
                        <span class="edu-card-label"><CircleCheck :size="14" /> Result</span>
                        <span class="edu-card-value">{{ edu.result }}<span v-if="edu.grading_system" class="edu-card-meta"> / {{ edu.grading_system }}</span></span>
                    </div>
                    <div class="edu-card-row">
                        <span class="edu-card-label"><Calendar :size="14" /> Passing Year</span>
                        <span class="edu-card-value">{{ edu.passing_year || '—' }}</span>
                    </div>
                    <div class="edu-card-row" v-if="edu.start_date || edu.end_date">
                        <span class="edu-card-label"><Calendar :size="14" /> Duration</span>
                        <span class="edu-card-value">{{ formatDate(edu.start_date) }} — {{ edu.is_current ? 'Present' : formatDate(edu.end_date) }}</span>
                    </div>
                    <div class="edu-card-actions">
                        <a :href="editRoute(edu.id)" class="edu-icon-btn" aria-label="Edit"><SquarePen :size="16" /></a>
                        <button type="button" class="edu-icon-btn edu-icon-btn--danger" @click="confirmDelete(edu)" aria-label="Delete"><Trash2 :size="16" /></button>
                    </div>
                </div>

                <div v-if="deleteTarget && deleteTarget.id === edu.id" class="edu-modal-overlay" @click.self="deleteTarget = null">
                    <div class="edu-modal">
                        <div class="edu-modal-head">
                            <h3>Delete Education</h3>
                            <button type="button" class="edu-modal-close" @click="deleteTarget = null">&times;</button>
                        </div>
                        <div class="edu-modal-body">
                            Are you sure you want to delete <strong>{{ deleteTarget.degree }}</strong>?
                        </div>
                        <div class="edu-modal-foot">
                            <button type="button" class="edu-btn edu-btn-ghost" @click="deleteTarget = null">Cancel</button>
                            <form :action="deleteRoute(deleteTarget.id)" method="POST" style="display:inline">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edu-btn edu-btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { GraduationCap, BookOpen, School, Calendar, Award, Plus, SquarePen, Trash2, CircleCheck } from '@lucide/vue';

export default {
    name: 'EducationIndex',
    components: { GraduationCap, BookOpen, School, Calendar, Award, Plus, SquarePen, Trash2, CircleCheck },
    props: {
        educations: { type: Array, default: () => [] },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
    },
    data() {
        return { deleteTarget: null };
    },
    methods: {
        formatDate(v) {
            if (!v) return '—';
            const s = typeof v === 'string' ? v : (v.date || '');
            if (!s) return '—';
            const d = new Date(s + 'T00:00:00');
            if (isNaN(d)) return '—';
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        editRoute(id) {
            const pattern = this.routes.edit || '/job-seeker/educations/__ID__/edit';
            return pattern.replace('__ID__', String(id));
        },
        deleteRoute(id) {
            const pattern = this.routes.delete || '/job-seeker/educations/__ID__';
            return pattern.replace('__ID__', String(id));
        },
        confirmDelete(edu) {
            this.deleteTarget = edu;
        },
    },
};
</script>

<style scoped>
.edu-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: edu-fade 0.5s ease both;
}
@keyframes edu-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.edu-header {
    background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 50%, #f0fdf4 100%);
    border: 1px solid rgba(37,99,235,0.12);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px -20px rgba(37,99,235,0.15);
}
.edu-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}
.edu-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    background: rgba(37,99,235,0.1);
    border: 1px solid rgba(37,99,235,0.2);
    color: #2563eb;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    margin-bottom: 0.75rem;
}
.edu-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.4rem;
}
.edu-sub {
    color: #6b7280;
    margin: 0;
    font-size: 0.95rem;
}
.edu-ico-circle {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #2563eb, #6366f1);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 28px rgba(37,99,235,0.25);
}

.edu-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.edu-count-badge {
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

.edu-btn {
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
.edu-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.edu-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.edu-btn-ghost {
    background: #f1f5f9;
    color: #475569;
}
.edu-btn-ghost:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.edu-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 8px 18px rgba(239,68,68,0.25);
}
.edu-btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(239,68,68,0.35);
}

.edu-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 24px;
    box-shadow: 0 10px 30px -20px rgba(37,99,235,0.12);
}
.edu-empty-ico {
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
.edu-empty-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}
.edu-empty-text {
    color: #6b7280;
    margin: 0 0 1.5rem;
    font-size: 0.95rem;
}

.edu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}
.edu-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 8px 24px rgba(15,23,42,0.05);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    animation: edu-fade 0.5s ease both;
}
.edu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(15,23,42,0.1);
    border-color: rgba(37,99,235,0.3);
}
.edu-card-head {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    margin-bottom: 1rem;
}
.edu-card-ico {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(245,158,11,0.12);
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.edu-card-title-group { flex: 1; min-width: 0; }
.edu-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1.3;
}
.edu-card-sub {
    font-size: 0.82rem;
    color: #6b7280;
    margin: 0.15rem 0 0;
}

.edu-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    white-space: nowrap;
}
.edu-badge--current {
    background: rgba(16,185,129,0.12);
    color: #059669;
}
.edu-badge--completed {
    background: rgba(107,114,128,0.1);
    color: #4b5563;
}

.edu-card-body {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.edu-card-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.45rem 0;
    border-bottom: 1px solid #f3f4f6;
}
.edu-card-row:last-of-type {
    border-bottom: 0;
}
.edu-card-label {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #6b7280;
}
.edu-card-label svg {
    color: #9ca3af;
}
.edu-card-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: #111827;
    text-align: right;
}
.edu-card-meta {
    color: #6b7280;
    font-weight: 500;
}

.edu-card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f3f4f6;
}
.edu-icon-btn {
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
.edu-icon-btn:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
}
.edu-icon-btn--danger:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #dc2626;
}

.edu-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 1rem;
    animation: edu-fade 0.2s ease;
}
.edu-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 24px 60px -20px rgba(15,23,42,0.3);
    overflow: hidden;
}
.edu-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem 0;
}
.edu-modal-head h3 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0;
}
.edu-modal-close {
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
.edu-modal-close:hover {
    background: #f1f5f9;
    color: #111827;
}
.edu-modal-body {
    padding: 1rem 1.5rem 1.25rem;
    color: #4b5563;
    font-size: 0.92rem;
    line-height: 1.6;
}
.edu-modal-foot {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
}

@media (max-width: 575.98px) {
    .edu-header-inner { flex-direction: column; text-align: center; }
    .edu-grid { grid-template-columns: 1fr; }
    .edu-card-row { flex-direction: column; align-items: flex-start; gap: 0.2rem; }
    .edu-card-value { text-align: left; }
}
</style>
