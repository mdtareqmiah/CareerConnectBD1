<template>
    <nav class="dash-nav">
        <div class="dash-nav-inner">
            <!-- Left: logo + brand -->
            <a :href="homeRoute" class="dash-brand" aria-label="CareerConnectBD home">
                <span v-if="logoUrl" class="dash-brand-logo">
                    <img :src="logoUrl" alt="CareerConnectBD logo">
                </span>
                <span v-else class="dash-brand-mark">CC</span>
                <span class="dash-brand-text">
                    <span class="dash-brand-name">CareerConnectBD</span>
                    <span class="dash-brand-tag">Job Seeker Dashboard</span>
                </span>
            </a>

            <!-- Right: notifications + user -->
            <div class="dash-nav-actions">
                <button class="dash-icon-btn" type="button" aria-label="Notifications" @click="toggleNotify">
                    <Bell :size="20" />
                    <span v-if="unreadCount > 0" class="dash-notify-dot">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>

                    <div v-if="notifyOpen" class="dash-notify-menu" @click.stop>
                        <div class="dash-notify-head">
                            <span>Notifications</span>
                            <span class="dash-notify-count">{{ unreadCount }} new</span>
                        </div>
                        <div v-if="notifications.length" class="dash-notify-list">
                            <a
                                v-for="(n, i) in notifications"
                                :key="i"
                                :href="n.url || '#'"
                                class="dash-notify-item"
                            >
                                <span class="dash-notify-ico"><Bell :size="15" /></span>
                                <span class="dash-notify-body">
                                    <span class="dash-notify-title">{{ n.title || 'Notification' }}</span>
                                    <span class="dash-notify-time">{{ n.time || '' }}</span>
                                </span>
                            </a>
                        </div>
                        <div v-else class="dash-notify-empty">You're all caught up.</div>
                        <a :href="notificationsRoute" class="dash-notify-all">View all notifications</a>
                    </div>
                </button>

                <div class="dash-user">
                    <button class="dash-user-btn" type="button" :aria-expanded="menuOpen" @click="toggleMenu">
                        <img :src="avatarUrl" :alt="userName" class="dash-avatar">
                        <span class="dash-user-name">{{ userName }}</span>
                        <ChevronDown :size="16" class="dash-user-caret" :class="{ 'is-open': menuOpen }" />
                    </button>

                    <div v-if="menuOpen" class="dash-user-menu" @click.stop>
                        <div class="dash-user-head">
                            <img :src="avatarUrl" :alt="userName" class="dash-user-head-avatar">
                            <div>
                                <div class="dash-user-head-name">{{ userName }}</div>
                                <div class="dash-user-head-email">{{ userEmail }}</div>
                            </div>
                        </div>
                        <a :href="dashboardRoute" class="dash-menu-link"><LayoutDashboard :size="16" /> Dashboard</a>
                        <a :href="profileRoute" class="dash-menu-link"><User :size="16" /> Profile</a>
                        <a :href="applicationsRoute" class="dash-menu-link"><FileText :size="16" /> Applications</a>
                        <a :href="savedJobsRoute" class="dash-menu-link"><Bookmark :size="16" /> Saved Jobs</a>
                        <a :href="settingsRoute" class="dash-menu-link"><Settings :size="16" /> Settings</a>
                        <form :action="logoutRoute" method="POST" class="dash-menu-form">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="dash-menu-link dash-menu-logout"><LogOut :size="16" /> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { Bell, ChevronDown, LayoutDashboard, User, FileText, Bookmark, Settings, LogOut } from '@lucide/vue';

export default {
    name: 'DashboardNav',
    components: { Bell, ChevronDown, LayoutDashboard, User, FileText, Bookmark, Settings, LogOut },
    props: {
        userName: { type: String, default: '' },
        userEmail: { type: String, default: '' },
        avatarUrl: { type: String, default: '' },
        logoUrl: { type: String, default: '' },
        unreadCount: { type: Number, default: 0 },
        notifications: { type: Array, default: () => [] },
        routes: { type: Object, default: () => ({}) },
        csrfToken: { type: String, default: '' },
    },
    data() {
        return {
            menuOpen: false,
            notifyOpen: false,
        };
    },
    mounted() {
        document.addEventListener('click', this.onDocClick);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.onDocClick);
    },
    computed: {
        homeRoute() { return this.routes.dashboard || '/job-seeker/dashboard'; },
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
.dash-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(15, 23, 42, 0.07);
    box-shadow: 0 6px 24px -18px rgba(15, 23, 42, 0.5);
}
.dash-nav-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem);
    height: 72px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.dash-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; transition: opacity 0.2s; }
.dash-brand:hover { opacity: 0.85; }
.dash-brand-logo img { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; box-shadow: 0 6px 16px rgba(37, 99, 235, 0.18); }
.dash-brand-mark {
    width: 42px; height: 42px; border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #6366f1);
    color: #fff; font-weight: 800; font-family: 'Poppins', sans-serif;
    display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
}
.dash-brand-text { display: flex; flex-direction: column; line-height: 1.05; }
.dash-brand-name {
    font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.1rem;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
}
.dash-brand-tag { font-size: 0.68rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--color-muted, #6b7280); font-weight: 600; }

.dash-nav-actions { display: flex; align-items: center; gap: 0.6rem; position: relative; }
.dash-icon-btn {
    position: relative; width: 42px; height: 42px; border-radius: 12px;
    border: 1px solid rgba(15, 23, 42, 0.08); background: #fff; color: #475569;
    display: flex; align-items: center; justify-content: center; cursor: pointer;
    transition: all 0.2s;
}
.dash-icon-btn:hover { background: #f1f5f9; color: #2563eb; transform: translateY(-1px); }
.dash-notify-dot {
    position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 4px;
    border-radius: 999px; background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; border: 2px solid #fff;
}

.dash-user { position: relative; }
.dash-user-btn {
    display: flex; align-items: center; gap: 0.55rem; padding: 0.3rem 0.7rem 0.3rem 0.3rem;
    border-radius: 999px; border: 1px solid rgba(15, 23, 42, 0.08); background: #fff; cursor: pointer;
    transition: all 0.2s;
}
.dash-user-btn:hover { background: #f8fafc; box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08); }
.dash-avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.12); }
.dash-user-name { font-size: 0.88rem; font-weight: 600; color: #111827; }
.dash-user-caret { color: #94a3b8; transition: transform 0.2s; }
.dash-user-caret.is-open { transform: rotate(180deg); }

.dash-user-menu, .dash-notify-menu {
    position: absolute; right: 0; top: calc(100% + 0.6rem); min-width: 240px;
    background: #fff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 16px;
    box-shadow: 0 24px 60px -20px rgba(15, 23, 42, 0.28); padding: 0.5rem;
    animation: dash-pop 0.18s ease both; z-index: 50;
}
.dash-notify-menu { min-width: 300px; }
@keyframes dash-pop { from { opacity: 0; transform: translateY(-6px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.dash-user-head { display: flex; gap: 0.6rem; align-items: center; padding: 0.6rem 0.6rem 0.75rem; border-bottom: 1px solid #f1f5f9; margin-bottom: 0.4rem; }
.dash-user-head-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.dash-user-head-name { font-size: 0.9rem; font-weight: 700; color: #111827; }
.dash-user-head-email { font-size: 0.76rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 170px; }

.dash-menu-link {
    display: flex; align-items: center; gap: 0.6rem; width: 100%; text-align: left;
    padding: 0.6rem 0.7rem; border-radius: 10px; font-size: 0.88rem; color: #334155;
    text-decoration: none; background: none; border: 0; cursor: pointer; font-weight: 500;
}
.dash-menu-link:hover { background: #f1f5f9; color: #1d4ed8; }
.dash-menu-form { margin: 0; }
.dash-menu-logout { color: #b91c1c; }
.dash-menu-logout:hover { background: #fef2f2; color: #dc2626; }

.dash-notify-head { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0.6rem 0.6rem; font-weight: 700; color: #111827; border-bottom: 1px solid #f1f5f9; }
.dash-notify-count { font-size: 0.72rem; font-weight: 600; color: #2563eb; background: #eff6ff; padding: 0.15rem 0.5rem; border-radius: 999px; }
.dash-notify-list { max-height: 280px; overflow-y: auto; }
.dash-notify-item { display: flex; gap: 0.6rem; padding: 0.6rem; border-radius: 10px; text-decoration: none; color: #334155; }
.dash-notify-item:hover { background: #f8fafc; }
.dash-notify-ico { width: 30px; height: 30px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.dash-notify-body { display: flex; flex-direction: column; min-width: 0; }
.dash-notify-title { font-size: 0.84rem; font-weight: 600; color: #1f2937; }
.dash-notify-time { font-size: 0.72rem; color: #9ca3af; }
.dash-notify-empty { padding: 1.25rem; text-align: center; color: #9ca3af; font-size: 0.85rem; }
.dash-notify-all { display: block; text-align: center; padding: 0.6rem; font-size: 0.82rem; font-weight: 600; color: #2563eb; text-decoration: none; border-top: 1px solid #f1f5f9; margin-top: 0.3rem; }
.dash-notify-all:hover { color: #1d4ed8; }

@media (max-width: 575.98px) {
    .dash-user-name { display: none; }
    .dash-brand-tag { display: none; }
    .dash-user-btn { padding: 0.25rem; }
}
</style>
