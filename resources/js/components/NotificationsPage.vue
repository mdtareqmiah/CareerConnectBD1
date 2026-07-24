<template>
  <div class="notifications-page">
    <header class="notifications-header">
      <div class="notifications-header-shape notifications-header-shape-1"></div>
      <div class="notifications-header-shape notifications-header-shape-2"></div>
      <div class="notifications-header-shape notifications-header-shape-3"></div>
      <div class="container-xl position-relative">
        <nav class="notifications-breadcrumb mb-4">
          <ol class="breadcrumb notifications-breadcrumb-list">
            <li class="breadcrumb-item notifications-breadcrumb-item">
              <a href="/" class="notifications-breadcrumb-link">
                <Home :size="14" />
                Home
              </a>
            </li>
            <li class="breadcrumb-item notifications-breadcrumb-item active notifications-breadcrumb-current" aria-current="page">
              <Bell :size="14" />
              Notifications
            </li>
          </ol>
        </nav>
        <div class="row align-items-center">
          <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="notifications-header-icon">
                <BellRing :size="32" />
              </div>
              <h1 class="notifications-header-title">Notifications</h1>
            </div>
            <p class="notifications-header-subtitle">
              Keep track of job updates, application status, employer responses, and important account activities.
            </p>
          </div>
          <div class="col-lg-4 d-none d-lg-flex justify-content-lg-end mt-4 mt-lg-0">
            <div class="notifications-header-illustration">
              <div class="notifications-illustration-ring"></div>
              <BellRing :size="80" class="notifications-illustration-icon" />
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="container-xl py-5">
      <div class="notifications-toolbar mb-4">
        <div class="row align-items-center g-3">
          <div class="col-md-6 col-lg-7">
            <div class="notifications-filters">
              <button
                v-for="f in filters"
                :key="f.value"
                type="button"
                class="notifications-filter-btn"
                :class="{ active: currentFilter === f.value }"
                @click="setFilter(f.value)"
              >
                {{ f.label }}
                <span v-if="f.count !== undefined" class="notifications-filter-count">{{ f.count }}</span>
              </button>
            </div>
          </div>
          <div class="col-md-6 col-lg-5 text-md-end">
            <button
              v-if="unreadCount > 0"
              type="button"
              class="notifications-mark-all-btn"
              @click="markAllRead"
              :disabled="markingAllRead"
            >
              <CheckCheck :size="16" />
              {{ markingAllRead ? 'Marking...' : 'Mark all as read' }}
            </button>
          </div>
        </div>
      </div>

      <div class="notifications-list">
        <transition-group name="notif-list" tag="div" class="notif-list-root">
          <div
            v-for="notification in notifications"
            :key="notification.id"
            class="notification-card"
            :class="{ unread: !notification.read_at }"
            :data-notification-id="notification.id"
          >
            <div class="notification-card-inner">
              <div class="notification-icon" :class="getIconClass(notification.data.icon)">
                <component :is="getIconComponent(notification.data.icon)" :size="20" />
              </div>

              <div class="notification-content">
                <div class="notification-header">
                  <h3 class="notification-title">{{ notification.data.title }}</h3>
                  <span class="notification-time">{{ notification.time }}</span>
                </div>
                <p class="notification-message">{{ notification.data.message }}</p>
                <span class="notification-badge" :class="getBadgeClass(notification.data.type)">
                  {{ formatType(notification.data.type) }}
                </span>
              </div>

              <div class="notification-actions">
                <a
                  v-if="notification.data.link"
                  :href="notification.data.link"
                  class="notification-action-btn notification-action-primary"
                >
                  <Eye :size="14" />
                  <span class="d-none d-sm-inline">View</span>
                </a>
                <button
                  v-if="!notification.read_at"
                  type="button"
                  class="notification-action-btn notification-action-secondary"
                  @click="markRead(notification.id)"
                  :disabled="isProcessing(notification.id)"
                >
                  <CircleCheck :size="14" />
                  <span class="d-none d-sm-inline">Mark read</span>
                </button>
                <button
                  type="button"
                  class="notification-action-btn notification-action-danger"
                  @click="deleteNotification(notification.id)"
                  :disabled="isProcessing(notification.id)"
                >
                  <Trash2 :size="14" />
                  <span class="d-none d-sm-inline">Delete</span>
                </button>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <div v-if="notifications.length === 0 && !loading" class="notifications-empty">
        <div class="notifications-empty-icon">
          <Bell :size="56" />
        </div>
        <h3 class="notifications-empty-title">You're all caught up!</h3>
        <p class="notifications-empty-text">
          New notifications will appear here when you have job updates, application responses, or important account activities.
        </p>
      </div>

      <div v-if="loading" class="notifications-loading">
        <div v-for="i in 3" :key="i" class="notification-card skeleton-card">
          <div class="skeleton skeleton-icon"></div>
          <div class="skeleton-flex">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
        </div>
      </div>
    </main>

    <NotificationsFooter :routes="footerRoutes" />
  </div>
</template>

<script>
import {
  Home,
  Bell,
  BellRing,
  Eye,
  Trash2,
  CircleCheck,
  CheckCheck,
  BriefcaseBusiness,
  Mail,
  Calendar,
  UserRound,
  Building2,
  ShieldCheck,
} from '@lucide/vue';

export default {
  name: 'NotificationsPage',
  components: {
    Home,
    Bell,
    BellRing,
    Eye,
    Trash2,
    CircleCheck,
    CheckCheck,
    BriefcaseBusiness,
    Mail,
    Calendar,
    UserRound,
    Building2,
    ShieldCheck,
  },
  props: {
    notifications: { type: Array, default: () => [] },
    routes: { type: Object, default: () => ({}) },
    unreadCount: { type: Number, default: 0 },
    currentFilter: { type: String, default: 'all' },
    csrfToken: { type: String, default: '' },
    authUserId: { type: [Number, String], default: null },
    footerRoutes: { type: Object, default: () => ({}) },
  },
  data() {
    return {
      loading: false,
      markingAllRead: false,
      processingIds: [],
    };
  },
  computed: {
    filters() {
      const total = this.notifications.length;
      const unread = this.notifications.filter((n) => !n.read_at).length;
      const read = total - unread;
      return [
        { label: 'All', value: 'all', count: total },
        { label: 'Unread', value: 'unread', count: unread },
        { label: 'Read', value: 'read', count: read },
      ];
    },
  },
  mounted() {
    if (window.Echo && this.authUserId) {
      this.echoListener = (payload) => {
        this.applyRealtimeUpdate(payload);
      };
      window.Echo.private(`notifications.${this.authUserId}`).listen(
        '.notification.updated',
        this.echoListener
      );
    }
  },
  beforeUnmount() {
    if (window.Echo && this.echoListener && this.authUserId) {
      window.Echo.private(`notifications.${this.authUserId}`).stopListening(
        '.notification.updated',
        this.echoListener
      );
    }
  },
  methods: {
    getIconComponent(iconName) {
      const map = {
        briefcase: 'BriefcaseBusiness',
        'check-circle': 'CircleCheck',
        megaphone: 'ShieldCheck',
        'calendar-event': 'Calendar',
        'file-earmark-text': 'Mail',
        'info-circle': 'ShieldCheck',
      };
      return map[iconName] || 'Bell';
    },
    getIconClass(iconName) {
      const map = {
        briefcase: 'icon-blue',
        'check-circle': 'icon-green',
        megaphone: 'icon-purple',
        'calendar-event': 'icon-teal',
        'file-earmark-text': 'icon-orange',
        'info-circle': 'icon-gray',
      };
      return map[iconName] || 'icon-blue';
    },
    getBadgeClass(type) {
      const map = {
        job_applied: 'badge-blue',
        application_status_changed: 'badge-green',
        employer_posted_job: 'badge-purple',
        resume_reviewed: 'badge-orange',
        admin_announcement: 'badge-red',
        system: 'badge-gray',
        interview_invitation: 'badge-teal',
      };
      return map[type] || 'badge-gray';
    },
    formatType(type) {
      return type
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (l) => l.toUpperCase());
    },
    isProcessing(id) {
      return this.processingIds.includes(id);
    },
    setProcessing(id, val) {
      if (val) {
        if (!this.processingIds.includes(id)) this.processingIds.push(id);
      } else {
        const idx = this.processingIds.indexOf(id);
        if (idx > -1) this.processingIds.splice(idx, 1);
      }
    },
    async markRead(id) {
      this.setProcessing(id, true);
      try {
        const res = await fetch(this.routes.read.replace('__NOTIFICATION_ID__', id), {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': this.csrfToken,
            Accept: 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({}),
        });
        if (res.ok) {
          const n = this.notifications.find((x) => x.id === id);
          if (n) n.read_at = new Date().toISOString();
        }
      } catch (e) {
        console.error(e);
      } finally {
        this.setProcessing(id, false);
      }
    },
    async markAllRead() {
      this.markingAllRead = true;
      try {
        const res = await fetch(this.routes.readAll, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': this.csrfToken,
            Accept: 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({}),
        });
        if (res.ok) {
          this.notifications.forEach((n) => {
            if (!n.read_at) n.read_at = new Date().toISOString();
          });
        }
      } catch (e) {
        console.error(e);
      } finally {
        this.markingAllRead = false;
      }
    },
    async deleteNotification(id) {
      if (!confirm('Are you sure you want to delete this notification?')) return;
      this.setProcessing(id, true);
      try {
        const res = await fetch(this.routes.destroy.replace('__NOTIFICATION_ID__', id), {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': this.csrfToken,
            Accept: 'application/json',
            'Content-Type': 'application/json',
          },
        });
        if (res.ok) {
          const idx = this.notifications.findIndex((x) => x.id === id);
          if (idx > -1) this.notifications.splice(idx, 1);
        }
      } catch (e) {
        console.error(e);
      } finally {
        this.setProcessing(id, false);
      }
    },
    setFilter(filter) {
      window.location.href = `${this.routes.index}?filter=${filter}`;
    },
    applyRealtimeUpdate(payload) {
      if (payload?.kind === 'notification.created' && payload.notification) {
        const n = payload.notification;
        this.notifications.unshift({
          id: n.id,
          data: {
            title: n.title,
            message: n.message,
            link: n.link,
            icon: n.icon || 'bell',
            type: n.type || 'system',
          },
          read_at: n.is_unread ? null : new Date().toISOString(),
          time: n.created_human || 'Just now',
        });
        return;
      }

      if (payload?.action === 'deleted' && payload.notification_id) {
        const idx = this.notifications.findIndex(
          (x) => x.id === payload.notification_id
        );
        if (idx > -1) this.notifications.splice(idx, 1);
      }

      if (payload?.action === 'read' && payload.notification_id) {
        const n = this.notifications.find(
          (x) => x.id === payload.notification_id
        );
        if (n) n.read_at = new Date().toISOString();
      }

      if (payload?.action === 'read_all') {
        this.notifications.forEach((n) => {
          if (!n.read_at) n.read_at = new Date().toISOString();
        });
      }
    },
  },
};
</script>

<style scoped>
.notifications-page {
  animation: notif-fade-in 0.4s ease;
}

@keyframes notif-fade-in {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Header */
.notifications-header {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #0f766e 100%);
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  padding: 2.5rem 0 3rem;
}

.notifications-header-shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  opacity: 0.55;
  pointer-events: none;
}

.notifications-header-shape-1 {
  width: 400px;
  height: 400px;
  background: #60a5fa;
  top: -120px;
  right: -80px;
  animation: float 8s ease-in-out infinite;
}

.notifications-header-shape-2 {
  width: 300px;
  height: 300px;
  background: #14b8a6;
  bottom: -100px;
  left: -60px;
  animation: float 10s ease-in-out infinite reverse;
}

.notifications-header-shape-3 {
  width: 200px;
  height: 200px;
  background: #3b82f6;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: pulse-glow 4s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

@keyframes pulse-glow {
  0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
  50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.1); }
}

.notifications-breadcrumb {
  margin-bottom: 1rem;
}

.notifications-breadcrumb-list {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.85rem;
}

.notifications-breadcrumb-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.notifications-breadcrumb-item + .notifications-breadcrumb-item::before {
  content: '/';
  margin-right: 0.5rem;
  color: #9ca3af;
}

.notifications-breadcrumb-link {
  color: rgba(255, 255, 255, 0.75);
  text-decoration: none;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  transition: color 0.2s;
}

.notifications-breadcrumb-link:hover {
  color: #ffffff;
}

.notifications-breadcrumb-current {
  color: #ffffff;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.notifications-header-icon {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.notifications-header-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 700;
  color: #ffffff;
  margin: 0;
  line-height: 1.2;
}

.notifications-header-subtitle {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.8);
  max-width: 36rem;
  margin: 0;
  line-height: 1.7;
}

.notifications-header-illustration {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 140px;
  height: 140px;
}

.notifications-illustration-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.08);
  border: 2px solid rgba(255, 255, 255, 0.15);
  animation: float 6s ease-in-out infinite;
}

.notifications-illustration-icon {
  color: #ffffff;
  opacity: 0.95;
  position: relative;
  z-index: 1;
}

/* Toolbar */
.notifications-toolbar {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  padding: 1rem 1.25rem;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.notifications-filters {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #f8fafc;
  padding: 0.35rem;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
}

.notifications-filter-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.55rem 1rem;
  border-radius: 12px;
  border: 0;
  background: transparent;
  color: #6b7280;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.notifications-filter-btn:hover {
  color: #374151;
  background: rgba(255, 255, 255, 0.6);
}

.notifications-filter-btn.active {
  background: #ffffff;
  color: #3b82f6;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}

.notifications-filter-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 22px;
  height: 22px;
  padding: 0 6px;
  border-radius: 999px;
  background: rgba(107, 114, 128, 0.1);
  color: #6b7280;
  font-size: 0.72rem;
  font-weight: 700;
}

.notifications-filter-btn.active .notifications-filter-count {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
}

.notifications-mark-all-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.1rem;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  background: #ffffff;
  color: #374151;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.notifications-mark-all-btn:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #3b82f6;
  color: #3b82f6;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.notifications-mark-all-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* List */
.notif-list-root {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.notif-list-enter-active,
.notif-list-leave-active {
  transition: all 0.35s ease;
}

.notif-list-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}

.notif-list-leave-to {
  opacity: 0;
  transform: translateX(20px);
}

/* Card */
.notification-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  padding: 1.25rem;
  box-shadow: 0 2px 12px rgba(15, 23, 42, 0.03);
  transition: all 0.25s ease;
  position: relative;
  overflow: hidden;
}

.notification-card::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: transparent;
  border-radius: 20px 0 0 20px;
  transition: background 0.25s ease;
}

.notification-card.unread::before {
  background: linear-gradient(180deg, #2563eb, #3b82f6);
}

.notification-card.unread {
  background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
  border-color: rgba(37, 99, 235, 0.18);
}

.notification-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
  border-color: rgba(59, 130, 246, 0.2);
}

.notification-card-inner {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding-left: 0.5rem;
}

.notification-icon {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform 0.25s ease;
}

.notification-card:hover .notification-icon {
  transform: scale(1.05);
}

.notification-icon.icon-blue {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
}

.notification-icon.icon-green {
  background: rgba(34, 197, 94, 0.1);
  color: #22c55e;
}

.notification-icon.icon-purple {
  background: rgba(139, 92, 246, 0.1);
  color: #8b5cf6;
}

.notification-icon.icon-teal {
  background: rgba(20, 184, 166, 0.1);
  color: #14b8a6;
}

.notification-icon.icon-orange {
  background: rgba(245, 158, 11, 0.1);
  color: #f59e0b;
}

.notification-icon.icon-gray {
  background: rgba(107, 114, 128, 0.1);
  color: #6b7280;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
  margin-bottom: 0.35rem;
}

.notification-title {
  font-family: 'Poppins', sans-serif;
  font-size: 0.95rem;
  font-weight: 600;
  color: #111827;
  margin: 0;
  line-height: 1.4;
}

.notification-card.unread .notification-title {
  font-weight: 700;
  color: #0f172a;
}

.notification-time {
  font-size: 0.78rem;
  color: #9ca3af;
  white-space: nowrap;
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
}

.notification-message {
  font-size: 0.88rem;
  color: #6b7280;
  margin: 0 0 0.6rem;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.notification-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.7rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.01em;
}

.notification-badge.badge-blue {
  background: rgba(59, 130, 246, 0.08);
  color: #2563eb;
}

.notification-badge.badge-green {
  background: rgba(34, 197, 94, 0.08);
  color: #16a34a;
}

.notification-badge.badge-purple {
  background: rgba(139, 92, 246, 0.08);
  color: #7c3aed;
}

.notification-badge.badge-teal {
  background: rgba(20, 184, 166, 0.08);
  color: #0d9488;
}

.notification-badge.badge-orange {
  background: rgba(245, 158, 11, 0.08);
  color: #d97706;
}

.notification-badge.badge-red {
  background: rgba(239, 68, 68, 0.08);
  color: #dc2626;
}

.notification-badge.badge-gray {
  background: rgba(107, 114, 128, 0.08);
  color: #4b5563;
}

/* Actions */
.notification-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-shrink: 0;
  margin-left: auto;
}

.notification-action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.85rem;
  border-radius: 12px;
  border: 1px solid transparent;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.notification-action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.notification-action-primary {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: #ffffff;
  border-color: transparent;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.notification-action-primary:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(59, 130, 246, 0.3);
  color: #ffffff;
}

.notification-action-secondary {
  background: #ffffff;
  color: #374151;
  border-color: #e5e7eb;
}

.notification-action-secondary:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #3b82f6;
  color: #3b82f6;
  transform: translateY(-1px);
}

.notification-action-danger {
  background: #ffffff;
  color: #dc2626;
  border-color: rgba(239, 68, 68, 0.15);
}

.notification-action-danger:hover:not(:disabled) {
  background: #fef2f2;
  border-color: #ef4444;
  transform: translateY(-1px);
}

/* Empty State */
.notifications-empty {
  text-align: center;
  padding: 4rem 2rem;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}

.notifications-empty-icon {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: rgba(59, 130, 246, 0.08);
  color: #3b82f6;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  animation: float 4s ease-in-out infinite;
}

.notifications-empty-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin: 0 0 0.5rem;
}

.notifications-empty-text {
  font-size: 1rem;
  color: #6b7280;
  max-width: 28rem;
  margin: 0 auto;
  line-height: 1.7;
}

/* Loading */
.notifications-loading {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.skeleton-card {
  pointer-events: none;
}

.skeleton {
  background: linear-gradient(90deg, #f1f5f9, #e2e8f0, #f1f5f9);
  background-size: 200% 100%;
  animation: skeleton-pulse 1.5s infinite ease-in-out;
  border-radius: 0.5rem;
}

.skeleton-icon {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  flex-shrink: 0;
}

.skeleton-flex {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.skeleton-title {
  height: 1.1rem;
  max-width: 60%;
}

.skeleton-text {
  height: 0.9rem;
  max-width: 90%;
}

.skeleton-text.short {
  max-width: 40%;
}

@keyframes skeleton-pulse {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* Responsive */
@media (max-width: 767.98px) {
  .notifications-header {
    padding: 1.75rem 0 2rem;
  }

  .notification-card {
    padding: 1rem;
    border-radius: 16px;
  }

  .notification-card-inner {
    flex-direction: column;
    gap: 0.75rem;
    padding-left: 0;
  }

  .notification-card::before {
    width: 100%;
    height: 4px;
    border-radius: 20px 20px 0 0;
    top: 0;
    left: 0;
    right: 0;
  }

  .notification-actions {
    width: 100%;
    justify-content: flex-start;
    margin-left: 0;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
  }

  .notifications-toolbar {
    padding: 0.85rem 1rem;
    border-radius: 16px;
  }

  .notifications-filters {
    width: 100%;
    justify-content: space-between;
  }

  .notifications-filter-btn {
    flex: 1;
    justify-content: center;
    padding: 0.5rem 0.5rem;
    font-size: 0.82rem;
  }
}

@media (max-width: 575.98px) {
  .notification-card {
    border-radius: 14px;
  }

  .notification-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
  }

  .notification-title {
    font-size: 0.88rem;
  }

  .notification-message {
    font-size: 0.82rem;
  }
}
</style>
