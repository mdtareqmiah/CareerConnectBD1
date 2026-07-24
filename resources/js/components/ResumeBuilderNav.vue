<template>
    <nav class="rb-nav">
        <div class="rb-nav-inner">
            <a :href="homeRoute" class="rb-brand" aria-label="CareerConnectBD home">
                <span v-if="logoUrl" class="rb-brand-logo">
                    <img :src="logoUrl" alt="CareerConnectBD logo">
                </span>
                <span v-else class="rb-brand-mark">CC</span>
                <span class="rb-brand-text">
                    <span class="rb-brand-name">CareerConnectBD</span>
                    <span class="rb-brand-tag">Resume Studio</span>
                </span>
            </a>

            <div class="rb-nav-links">
                <a :href="homeRoute" class="rb-nav-link" :class="{ 'rb-nav-link--active': isHome }">Home</a>
                <a :href="jobsRoute" class="rb-nav-link" :class="{ 'rb-nav-link--active': isJobs }">Jobs</a>
            </div>

            <div class="rb-nav-actions">
                <button class="rb-icon-btn" type="button" aria-label="Notifications" @click="toggleNotify">
                    <Bell :size="18" />
                    <span v-if="unreadCount > 0" class="rb-notify-dot">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>

                    <div v-if="notifyOpen" class="rb-notify-menu" @click.stop>
                        <div class="rb-notify-head">
                            <span>Notifications</span>
                            <span class="rb-notify-count">{{ unreadCount }} new</span>
                        </div>
                        <div v-if="notifications.length" class="rb-notify-list">
                            <a v-for="(n, i) in notifications" :key="i" :href="n.url || '#'" class="rb-notify-item">
                                <span class="rb-notify-ico"><Bell :size="14" /></span>
                                <span class="rb-notify-body">
                                    <span class="rb-notify-title">{{ n.title || 'Notification' }}</span>
                                    <span class="rb-notify-time">{{ n.time || '' }}</span>
                                </span>
                            </a>
                        </div>
                        <div v-else class="rb-notify-empty">You're all caught up.</div>
                        <a :href="notificationsRoute" class="rb-notify-all">View all notifications</a>
                    </div>
                </button>

                <div class="rb-user">
                    <button class="rb-user-btn" type="button" :aria-expanded="menuOpen" @click="toggleMenu">
                        <img :src="avatarUrl" :alt="userName" class="rb-avatar">
                        <span class="rb-user-name">{{ userName }}</span>
                        <ChevronDown :size="16" class="rb-user-caret" :class="{ 'is-open': menuOpen }" />
                    </button>

                    <div v-if="menuOpen" class="rb-user-menu" @click.stop>
                        <div class="rb-user-head">
                            <img :src="avatarUrl" :alt="userName" class="rb-user-head-avatar">
                            <div>
                                <div class="rb-user-head-name">{{ userName }}</div>
                                <div class="rb-user-head-email">{{ userEmail }}</div>
                            </div>
                        </div>
                        <a :href="dashboardRoute" class="rb-menu-link"><LayoutDashboard :size="16" /> Dashboard</a>
                        <a :href="profileRoute" class="rb-menu-link"><User :size="16" /> Profile</a>
                        <a :href="settingsRoute" class="rb-menu-link"><Settings :size="16" /> Settings</a>
                        <form :action="logoutRoute" method="POST" class="rb-menu-form">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="rb-menu-link rb-menu-logout"><LogOut :size="16" /> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { Bell, ChevronDown, LayoutDashboard, User, Settings, LogOut } from '@lucide/vue';

export default {
    name: 'ResumeBuilderNav',
    components: { Bell, ChevronDown, LayoutDashboard, User, Settings, LogOut },
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
        return { menuOpen: false, notifyOpen: false };
    },
    mounted() {
        document.addEventListener('click', this.onDocClick);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.onDocClick);
    },
    computed: {
        homeRoute() { return this.routes.dashboard || '/job-seeker/dashboard'; },
        jobsRoute() { return this.routes.jobs || '/jobs'; },
        dashboardRoute() { return this.routes.dashboard || '/job-seeker/dashboard'; },
        profileRoute() { return this.routes.profile || '/job-seeker/profile'; },
        settingsRoute() { return this.routes.settings || '/profile'; },
        notificationsRoute() { return this.routes.notifications || '/notifications'; },
        logoutRoute() { return this.routes.logout || '/logout'; },
        isHome() { return window.location.pathname === '/' || window.location.pathname === this.homeRoute; },
        isJobs() { return window.location.pathname === this.jobsRoute; },
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
.rb-nav {
    position: sticky;
    top: 18px;
    z-index: 1000;
    margin: 18px clamp(1rem, 3vw, 2.25rem) 0;
    background: rgba(255, 255, 255, 0.82);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 20px;
    box-shadow: 0 18px 50px -22px rgba(15, 23, 42, 0.4);
}
.rb-nav-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0.55rem 0.9rem 0.55rem clamp(1rem, 2vw, 1.4rem);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
}
.rb-brand {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    text-decoration: none;
    transition: opacity 0.2s;
    flex-shrink: 0;
}
.rb-brand:hover { opacity: 0.88; }
.rb-brand-logo img {
    width: 40px; height: 40px; border-radius: 12px; object-fit: cover;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.18);
}
.rb-brand-mark {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #8b5cf6);
    color: #fff; font-weight: 800; font-family: 'Poppins', sans-serif;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
}
.rb-brand-text { display: flex; flex-direction: column; line-height: 1.1; }
.rb-brand-name {
    font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.02rem;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
}
.rb-brand-tag {
    font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase;
    color: #8b5cf6; font-weight: 700;
}
.rb-nav-links { display: flex; align-items: center; gap: 0.35rem; }
.rb-nav-link {
    padding: 0.55rem 1.15rem;
    border-radius: 999px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    color: #475569;
    position: relative;
}
.rb-nav-link:hover { background: rgba(37,99,235,0.07); color: #2563eb; transform: translateY(-1px); }
.rb-nav-link--active {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    color: #fff;
    box-shadow: 0 8px 18px -6px rgba(37,99,235,0.55);
}
.rb-nav-link--active:hover { color: #fff; background: linear-gradient(135deg, #1d4ed8, #4f46e5); }

.rb-nav-actions { display: flex; align-items: center; gap: 0.6rem; position: relative; }
.rb-icon-btn {
    position: relative;
    width: 42px; height: 42px; border-radius: 13px;
    border: 1px solid rgba(15, 23, 42, 0.07);
    background: rgba(255,255,255,0.7); color: #475569;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
}
.rb-icon-btn:hover { background: #fff; color: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 22px -10px rgba(37,99,235,0.45); }
.rb-notify-dot {
    position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 4px;
    border-radius: 999px; background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; border: 2px solid #fff;
}
.rb-user { position: relative; }
.rb-user-btn {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.3rem 0.7rem 0.3rem 0.3rem;
    border-radius: 999px;
    border: 1px solid rgba(15, 23, 42, 0.07);
    background: rgba(255,255,255,0.7); cursor: pointer;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
}
.rb-user-btn:hover { background: #fff; box-shadow: 0 10px 22px -10px rgba(15,23,42,0.18); transform: translateY(-2px); }
.rb-avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.12); }
.rb-user-name { font-size: 0.85rem; font-weight: 600; color: #0f172a; }
.rb-user-caret { color: #94a3b8; transition: transform 0.2s; }
.rb-user-caret.is-open { transform: rotate(180deg); }

.rb-user-menu, .rb-notify-menu {
    position: absolute; right: 0; top: calc(100% + 0.7rem); min-width: 240px;
    background: rgba(255,255,255,0.96);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(15, 23, 42, 0.07); border-radius: 18px;
    box-shadow: 0 28px 64px -20px rgba(15, 23, 42, 0.32); padding: 0.5rem;
    animation: rb-pop 0.18s ease both; z-index: 50;
}
.rb-notify-menu { min-width: 300px; }
@keyframes rb-pop { from { opacity: 0; transform: translateY(-6px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.rb-user-head { display: flex; gap: 0.6rem; align-items: center; padding: 0.6rem 0.6rem 0.75rem; border-bottom: 1px solid #f1f5f9; margin-bottom: 0.4rem; }
.rb-user-head-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
.rb-user-head-name { font-size: 0.88rem; font-weight: 700; color: #0f172a; }
.rb-user-head-email { font-size: 0.74rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 170px; }

.rb-menu-link {
    display: flex; align-items: center; gap: 0.6rem; width: 100%; text-align: left;
    padding: 0.55rem 0.7rem; border-radius: 11px; font-size: 0.86rem; color: #334155;
    text-decoration: none; background: none; border: 0; cursor: pointer; font-weight: 500;
}
.rb-menu-link:hover { background: #f1f5f9; color: #1d4ed8; }
.rb-menu-form { margin: 0; }
.rb-menu-logout { color: #b91c1c; }
.rb-menu-logout:hover { background: #fef2f2; color: #dc2626; }

.rb-notify-head { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0.6rem 0.6rem; font-weight: 700; color: #0f172a; border-bottom: 1px solid #f1f5f9; }
.rb-notify-count { font-size: 0.72rem; font-weight: 600; color: #2563eb; background: #eff6ff; padding: 0.15rem 0.5rem; border-radius: 999px; }
.rb-notify-list { max-height: 280px; overflow-y: auto; }
.rb-notify-item { display: flex; gap: 0.6rem; padding: 0.6rem; border-radius: 11px; text-decoration: none; color: #334155; }
.rb-notify-item:hover { background: #f8fafc; }
.rb-notify-ico { width: 28px; height: 28px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rb-notify-body { display: flex; flex-direction: column; min-width: 0; }
.rb-notify-title { font-size: 0.82rem; font-weight: 600; color: #1f2937; }
.rb-notify-time { font-size: 0.7rem; color: #9ca3af; }
.rb-notify-empty { padding: 1.25rem; text-align: center; color: #9ca3af; font-size: 0.85rem; }
.rb-notify-all { display: block; text-align: center; padding: 0.6rem; font-size: 0.82rem; font-weight: 600; color: #2563eb; text-decoration: none; border-top: 1px solid #f1f5f9; margin-top: 0.3rem; }
.rb-notify-all:hover { color: #1d4ed8; }

@media (max-width: 767.98px) {
    .rb-user-name { display: none; }
    .rb-brand-tag { display: none; }
    .rb-nav-links { display: none; }
}
</style>
