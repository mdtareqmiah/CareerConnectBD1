<template>
    <nav class="pnav">
        <div class="pnav-inner">
            <a :href="homeRoute" class="pnav-brand" aria-label="CareerConnectBD home">
                <span v-if="logoUrl" class="pnav-logo"><img :src="logoUrl" alt="CareerConnectBD logo"></span>
                <span v-else class="pnav-mark">CC</span>
                <span class="pnav-brand-text">CareerConnectBD</span>
            </a>

            <div class="pnav-links">
                <a href="/" class="pnav-link" :class="{ 'is-active': current === 'home' }">Home</a>
                <a :href="jobsRoute" class="pnav-link" :class="{ 'is-active': current === 'jobs' }">Jobs</a>
            </div>

            <div class="pnav-actions">
                <button class="pnav-icon" type="button" aria-label="Notifications" @click="toggleNotify">
                    <Bell :size="20" />
                    <span v-if="unreadCount > 0" class="pnav-dot">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
                    <div v-if="notifyOpen" class="pnav-notify" @click.stop>
                        <div class="pnav-notify-head">
                            <span>Notifications</span>
                            <span class="pnav-notify-count">{{ unreadCount }} new</span>
                        </div>
                        <div v-if="notifications.length" class="pnav-notify-list">
                            <a v-for="(n, i) in notifications" :key="i" :href="n.url || '#'" class="pnav-notify-item">
                                <span class="pnav-notify-ico"><Bell :size="15" /></span>
                                <span class="pnav-notify-body">
                                    <span class="pnav-notify-title">{{ n.title || 'Notification' }}</span>
                                    <span class="pnav-notify-time">{{ n.time || '' }}</span>
                                </span>
                            </a>
                        </div>
                        <div v-else class="pnav-notify-empty">You're all caught up.</div>
                        <a :href="notificationsRoute" class="pnav-notify-all">View all notifications</a>
                    </div>
                </button>

                <a :href="settingsRoute" class="pnav-icon" aria-label="Settings">
                    <Settings :size="20" />
                </a>

                <div class="pnav-user">
                    <button class="pnav-user-btn" type="button" :aria-expanded="menuOpen" @click="toggleMenu">
                        <img :src="avatarUrl" :alt="userName" class="pnav-avatar">
                        <span class="pnav-user-name">{{ userName }}</span>
                        <ChevronDown :size="16" class="pnav-caret" :class="{ 'is-open': menuOpen }" />
                    </button>
                    <div v-if="menuOpen" class="pnav-menu" @click.stop>
                        <div class="pnav-menu-head">
                            <img :src="avatarUrl" :alt="userName" class="pnav-menu-avatar">
                            <div>
                                <div class="pnav-menu-name">{{ userName }}</div>
                                <div class="pnav-menu-email">{{ userEmail }}</div>
                            </div>
                        </div>
                        <a :href="dashboardRoute" class="pnav-menu-link"><LayoutDashboard :size="16" /> Dashboard</a>
                        <a :href="profileRoute" class="pnav-menu-link"><User :size="16" /> Edit Profile</a>
                        <a :href="applicationsRoute" class="pnav-menu-link"><FileText :size="16" /> Applications</a>
                        <a :href="savedJobsRoute" class="pnav-menu-link"><Bookmark :size="16" /> Saved Jobs</a>
                        <a :href="settingsRoute" class="pnav-menu-link"><Settings :size="16" /> Settings</a>
                        <form :action="logoutRoute" method="POST" class="pnav-menu-form">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="pnav-menu-link pnav-menu-logout"><LogOut :size="16" /> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { Bell, Settings, ChevronDown, LayoutDashboard, User, FileText, Bookmark, LogOut } from '@lucide/vue';

export default {
    name: 'ProfileNav',
    components: { Bell, Settings, ChevronDown, LayoutDashboard, User, FileText, Bookmark, LogOut },
    props: {
        userName: { type: String, default: '' },
        userEmail: { type: String, default: '' },
        avatarUrl: { type: String, default: '' },
        logoUrl: { type: String, default: '' },
        unreadCount: { type: Number, default: 0 },
        notifications: { type: Array, default: () => [] },
        current: { type: String, default: '' },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
    },
    data() { return { menuOpen: false, notifyOpen: false }; },
    mounted() { document.addEventListener('click', this.onDocClick); },
    beforeUnmount() { document.removeEventListener('click', this.onDocClick); },
    computed: {
        homeRoute() { return this.routes.home || '/'; },
        jobsRoute() { return this.routes.jobs || '/jobs'; },
        dashboardRoute() { return this.routes.dashboard || '/job-seeker/dashboard'; },
        profileRoute() { return this.routes.profile || '/job-seeker/profile'; },
        applicationsRoute() { return this.routes.applications || '/job-seeker/applications'; },
        savedJobsRoute() { return this.routes.savedJobs || '/job-seeker/saved-jobs'; },
        settingsRoute() { return this.routes.settings || '/profile'; },
        notificationsRoute() { return this.routes.notifications || '/notifications'; },
        logoutRoute() { return this.routes.logout || '/logout'; },
    },
    methods: {
        toggleMenu() { this.menuOpen = !this.menuOpen; this.notifyOpen = false; },
        toggleNotify() { this.notifyOpen = !this.notifyOpen; this.menuOpen = false; },
        onDocClick(e) {
            if (this.menuOpen && this.$el && !this.$el.contains(e.target)) this.menuOpen = false;
            if (this.notifyOpen && this.$el && !this.$el.contains(e.target)) this.notifyOpen = false;
        },
    },
};
</script>

<style scoped>
.pnav {
    position: sticky; top: 0; z-index: 1000;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(15,23,42,0.07);
    box-shadow: 0 8px 28px -22px rgba(15,23,42,0.5);
}
.pnav-inner {
    max-width: 1320px; margin: 0 auto; padding: 0 clamp(1rem, 3vw, 2.25rem);
    height: 72px; display: flex; align-items: center; justify-content: space-between; gap: 1rem;
}
.pnav-brand { display: flex; align-items: center; gap: 0.7rem; text-decoration: none; transition: opacity 0.2s; }
.pnav-brand:hover { opacity: 0.85; }
.pnav-logo img { width: 40px; height: 40px; border-radius: 11px; object-fit: cover; box-shadow: 0 6px 16px rgba(37,99,235,0.18); }
.pnav-mark {
    width: 40px; height: 40px; border-radius: 11px; background: linear-gradient(135deg, #2563eb, #6366f1);
    color: #fff; font-weight: 800; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; font-size: 1rem;
    box-shadow: 0 8px 18px rgba(37,99,235,0.25);
}
.pnav-brand-text { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.1rem;
    background: linear-gradient(135deg, #2563eb, #4f46e5); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }

.pnav-links { display: flex; align-items: center; gap: 0.25rem; }
.pnav-link { padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.9rem; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.2s; }
.pnav-link:hover { background: #f1f5f9; color: #1d4ed8; }
.pnav-link.is-active { background: #eff6ff; color: #2563eb; }

.pnav-actions { display: flex; align-items: center; gap: 0.5rem; position: relative; }
.pnav-icon {
    position: relative; width: 42px; height: 42px; border-radius: 12px; border: 1px solid rgba(15,23,42,0.08);
    background: #fff; color: #475569; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;
}
.pnav-icon:hover { background: #f1f5f9; color: #2563eb; transform: translateY(-1px); }
.pnav-dot {
    position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 4px;
    border-radius: 999px; background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; border: 2px solid #fff;
}

.pnav-user { position: relative; }
.pnav-user-btn { display: flex; align-items: center; gap: 0.5rem; padding: 0.3rem 0.7rem 0.3rem 0.3rem;
    border-radius: 999px; border: 1px solid rgba(15,23,42,0.08); background: #fff; cursor: pointer; transition: all 0.2s; }
.pnav-user-btn:hover { background: #f8fafc; box-shadow: 0 6px 16px rgba(15,23,42,0.08); }
.pnav-avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.12); }
.pnav-user-name { font-size: 0.88rem; font-weight: 600; color: #111827; }
.pnav-caret { color: #94a3b8; transition: transform 0.2s; }
.pnav-caret.is-open { transform: rotate(180deg); }

.pnav-menu, .pnav-notify {
    position: absolute; right: 0; top: calc(100% + 0.6rem); min-width: 240px;
    background: #fff; border: 1px solid rgba(15,23,42,0.08); border-radius: 16px;
    box-shadow: 0 24px 60px -20px rgba(15,23,42,0.28); padding: 0.5rem; animation: pnav-pop 0.18s ease both; z-index: 50;
}
.pnav-notify { min-width: 300px; }
@keyframes pnav-pop { from { opacity: 0; transform: translateY(-6px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.pnav-menu-head { display: flex; gap: 0.6rem; align-items: center; padding: 0.6rem 0.6rem 0.75rem; border-bottom: 1px solid #f1f5f9; margin-bottom: 0.4rem; }
.pnav-menu-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.pnav-menu-name { font-size: 0.9rem; font-weight: 700; color: #111827; }
.pnav-menu-email { font-size: 0.76rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 170px; }
.pnav-menu-link { display: flex; align-items: center; gap: 0.6rem; width: 100%; text-align: left; padding: 0.6rem 0.7rem;
    border-radius: 10px; font-size: 0.88rem; color: #334155; text-decoration: none; background: none; border: 0; cursor: pointer; font-weight: 500; }
.pnav-menu-link:hover { background: #f1f5f9; color: #1d4ed8; }
.pnav-menu-form { margin: 0; }
.pnav-menu-logout { color: #b91c1c; }
.pnav-menu-logout:hover { background: #fef2f2; color: #dc2626; }

.pnav-notify-head { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0.6rem 0.6rem; font-weight: 700; color: #111827; border-bottom: 1px solid #f1f5f9; }
.pnav-notify-count { font-size: 0.72rem; font-weight: 600; color: #2563eb; background: #eff6ff; padding: 0.15rem 0.5rem; border-radius: 999px; }
.pnav-notify-list { max-height: 280px; overflow-y: auto; }
.pnav-notify-item { display: flex; gap: 0.6rem; padding: 0.6rem; border-radius: 10px; text-decoration: none; color: #334155; }
.pnav-notify-item:hover { background: #f8fafc; }
.pnav-notify-ico { width: 30px; height: 30px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.pnav-notify-body { display: flex; flex-direction: column; min-width: 0; }
.pnav-notify-title { font-size: 0.84rem; font-weight: 600; color: #1f2937; }
.pnav-notify-time { font-size: 0.72rem; color: #9ca3af; }
.pnav-notify-empty { padding: 1.25rem; text-align: center; color: #9ca3af; font-size: 0.85rem; }
.pnav-notify-all { display: block; text-align: center; padding: 0.6rem; font-size: 0.82rem; font-weight: 600; color: #2563eb; text-decoration: none; border-top: 1px solid #f1f5f9; margin-top: 0.3rem; }
.pnav-notify-all:hover { color: #1d4ed8; }

@media (max-width: 767.98px) {
    .pnav-links { display: none; }
    .pnav-user-name { display: none; }
    .pnav-user-btn { padding: 0.25rem; }
}
</style>
