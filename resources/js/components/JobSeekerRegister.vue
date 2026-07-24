<template>
    <div class="jobseeker-register-page">
        <div class="jobseeker-register-main flex-grow-1 d-flex">
            <div class="jobseeker-left-side">
                <div class="jobseeker-left-bg">
                    <div class="jobseeker-left-orb jobseeker-left-orb--1"></div>
                    <div class="jobseeker-left-orb jobseeker-left-orb--2"></div>
                    <div class="jobseeker-left-orb jobseeker-left-orb--3"></div>
                </div>
                <div class="jobseeker-left-content position-relative">
                    <svg class="jobseeker-illustration" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="500" height="400" rx="24" fill="#ECFDF5"/>
                        <rect x="20" y="20" width="460" height="360" rx="16" fill="#D1FAE5" opacity="0.6"/>
                        <rect x="50" y="60" width="160" height="120" rx="16" fill="white" filter="url(#shadow2)"/>
                        <circle cx="130" cy="105" r="22" fill="#ECFDF5"/>
                        <path d="M130 97v14M123 105h14" stroke="#10B981" stroke-width="2.5" stroke-linecap="round"/>
                        <rect x="56" y="142" width="148" height="6" rx="3" fill="#A7F3D0"/>
                        <rect x="56" y="156" width="110" height="6" rx="3" fill="#D1FAE5"/>
                        <rect x="56" y="170" width="80" height="10" rx="5" fill="#10B981"/>
                        <rect x="290" y="60" width="160" height="100" rx="16" fill="white" filter="url(#shadow2)"/>
                        <rect x="306" y="78" width="100" height="8" rx="4" fill="#10B981" opacity="0.6"/>
                        <rect x="306" y="96" width="128" height="6" rx="3" fill="#A7F3D0"/>
                        <rect x="306" y="110" width="60" height="10" rx="5" fill="#2563EB"/>
                        <rect x="50" y="240" width="160" height="110" rx="16" fill="white" filter="url(#shadow2)"/>
                        <rect x="66" y="258" width="100" height="8" rx="4" fill="#2563EB" opacity="0.6"/>
                        <rect x="66" y="274" width="128" height="6" rx="3" fill="#A7F3D0"/>
                        <rect x="66" y="288" width="90" height="6" rx="3" fill="#D1FAE5"/>
                        <rect x="66" y="306" width="70" height="10" rx="5" fill="#F59E0B"/>
                        <rect x="290" y="210" width="160" height="100" rx="16" fill="white" filter="url(#shadow2)"/>
                        <rect x="306" y="228" width="100" height="8" rx="4" fill="#10B981" opacity="0.6"/>
                        <rect x="306" y="246" width="128" height="6" rx="3" fill="#D1FAE5"/>
                        <rect x="306" y="260" width="80" height="10" rx="5" fill="#2563EB"/>
                        <defs>
                            <filter id="shadow2" x="-10%" y="-10%" width="120%" height="120%">
                                <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#16A34A" flood-opacity="0.1"/>
                            </filter>
                        </defs>
                    </svg>

                    <h1 class="jobseeker-left-heading">Start Your Career Journey</h1>
                    <p class="jobseeker-left-subtitle">
                        Create your CareerConnectBD account and discover thousands of career opportunities.
                    </p>

                    <div class="jobseeker-feature-list">
                        <div class="jobseeker-feature-card">
                            <div class="jobseeker-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="jobseeker-feature-title">Apply to Jobs Instantly</h4>
                                <p class="jobseeker-feature-desc">One-click applications tailored to your profile</p>
                            </div>
                        </div>
                        <div class="jobseeker-feature-card">
                            <div class="jobseeker-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="jobseeker-feature-title">Build Your Resume</h4>
                                <p class="jobseeker-feature-desc">Create a standout professional resume</p>
                            </div>
                        </div>
                        <div class="jobseeker-feature-card">
                            <div class="jobseeker-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"/>
                                    <line x1="12" y1="20" x2="12" y2="4"/>
                                    <line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="jobseeker-feature-title">Track Applications</h4>
                                <p class="jobseeker-feature-desc">Monitor every application in one place</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="jobseeker-right-side">
                <div class="jobseeker-form-card">
                    <Transition name="jobseeker-form" appear>
                        <div class="jobseeker-form-wrapper">
                            <div class="jobseeker-form-header">
                                <div class="jobseeker-form-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <line x1="19" y1="8" x2="19" y2="14"/>
                                        <line x1="22" y1="11" x2="16" y2="11"/>
                                    </svg>
                                </div>
                                <h2 class="jobseeker-form-title">Create Your Account</h2>
                                <p class="jobseeker-form-subtitle">Join thousands of professionals landing their dream jobs</p>
                            </div>

                            <form method="POST" :action="formAction" novalidate>
                                <input type="hidden" name="_token" :value="csrfToken">

                                <div class="jobseeker-form-fields">
                                    <div class="jobseeker-field-group">
                                        <label for="name" class="jobseeker-field-label">
                                            Full Name <span class="jobseeker-required">*</span>
                                        </label>
                                        <div class="jobseeker-field-input-wrap">
                                            <div class="jobseeker-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </div>
                                            <input id="name" type="text" name="name" required autocomplete="name"
                                                class="jobseeker-field-input"
                                                :class="{ 'jobseeker-field-input--error': errors.name }"
                                                v-model="formName" placeholder="Enter your full name">
                                        </div>
                                        <div v-if="errors.name" class="jobseeker-field-error">{{ errors.name[0] }}</div>
                                    </div>

                                    <div class="jobseeker-field-group">
                                        <label for="email" class="jobseeker-field-label">
                                            Email Address <span class="jobseeker-required">*</span>
                                        </label>
                                        <div class="jobseeker-field-input-wrap">
                                            <div class="jobseeker-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                    <polyline points="22,6 12,13 2,6"/>
                                                </svg>
                                            </div>
                                            <input id="email" type="email" name="email" required autocomplete="username"
                                                class="jobseeker-field-input"
                                                :class="{ 'jobseeker-field-input--error': errors.email }"
                                                v-model="formEmail" placeholder="you@example.com">
                                        </div>
                                        <div v-if="errors.email" class="jobseeker-field-error">{{ errors.email[0] }}</div>
                                    </div>

                                    <div class="jobseeker-field-group">
                                        <label for="password" class="jobseeker-field-label">
                                            Password <span class="jobseeker-required">*</span>
                                        </label>
                                        <div class="jobseeker-field-input-wrap">
                                            <div class="jobseeker-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                                </svg>
                                            </div>
                                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                                class="jobseeker-field-input jobseeker-field-input--with-icon"
                                                :class="{ 'jobseeker-field-input--error': errors.password }"
                                                placeholder="Create a strong password">
                                            <button type="button" class="jobseeker-password-toggle" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                                <EyeOff v-if="showPassword" :size="18" stroke-width="2" />
                                                <Eye v-else :size="18" stroke-width="2" />
                                            </button>
                                        </div>
                                        <div v-if="errors.password" class="jobseeker-field-error">{{ errors.password[0] }}</div>
                                    </div>

                                    <div class="jobseeker-field-group">
                                        <label for="password_confirmation" class="jobseeker-field-label">
                                            Confirm Password <span class="jobseeker-required">*</span>
                                        </label>
                                        <div class="jobseeker-field-input-wrap">
                                            <div class="jobseeker-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                                </svg>
                                            </div>
                                            <input id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                                class="jobseeker-field-input jobseeker-field-input--with-icon"
                                                :class="{ 'jobseeker-field-input--error': errors.password_confirmation }"
                                                placeholder="Confirm your password">
                                            <button type="button" class="jobseeker-password-toggle" @click="showConfirmPassword = !showConfirmPassword" :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                                                <EyeOff v-if="showConfirmPassword" :size="18" stroke-width="2" />
                                                <Eye v-else :size="18" stroke-width="2" />
                                            </button>
                                        </div>
                                        <div v-if="errors.password_confirmation" class="jobseeker-field-error">{{ errors.password_confirmation[0] }}</div>
                                    </div>
                                </div>

                                <button type="submit" class="jobseeker-submit-btn">
                                    <span class="jobseeker-btn-text">Create Account</span>
                                </button>

                                <p class="jobseeker-login-text">
                                    Already have an account?
                                    <a :href="loginRoute" class="jobseeker-login-link">Login</a>
                                </p>
                            </form>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { Eye, EyeOff } from '@lucide/vue';

export default {
    name: 'JobSeekerRegister',
    components: { Eye, EyeOff },
    props: {
        csrfToken: { type: String, default: '' },
        formAction: { type: String, default: '/register' },
        oldName: { type: String, default: '' },
        oldEmail: { type: String, default: '' },
        loginRoute: { type: String, default: '/login' },
        jobsRoute: { type: String, default: '/jobs' },
        errors: { type: Object, default: () => ({}) },
    },
    setup(props) {
        const showPassword = ref(false);
        const showConfirmPassword = ref(false);
        const formName = ref(props.oldName);
        const formEmail = ref(props.oldEmail);

        onMounted(() => {
            const nameInput = document.getElementById('name');
            if (nameInput) {
                setTimeout(() => nameInput.focus(), 100);
            }
        });

        return {
            showPassword,
            showConfirmPassword,
            formName,
            formEmail,
        };
    },
};
</script>

<style>
.jobseeker-register-page {
    font-family: 'Inter', 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #111827;
    overflow-x: hidden;
    min-height: calc(100vh - 72px);
    padding-top: 72px;
}

.jobseeker-register-main {
    display: flex;
    min-height: calc(100vh - 72px);
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.jobseeker-left-side {
    flex: 0 0 45%;
    background: linear-gradient(160deg, #ECFDF5 0%, #D1FAE5 30%, #F0FDF4 60%, #EFF6FF 100%);
    padding: 48px 40px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.jobseeker-left-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.jobseeker-left-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
}

.jobseeker-left-orb--1 {
    width: 300px;
    height: 300px;
    background: rgba(16, 185, 129, 0.12);
    top: -80px;
    right: -40px;
    animation: float-orb 10s ease-in-out infinite;
}

.jobseeker-left-orb--2 {
    width: 250px;
    height: 250px;
    background: rgba(37, 99, 235, 0.08);
    bottom: -60px;
    left: -20px;
    animation: float-orb 12s ease-in-out infinite reverse;
}

.jobseeker-left-orb--3 {
    width: 180px;
    height: 180px;
    background: rgba(245, 158, 11, 0.08);
    top: 50%;
    left: 30%;
    animation: float-orb 8s ease-in-out infinite;
}

.jobseeker-left-content {
    max-width: 440px;
}

.jobseeker-illustration {
    width: 100%;
    max-width: 440px;
    height: auto;
    margin-bottom: 32px;
    animation: jobseeker-fade-in-up 0.8s ease;
}

.jobseeker-left-heading {
    font-size: clamp(1.8rem, 2.5vw, 2.5rem);
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 16px;
    animation: jobseeker-fade-in-up 0.8s ease 0.1s both;
}

.jobseeker-left-subtitle {
    font-size: 1.05rem;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 32px;
    animation: jobseeker-fade-in-up 0.8s ease 0.2s both;
}

.jobseeker-feature-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.jobseeker-feature-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(16, 185, 129, 0.15);
    border-radius: 16px;
    transition: all 0.25s ease;
    animation: jobseeker-fade-in-up 0.6s ease both;
}

.jobseeker-feature-card:nth-child(1) {
    animation-delay: 0.3s;
}

.jobseeker-feature-card:nth-child(2) {
    animation-delay: 0.4s;
}

.jobseeker-feature-card:nth-child(3) {
    animation-delay: 0.5s;
}

.jobseeker-feature-card:hover {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.1);
    transform: translateX(4px);
}

.jobseeker-feature-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.jobseeker-feature-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.jobseeker-feature-desc {
    font-size: 0.8rem;
    color: #9ca3af;
    margin-bottom: 0;
}

.jobseeker-right-side {
    flex: 0 0 55%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: #f8fafc;
}

.jobseeker-form-card {
    width: 100%;
    max-width: 520px;
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(229, 231, 235, 0.8);
    overflow: hidden;
}

.jobseeker-form-wrapper {
    padding: 40px 36px;
}

.jobseeker-form-header {
    text-align: center;
    margin-bottom: 32px;
    animation: jobseeker-fade-in-up 0.6s ease 0.1s both;
}

.jobseeker-form-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(16, 185, 129, 0.08);
    color: #10B981;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.jobseeker-form-title {
    font-size: 1.5rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    color: #111827;
    margin-bottom: 8px;
}

.jobseeker-form-subtitle {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 0;
}

.jobseeker-form-fields {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 24px;
}

.jobseeker-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.jobseeker-field-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 2px;
}

.jobseeker-required {
    color: #ef4444;
    margin-left: 2px;
}

.jobseeker-field-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.jobseeker-field-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    display: flex;
    align-items: center;
    z-index: 1;
}

.jobseeker-field-input {
    width: 100%;
    height: 48px;
    padding: 10px 14px 10px 44px;
    font-size: 0.9rem;
    font-family: 'Inter', sans-serif;
    color: #111827;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    outline: none;
    transition: all 0.2s ease;
}

.jobseeker-field-input::placeholder {
    color: #b0b7c3;
}

.jobseeker-field-input:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.jobseeker-field-input--with-icon {
    padding-right: 44px;
}

.jobseeker-field-input--error {
    border-color: #ef4444;
    background: #fef2f2;
}

.jobseeker-field-input--error:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.jobseeker-field-error {
    font-size: 0.8rem;
    color: #ef4444;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.jobseeker-password-toggle {
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: #9ca3af;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    z-index: 1;
}

.jobseeker-password-toggle:hover {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.06);
}

.jobseeker-submit-btn {
    width: 100%;
    height: 50px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    color: white;
    font-size: 0.95rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.jobseeker-submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
}

.jobseeker-submit-btn:active:not(:disabled) {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
}

.jobseeker-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.jobseeker-btn-text {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.jobseeker-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: jobseeker-spin 0.7s linear infinite;
}

.jobseeker-login-text {
    text-align: center;
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 0;
}

.jobseeker-login-link {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}

.jobseeker-login-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

@keyframes jobseeker-fade-in-up {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float-orb {
    0%, 100% {
        transform: translate(0, 0);
    }
    33% {
        transform: translate(15px, -20px);
    }
    66% {
        transform: translate(-10px, 15px);
    }
}

@keyframes jobseeker-spin {
    to {
        transform: rotate(360deg);
    }
}

.jobseeker-form-enter-active {
    animation: jobseeker-fade-in-up 0.6s ease;
}

.jobseeker-form-leave-active {
    animation: jobseeker-fade-in-up 0.4s ease reverse;
}

.jobseeker-form-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.jobseeker-form-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

@media (max-width: 991.98px) {
    .jobseeker-register-main {
        flex-direction: column;
    }

    .jobseeker-left-side {
        flex: none;
        padding: 40px 24px 24px;
    }

    .jobseeker-illustration {
        max-width: 320px;
        margin-bottom: 24px;
    }

    .jobseeker-left-heading {
        margin-bottom: 12px;
    }

    .jobseeker-left-subtitle {
        margin-bottom: 24px;
        font-size: 0.95rem;
    }

    .jobseeker-feature-card {
        padding: 12px 16px;
    }

    .jobseeker-right-side {
        flex: none;
        padding: 24px;
        background: #f8fafc;
    }

    .jobseeker-form-card {
        border-radius: 20px;
        max-width: 100%;
    }

    .jobseeker-form-wrapper {
        padding: 28px 24px;
    }
}

@media (max-width: 575.98px) {
    .jobseeker-left-side {
        padding: 24px 16px 16px;
    }

    .jobseeker-illustration {
        max-width: 260px;
    }

    .jobseeker-right-side {
        padding: 16px;
    }

    .jobseeker-form-wrapper {
        padding: 24px 20px;
    }

    .jobseeker-feature-card {
        padding: 12px 14px;
        gap: 10px;
    }
}
</style>
