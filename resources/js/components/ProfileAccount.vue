<template>
    <div class="profile-page">
        <section class="profile-header">
            <div class="profile-header-inner">
                <div class="profile-header-content">
                    <nav aria-label="breadcrumb" class="profile-breadcrumb">
                        <ol class="profile-breadcrumb-list">
                            <li class="profile-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="14" /> Dashboard</a>
                            </li>
                            <li class="profile-breadcrumb-item profile-breadcrumb-item--active" aria-current="page">Profile Settings</li>
                        </ol>
                    </nav>
                    <div class="profile-title-row">
                        <div class="profile-avatar-wrap">
                            <img :src="user.avatar_url || defaultAvatar" alt="Profile" class="profile-avatar">
                            <span class="profile-avatar-ring"></span>
                        </div>
                        <div>
                            <span class="profile-pill"><User :size="14" /> Account settings</span>
                            <h1 class="profile-title">{{ user.name }}</h1>
                            <p class="profile-sub">{{ user.email }}</p>
                        </div>
                    </div>
                </div>
                <div class="profile-header-ico">
                    <div class="profile-ico-badge"><ShieldCheck :size="36" /></div>
                </div>
            </div>
        </section>

        <div class="profile-grid">
            <div class="profile-card">
                <div class="profile-card-head">
                    <div class="profile-card-ico"><User :size="20" /></div>
                    <div>
                        <h2 class="profile-card-title">Profile Information</h2>
                        <p class="profile-card-sub">Update your account's profile information and email address.</p>
                    </div>
                </div>
                <form @submit.prevent="submitProfile" class="profile-form">
                    <div class="profile-field" :class="{ 'profile-field--error': errors.name }">
                        <label class="profile-label" for="name"><User :size="14" /> Name <span class="profile-req">*</span></label>
                        <input id="name" v-model="form.name" type="text" class="profile-input" required autofocus>
                        <div v-if="errors.name" class="profile-error">{{ errors.name[0] }}</div>
                    </div>
                    <div class="profile-field" :class="{ 'profile-field--error': errors.email }">
                        <label class="profile-label" for="email"><Mail :size="14" /> Email <span class="profile-req">*</span></label>
                        <input id="email" v-model="form.email" type="email" class="profile-input" required>
                        <div v-if="errors.email" class="profile-error">{{ errors.email[0] }}</div>
            <div v-if="!user.email_verified_at" class="profile-verify">
                <p class="profile-verify-text">Your email address is unverified.</p>
                <button type="button" class="profile-link" @click="sendVerification">Click here to re-send the verification email.</button>
                <p v-if="verificationSent" class="profile-verify-success">A new verification link has been sent to your email address.</p>
            </div>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" :disabled="profileSaving" class="profile-btn profile-btn-primary">
                            <Save :size="15" /> {{ profileSaving ? 'Saving...' : 'Save' }}
                        </button>
                        <span v-if="profileStatus === 'profile-updated'" class="profile-success">Saved.</span>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="profile-card-head">
                    <div class="profile-card-ico"><Lock :size="20" /></div>
                    <div>
                        <h2 class="profile-card-title">Update Password</h2>
                        <p class="profile-card-sub">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                </div>
                <form @submit.prevent="submitPassword" class="profile-form">
                    <div class="profile-field" :class="{ 'profile-field--error': errors.current_password }">
                        <label class="profile-label" for="current_password"><Lock :size="14" /> Current Password</label>
                        <input id="current_password" v-model="passwordForm.current_password" type="password" class="profile-input">
                        <div v-if="errors.current_password" class="profile-error">{{ errors.current_password[0] }}</div>
                    </div>
                    <div class="profile-field" :class="{ 'profile-field--error': errors.password }">
                        <label class="profile-label" for="password"><Lock :size="14" /> New Password</label>
                        <input id="password" v-model="passwordForm.password" type="password" class="profile-input">
                        <div v-if="errors.password" class="profile-error">{{ errors.password[0] }}</div>
                    </div>
                    <div class="profile-field" :class="{ 'profile-field--error': errors.password_confirmation }">
                        <label class="profile-label" for="password_confirmation"><Lock :size="14" /> Confirm Password</label>
                        <input id="password_confirmation" v-model="passwordForm.password_confirmation" type="password" class="profile-input">
                        <div v-if="errors.password_confirmation" class="profile-error">{{ errors.password_confirmation[0] }}</div>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" :disabled="passwordSaving" class="profile-btn profile-btn-primary">
                            <Save :size="15" /> {{ passwordSaving ? 'Saving...' : 'Save' }}
                        </button>
                        <span v-if="passwordStatus === 'password-updated'" class="profile-success">Saved.</span>
                    </div>
                </form>
            </div>

            <div class="profile-card profile-card--danger">
                <div class="profile-card-head">
                    <div class="profile-card-ico profile-card-ico--danger"><Trash2 :size="20" /></div>
                    <div>
                        <h2 class="profile-card-title">Delete Account</h2>
                        <p class="profile-card-sub">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                    </div>
                </div>
                <div class="profile-actions">
                    <button type="button" @click="openDeleteModal" class="profile-btn profile-btn-danger">
                        <Trash2 :size="15" /> Delete Account
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showDeleteModal" class="profile-modal-overlay" @click.self="showDeleteModal = false">
            <div class="profile-modal">
                <div class="profile-modal-head">
                    <h3>Delete Account</h3>
                    <button type="button" class="profile-modal-close" @click="showDeleteModal = false">&times;</button>
                </div>
                <div class="profile-modal-body">
                    <p>Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>
                    <div class="profile-field" :class="{ 'profile-field--error': errors.password_delete }">
                        <label class="profile-label" for="password_delete">Password</label>
                        <input id="password_delete" v-model="deleteForm.password" type="password" class="profile-input">
                        <div v-if="errors.password_delete" class="profile-error">{{ errors.password_delete[0] }}</div>
                    </div>
                </div>
                <div class="profile-modal-foot">
                    <button type="button" class="profile-btn profile-btn-ghost" @click="showDeleteModal = false">Cancel</button>
                    <button type="button" :disabled="deleteSaving" @click="submitDelete" class="profile-btn profile-btn-danger">
                        <Trash2 :size="15" /> {{ deleteSaving ? 'Deleting...' : 'Delete Account' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Home, User, Mail, Lock, ShieldCheck, Save, Trash2,
} from '@lucide/vue';

export default {
    name: 'ProfileAccount',
    components: { Home, User, Mail, Lock, ShieldCheck, Save, Trash2 },
    props: {
        user: { type: Object, default: () => ({}) },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
    },
    data() {
        return {
            form: { name: this.user.name || '', email: this.user.email || '' },
            passwordForm: { current_password: '', password: '', password_confirmation: '' },
            deleteForm: { password: '' },
            errors: {},
            profileSaving: false,
            passwordSaving: false,
            deleteSaving: false,
            profileStatus: null,
            passwordStatus: null,
            showDeleteModal: false,
            verificationSent: false,
        };
    },
    computed: {
        defaultAvatar() {
            return '/images/default-avatar.svg';
        },
    },
    methods: {
        async submitProfile() {
            this.profileSaving = true;
            this.errors = {};
            try {
                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                formData.append('_method', 'PATCH');
                formData.append('name', this.form.name);
                formData.append('email', this.form.email);

                const res = await fetch(this.routes.update || '/profile', { method: 'POST', body: formData });
                if (!res.ok) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                    return;
                }
                this.profileStatus = 'profile-updated';
                setTimeout(() => { this.profileStatus = null; }, 3000);
            } catch (e) {
                this.errors = { general: ['Something went wrong.'] };
            } finally {
                this.profileSaving = false;
            }
        },
        async submitPassword() {
            this.passwordSaving = true;
            this.errors = {};
            try {
                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                formData.append('_method', 'PUT');
                formData.append('current_password', this.passwordForm.current_password);
                formData.append('password', this.passwordForm.password);
                formData.append('password_confirmation', this.passwordForm.password_confirmation);

                const res = await fetch(this.routes.password || '/password', { method: 'POST', body: formData });
                if (!res.ok) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                    return;
                }
                this.passwordStatus = 'password-updated';
                this.passwordForm = { current_password: '', password: '', password_confirmation: '' };
                setTimeout(() => { this.passwordStatus = null; }, 3000);
            } catch (e) {
                this.errors = { general: ['Something went wrong.'] };
            } finally {
                this.passwordSaving = false;
            }
        },
        openDeleteModal() {
            this.showDeleteModal = true;
            this.deleteForm.password = '';
            this.errors = {};
        },
        async submitDelete() {
            this.deleteSaving = true;
            this.errors = {};
            try {
                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                formData.append('_method', 'DELETE');
                formData.append('password', this.deleteForm.password);

                const res = await fetch(this.routes.destroy || '/profile', { method: 'POST', body: formData });
                if (!res.ok) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                    return;
                }
                window.location.href = '/';
            } catch (e) {
                this.errors = { general: ['Something went wrong.'] };
            } finally {
                this.deleteSaving = false;
            }
        },
        async sendVerification() {
            this.verificationSent = false;
            try {
                const formData = new FormData();
                formData.append('_token', this.csrfToken);

                const res = await fetch(this.routes.verification || '/email/verification-notification', { method: 'POST', body: formData });
                if (res.ok) {
                    this.verificationSent = true;
                    setTimeout(() => { this.verificationSent = false; }, 4000);
                }
            } catch (e) {
                // silent
            }
        },
    },
};
</script>

<style scoped>
.profile-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #111827;
    animation: profile-fade 0.5s ease both;
}
@keyframes profile-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.profile-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 24px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
    position: relative;
    overflow: hidden;
}
.profile-header::before {
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
.profile-header::after {
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
.profile-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}
.profile-breadcrumb { margin-bottom: 0.75rem; }
.profile-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
}
.profile-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.profile-breadcrumb-item a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    transition: color 0.2s;
}
.profile-breadcrumb-item a:hover { color: #fff; }
.profile-breadcrumb-item--active { color: rgba(255,255,255,0.55); }
.profile-breadcrumb-item + .profile-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.profile-title-row {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.profile-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.profile-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.profile-avatar-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.2);
    pointer-events: none;
}
.profile-pill {
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
    margin-bottom: 0.6rem;
}
.profile-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.2rem;
}
.profile-sub {
    color: rgba(255,255,255,0.8);
    margin: 0;
    font-size: 0.9rem;
}
.profile-header-ico {
    flex-shrink: 0;
}
.profile-ico-badge {
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

.profile-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
.profile-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 8px 24px rgba(15,23,42,0.05);
    transition: all 0.3s ease;
}
.profile-card:hover {
    box-shadow: 0 12px 32px rgba(15,23,42,0.08);
}
.profile-card--danger {
    border-color: #fecaca;
}
.profile-card-head {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    margin-bottom: 1.25rem;
}
.profile-card-ico {
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
.profile-card-ico--danger {
    background: rgba(239,68,68,0.1);
    color: #dc2626;
}
.profile-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}
.profile-card-sub {
    font-size: 0.84rem;
    color: #6b7280;
    margin: 0.2rem 0 0;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.profile-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.profile-field--error .profile-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
}
.profile-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
}
.profile-label svg { color: #2563eb; }
.profile-req { color: #ef4444; }
.profile-input {
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
.profile-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
    transform: translateY(-1px);
}
.profile-error {
    font-size: 0.76rem;
    color: #ef4444;
    font-weight: 500;
}
.profile-success {
    font-size: 0.84rem;
    color: #059669;
    font-weight: 600;
}
.profile-verify {
    margin-top: 0.5rem;
    padding: 0.75rem;
    border-radius: 10px;
    background: #fef3c7;
    border: 1px solid #fde68a;
}
.profile-verify-text {
    font-size: 0.84rem;
    color: #92400e;
    margin: 0 0 0.25rem;
}
.profile-link {
    font-size: 0.82rem;
    color: #2563eb;
    text-decoration: underline;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
}
.profile-link:hover { color: #1d4ed8; }
.profile-verify-success {
    font-size: 0.82rem;
    color: #059669;
    font-weight: 600;
    margin: 0.25rem 0 0;
}

.profile-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.5rem;
}
.profile-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1.4rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.88rem;
    border: 0;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap;
}
.profile-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 8px 20px rgba(37,99,235,0.25);
}
.profile-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 12px 28px rgba(37,99,235,0.35);
}
.profile-btn-ghost {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e7eb;
}
.profile-btn-ghost:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #d1d5db;
}
.profile-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 8px 18px rgba(239,68,68,0.25);
}
.profile-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(239,68,68,0.35);
}

.profile-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 1rem;
    animation: profile-fade 0.2s ease;
}
.profile-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 24px 60px -20px rgba(15,23,42,0.3);
    overflow: hidden;
}
.profile-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem 0;
}
.profile-modal-head h3 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0;
}
.profile-modal-close {
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
.profile-modal-close:hover {
    background: #f1f5f9;
    color: #111827;
}
.profile-modal-body {
    padding: 1rem 1.5rem 1.25rem;
    color: #4b5563;
    font-size: 0.92rem;
    line-height: 1.6;
}
.profile-modal-foot {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
}

@media (max-width: 767.98px) {
    .profile-header-inner { flex-direction: column; text-align: center; }
    .profile-title-row { flex-direction: column; }
}
</style>
