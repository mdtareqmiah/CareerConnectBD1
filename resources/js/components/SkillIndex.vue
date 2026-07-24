<template>
    <div class="exp-page">
        <section class="exp-header">
            <div class="exp-header-inner">
                <div class="exp-header-content">
                    <nav aria-label="breadcrumb" class="exp-breadcrumb">
                        <ol class="exp-breadcrumb-list">
                            <li class="exp-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="exp-breadcrumb-item exp-breadcrumb-item--active" aria-current="page">Skills</li>
                        </ol>
                    </nav>
                    <div class="exp-title-row">
                        <div class="exp-title-ico"><Brain :size="28" /></div>
                        <div>
                            <span class="exp-pill"><Sparkles :size="14" /> Skill profile</span>
                            <h1 class="exp-title">Skills</h1>
                            <p class="exp-sub">Showcase your professional skills to improve your profile and job opportunities.</p>
                        </div>
                    </div>
                </div>
                <div class="exp-header-ico">
                    <div class="exp-icon-badge"><Puzzle :size="36" /></div>
                    <div class="exp-icon-badge exp-icon-badge--secondary"><Zap :size="28" /></div>
                    <div class="exp-icon-badge exp-icon-badge--tertiary"><Rocket :size="24" /></div>
                </div>
            </div>
        </section>

        <div class="exp-actions">
            <div class="exp-count">
                <span class="exp-count-badge">{{ skills.length }} skill{{ skills.length === 1 ? '' : 's' }}</span>
            </div>
            <a :href="routes.create" class="exp-btn exp-btn-primary"><Plus :size="16" /> Add Skill</a>
        </div>

        <div v-if="!skills.length" class="exp-empty">
            <div class="exp-empty-ico"><Brain :size="32" /></div>
            <h2 class="exp-empty-title">No skills added yet</h2>
            <p class="exp-empty-text">Add your key skills to strengthen your profile and signal fit clearly.</p>
            <a :href="routes.create" class="exp-btn exp-btn-primary"><Plus :size="16" /> Add Skill</a>
        </div>

        <div v-else class="exp-timeline">
            <div v-for="skill in skills" :key="skill.id" class="exp-tl-item">
                <div class="exp-tl-card">
                    <div class="exp-card-head">
                        <div class="exp-card-ico"><Award :size="20" /></div>
                        <div class="exp-card-title-group">
                            <h3 class="exp-card-title">{{ skill.skill_name }}</h3>
                            <span class="exp-badge" :class="badgeClass(skill.proficiency_level)">{{ skill.proficiency_level }}</span>
                        </div>
                    </div>
                    <div class="exp-card-body">
                        <div class="exp-card-row" v-if="skill.years_of_experience">
                            <span class="exp-card-label"><Clock3 :size="14" /> Experience</span>
                            <span class="exp-card-value">{{ skill.years_of_experience }} years</span>
                        </div>
                        <div class="exp-card-row" v-if="skill.notes">
                            <span class="exp-card-label"><FileText :size="14" /> Notes</span>
                            <span class="exp-card-value">{{ skill.notes }}</span>
                        </div>
                    </div>
                    <div class="exp-card-actions">
                        <a :href="editRoute(skill.id)" class="exp-icon-btn" aria-label="Edit"><SquarePen :size="16" /></a>
                        <button type="button" class="exp-icon-btn exp-icon-btn--danger" @click="confirmDelete(skill)" aria-label="Delete"><Trash2 :size="16" /></button>
                    </div>

                    <div v-if="deleteTarget && deleteTarget.id === skill.id" class="exp-modal-overlay" @click.self="deleteTarget = null">
                        <div class="exp-modal">
                            <div class="exp-modal-head">
                                <h3>Delete Skill</h3>
                                <button type="button" class="exp-modal-close" @click="deleteTarget = null">&times;</button>
                            </div>
                            <div class="exp-modal-body">
                                Are you sure you want to delete <strong>{{ deleteTarget.skill_name }}</strong>?
                            </div>
                            <div class="exp-modal-foot">
                                <button type="button" class="exp-btn exp-btn-ghost" @click="deleteTarget = null">Cancel</button>
                                <form :action="deleteRoute(deleteTarget.id)" method="POST" style="display:inline">
                                    <input type="hidden" name="_token" :value="csrfToken">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="exp-btn exp-btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, Brain, Sparkles, Puzzle, Zap, Rocket, Plus, SquarePen, Trash2, Clock3, FileText,
} from '@lucide/vue';

export default {
    name: 'SkillIndex',
    components: { Home, Brain, Sparkles, Puzzle, Zap, Rocket, Plus, SquarePen, Trash2, Clock3, FileText },
    props: {
        skills: { type: Array, default: () => [] },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
    },
    data() {
        return { deleteTarget: null };
    },
    methods: {
        badgeClass(level) {
            const map = {
                Beginner: 'exp-badge--beginner',
                Intermediate: 'exp-badge--intermediate',
                Advanced: 'exp-badge--advanced',
                Expert: 'exp-badge--expert',
            };
            return map[level] || 'exp-badge--intermediate';
        },
        editRoute(id) {
            const pattern = this.routes.edit || '/job-seeker/skills/__ID__/edit';
            return pattern.replace('__ID__', String(id));
        },
        deleteRoute(id) {
            const pattern = this.routes.delete || '/job-seeker/skills/__ID__';
            return pattern.replace('__ID__', String(id));
        },
        confirmDelete(skill) {
            this.deleteTarget = skill;
        },
    },
};
</script>

<style scoped>
.exp-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: exp-fade 0.5s ease both;
}
@keyframes exp-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.exp-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.exp-header::before {
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
.exp-header::after {
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
.exp-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.exp-breadcrumb { margin-bottom: 0.75rem; }
.exp-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.exp-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.exp-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.exp-breadcrumb-item a:hover { color: #fff; }
.exp-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.exp-breadcrumb-item + .exp-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.exp-title-row {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.exp-title-ico {
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
.exp-pill {
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
.exp-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
    line-height: 1.2;
}
.exp-sub {
    color: rgba(255,255,255,0.85);
    margin: 0;
    font-size: 0.95rem;
}
.exp-header-ico {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
}
.exp-icon-badge {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #2dd4bf, #06b6d4);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 28px rgba(45, 212, 191, 0.35);
}
.exp-icon-badge--secondary {
    background: linear-gradient(135deg, #fb923c, #f43f5e);
    box-shadow: 0 12px 28px rgba(251, 146, 60, 0.35);
}
.exp-icon-badge--tertiary {
    background: linear-gradient(135deg, #818cf8, #6366f1);
    box-shadow: 0 12px 28px rgba(99, 102, 241, 0.35);
}

.exp-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.exp-count-badge {
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
.exp-btn {
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
.exp-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 10px 24px rgba(37,99,235,0.25);
}
.exp-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 14px 32px rgba(37,99,235,0.34);
}
.exp-btn-ghost {
    background: #f1f5f9;
    color: #475569;
}
.exp-btn-ghost:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.exp-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 8px 18px rgba(239,68,68,0.25);
}
.exp-btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(239,68,68,0.35);
}

.exp-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 24px;
    box-shadow: 0 10px 30px -20px rgba(37,99,235,0.12);
}
.exp-empty-ico {
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
.exp-empty-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}
.exp-empty-text {
    color: #6b7280;
    margin: 0 0 1.5rem;
    font-size: 0.95rem;
}

.exp-timeline {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.exp-tl-item {
    width: 100%;
}
.exp-tl-card {
    width: 100%;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 8px 24px rgba(15,23,42,0.05);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    animation: exp-fade 0.5s ease both;
}
.exp-tl-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(15,23,42,0.1);
    border-color: rgba(37,99,235,0.3);
}
.exp-card-head {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    margin-bottom: 1rem;
}
.exp-card-ico {
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
.exp-card-title-group { flex: 1; min-width: 0; }
.exp-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1.3;
}
.exp-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    white-space: nowrap;
    margin-top: 0.35rem;
}
.exp-badge--beginner {
    background: rgba(245,158,11,0.12);
    color: #b45309;
}
.exp-badge--intermediate {
    background: rgba(59,130,246,0.12);
    color: #1d4ed8;
}
.exp-badge--advanced {
    background: rgba(16,185,129,0.12);
    color: #059669;
}
.exp-badge--expert {
    background: rgba(139,92,246,0.12);
    color: #7c3aed;
}

.exp-card-body {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.exp-card-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.45rem 0;
    border-bottom: 1px solid #f3f4f6;
}
.exp-card-row:last-of-type {
    border-bottom: 0;
}
.exp-card-label {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #6b7280;
}
.exp-card-label svg { color: #9ca3af; }
.exp-card-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: #111827;
    text-align: right;
}

.exp-card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f3f4f6;
}
.exp-icon-btn {
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
.exp-icon-btn:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
}
.exp-icon-btn--danger:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #dc2626;
}

.exp-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 1rem;
    animation: exp-fade 0.2s ease;
}
.exp-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 24px 60px -20px rgba(15,23,42,0.3);
    overflow: hidden;
}
.exp-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem 0;
}
.exp-modal-head h3 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0;
}
.exp-modal-close {
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
.exp-modal-close:hover {
    background: #f1f5f9;
    color: #111827;
}
.exp-modal-body {
    padding: 1rem 1.5rem 1.25rem;
    color: #4b5563;
    font-size: 0.92rem;
    line-height: 1.6;
}
.exp-modal-foot {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
}

@media (max-width: 767.98px) {
    .exp-header-inner { flex-direction: column; text-align: center; }
    .exp-card-row { flex-direction: column; align-items: flex-start; gap: 0.2rem; }
    .exp-card-value { text-align: left; }
}
</style>
