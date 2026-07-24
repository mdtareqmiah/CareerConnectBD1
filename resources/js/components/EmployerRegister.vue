<template>
    <div class="employer-register-page">
        <!-- Main Content -->
        <div class="employer-register-main flex-grow-1 d-flex">
            <!-- Left Side - Illustration & Features -->
            <div class="employer-left-side">
                <div class="employer-left-bg">
                    <div class="employer-left-orb employer-left-orb--1"></div>
                    <div class="employer-left-orb employer-left-orb--2"></div>
                    <div class="employer-left-orb employer-left-orb--3"></div>
                </div>
                <div class="employer-left-content position-relative">
                    <svg class="employer-illustration" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="500" height="400" rx="24" fill="#EEF2FF"/>
                        <rect x="20" y="20" width="460" height="360" rx="16" fill="#E0E7FF" opacity="0.5"/>
                        <rect x="50" y="60" width="160" height="110" rx="16" fill="white" filter="url(#shadow1)"/>
                        <rect x="56" y="78" width="100" height="10" rx="5" fill="#2563EB" opacity="0.6"/>
                        <rect x="56" y="100" width="148" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="56" y="114" width="120" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="56" y="128" width="60" height="6" rx="3" fill="#F1F5F9"/>
                        <rect x="56" y="148" width="80" height="10" rx="5" fill="#3B82F6"/>
                        <rect x="290" y="60" width="160" height="100" rx="16" fill="white" filter="url(#shadow1)"/>
                        <circle cx="330" cy="95" r="24" fill="#EEF2FF"/>
                        <text x="330" y="100" text-anchor="middle" font-size="22" fill="#2563EB">+</text>
                        <rect x="290" y="210" width="160" height="100" rx="16" fill="white" filter="url(#shadow1)"/>
                        <rect x="306" y="228" width="100" height="8" rx="4" fill="#2563EB" opacity="0.6"/>
                        <rect x="306" y="246" width="128" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="306" y="260" width="100" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="306" y="278" width="50" height="10" rx="5" fill="#10B981"/>
                        <rect x="50" y="240" width="160" height="110" rx="16" fill="white" filter="url(#shadow1)"/>
                        <rect x="66" y="258" width="100" height="8" rx="4" fill="#2563EB" opacity="0.6"/>
                        <rect x="66" y="274" width="128" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="66" y="288" width="90" height="6" rx="3" fill="#CBD5E1"/>
                        <rect x="66" y="306" width="70" height="10" rx="5" fill="#F59E0B"/>
                        <defs>
                            <filter id="shadow1" x="-10%" y="-10%" width="120%" height="120%">
                                <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#2563EB" flood-opacity="0.1"/>
                            </filter>
                        </defs>
                    </svg>

                    <h1 class="employer-left-heading">Hire Exceptional Talent</h1>
                    <p class="employer-left-subtitle">
                        Create your employer account and connect with qualified candidates across Bangladesh.
                    </p>

                    <div class="employer-feature-list">
                        <div class="employer-feature-card">
                            <div class="employer-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                    <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="employer-feature-title">Post Unlimited Jobs</h4>
                                <p class="employer-feature-desc">Create and publish job listings at scale</p>
                            </div>
                        </div>
                        <div class="employer-feature-card">
                            <div class="employer-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 00-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 010 7.75"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="employer-feature-title">Manage Applications</h4>
                                <p class="employer-feature-desc">Track and organize candidate pipelines</p>
                            </div>
                        </div>
                        <div class="employer-feature-card">
                            <div class="employer-feature-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="employer-feature-title">Hire Faster</h4>
                                <p class="employer-feature-desc">Reduce time-to-hire with smart matching</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="employer-right-side">
                <div class="employer-form-card">
                    <Transition name="employer-form" appear>
                        <div class="employer-form-wrapper">
                            <div class="employer-form-header">
                                <div class="employer-form-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <line x1="19" y1="8" x2="19" y2="14"/>
                                        <line x1="22" y1="11" x2="16" y2="11"/>
                                    </svg>
                                </div>
                                <h2 class="employer-form-title">Create Employer Account</h2>
                                <p class="employer-form-subtitle">Join Bangladesh's fastest growing recruitment platform</p>
                            </div>

                            <form method="POST" :action="formAction" novalidate>
                                <input type="hidden" name="_token" :value="csrfToken">

                                <div class="employer-form-fields">
                                    <!-- Name -->
                                    <div class="employer-field-group">
                                        <label for="name" class="employer-field-label">
                                            Full Name <span class="employer-required">*</span>
                                        </label>
                                        <div class="employer-field-input-wrap">
                                            <div class="employer-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </div>
                                            <input id="name" type="text" name="name" required autocomplete="name"
                                                class="employer-field-input"
                                                :class="{ 'employer-field-input--error': errors.name }"
                                                v-model="formName" placeholder="Enter your full name">
                                        </div>
                                        <div v-if="errors.name" class="employer-field-error">{{ errors.name[0] }}</div>
                                    </div>

                                    <!-- Email -->
                                    <div class="employer-field-group">
                                        <label for="email" class="employer-field-label">
                                            Email Address <span class="employer-required">*</span>
                                        </label>
                                        <div class="employer-field-input-wrap">
                                            <div class="employer-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                    <polyline points="22,6 12,13 2,6"/>
                                                </svg>
                                            </div>
                                            <input id="email" type="email" name="email" required autocomplete="username"
                                                class="employer-field-input"
                                                :class="{ 'employer-field-input--error': errors.email }"
                                                v-model="formEmail" placeholder="you@company.com">
                                        </div>
                                        <div v-if="errors.email" class="employer-field-error">{{ errors.email[0] }}</div>
                                    </div>

                                    <!-- Password -->
                                    <div class="employer-field-group">
                                        <label for="password" class="employer-field-label">
                                            Password <span class="employer-required">*</span>
                                        </label>
                                        <div class="employer-field-input-wrap">
                                            <div class="employer-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                                </svg>
                                            </div>
                                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                                class="employer-field-input employer-field-input--with-icon"
                                                :class="{ 'employer-field-input--error': errors.password }"
                                                placeholder="Create a strong password">
                                            <button type="button" class="employer-password-toggle" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                                <EyeOff v-if="showPassword" :size="18" stroke-width="2" />
                                                <Eye v-else :size="18" stroke-width="2" />
                                            </button>
                                        </div>
                                        <div v-if="errors.password" class="employer-field-error">{{ errors.password[0] }}</div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="employer-field-group">
                                        <label for="password_confirmation" class="employer-field-label">
                                            Confirm Password <span class="employer-required">*</span>
                                        </label>
                                        <div class="employer-field-input-wrap">
                                            <div class="employer-field-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                                </svg>
                                            </div>
                                            <input id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                                class="employer-field-input employer-field-input--with-icon"
                                                :class="{ 'employer-field-input--error': errors.password_confirmation }"
                                                placeholder="Confirm your password">
                                            <button type="button" class="employer-password-toggle" @click="showConfirmPassword = !showConfirmPassword" :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                                                <EyeOff v-if="showConfirmPassword" :size="18" stroke-width="2" />
                                                <Eye v-else :size="18" stroke-width="2" />
                                            </button>
                                        </div>
                                        <div v-if="errors.password_confirmation" class="employer-field-error">{{ errors.password_confirmation[0] }}</div>
                                    </div>

                                    <!-- Terms -->
                                    <div class="employer-terms-group">
                                        <div class="employer-checkbox-wrap">
                                            <input id="terms" type="checkbox" name="terms" required
                                                class="employer-checkbox-input"
                                                :class="{ 'employer-checkbox-input--error': errors.terms }"
                                                v-model="termsAccepted">
                                            <label for="terms" class="employer-checkbox-label">
                                                I accept the <a href="#" class="employer-link">terms and conditions</a>
                                            </label>
                                        </div>
                                        <div v-if="errors.terms" class="employer-field-error">{{ errors.terms[0] }}</div>
                                    </div>
                                </div>

                                <button type="submit" class="employer-submit-btn">
                                    <span class="employer-btn-text">Register as Employer</span>
                                </button>

                                <p class="employer-login-text">
                                    Already have an account?
                                    <a :href="loginRoute" class="employer-login-link">Login</a>
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
import { ref, reactive, onMounted } from 'vue';
import { Eye, EyeOff } from '@lucide/vue';

export default {
    name: 'EmployerRegister',
    components: { Eye, EyeOff },
    props: {
        csrfToken: { type: String, default: '' },
        formAction: { type: String, default: '/employer/register' },
        oldName: { type: String, default: '' },
        oldEmail: { type: String, default: '' },
        loginRoute: { type: String, default: '/login' },
        jobsRoute: { type: String, default: '/jobs' },
        errors: { type: Object, default: () => ({}) },
    },
    setup(props) {
        const showPassword = ref(false);
        const showConfirmPassword = ref(false);
        const termsAccepted = ref(false);
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
            termsAccepted,
            formName,
            formEmail,
        };
    },
};
</script>

<style>
.employer-register-page {
    font-family: 'Inter', 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #111827;
    overflow-x: hidden;
    min-height: calc(100vh - 72px);
    padding-top: 72px;
}

/* ===== SPLIT LAYOUT ===== */
.employer-register-main {
    display: flex;
    min-height: calc(100vh - 64px);
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.employer-left-side {
    flex: 0 0 45%;
    background: linear-gradient(160deg, #EEF2FF 0%, #E0E7FF 30%, #F0F9FF 60%, #EFF6FF 100%);
    padding: 48px 40px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.employer-left-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.employer-left-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
}

.employer-left-orb--1 {
    width: 300px;
    height: 300px;
    background: rgba(37, 99, 235, 0.1);
    top: -80px;
    right: -40px;
    animation: float-orb 10s ease-in-out infinite;
}

.employer-left-orb--2 {
    width: 250px;
    height: 250px;
    background: rgba(15, 118, 110, 0.08);
    bottom: -60px;
    left: -20px;
    animation: float-orb 12s ease-in-out infinite reverse;
}

.employer-left-orb--3 {
    width: 180px;
    height: 180px;
    background: rgba(245, 158, 11, 0.06);
    top: 50%;
    left: 30%;
    animation: float-orb 8s ease-in-out infinite;
}

.employer-left-content {
    max-width: 440px;
}

.employer-illustration {
    width: 100%;
    max-width: 440px;
    height: auto;
    margin-bottom: 32px;
    animation: employer-fade-in-up 0.8s ease;
}

.employer-left-heading {
    font-size: clamp(1.8rem, 2.5vw, 2.5rem);
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 16px;
    animation: employer-fade-in-up 0.8s ease 0.1s both;
}

.employer-left-subtitle {
    font-size: 1.05rem;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 32px;
    animation: employer-fade-in-up 0.8s ease 0.2s both;
}

.employer-feature-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.employer-feature-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(37, 99, 235, 0.1);
    border-radius: 16px;
    transition: all 0.25s ease;
    animation: employer-fade-in-up 0.6s ease both;
}

.employer-feature-card:nth-child(1) {
    animation-delay: 0.3s;
}

.employer-feature-card:nth-child(2) {
    animation-delay: 0.4s;
}

.employer-feature-card:nth-child(3) {
    animation-delay: 0.5s;
}

.employer-feature-card:hover {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
    transform: translateX(4px);
}

.employer-feature-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.employer-feature-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.employer-feature-desc {
    font-size: 0.8rem;
    color: #9ca3af;
    margin-bottom: 0;
}

/* ===== RIGHT SIDE - FORM ===== */
.employer-right-side {
    flex: 0 0 55%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: #f8fafc;
}

.employer-form-card {
    width: 100%;
    max-width: 520px;
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(229, 231, 235, 0.8);
    overflow: hidden;
}

.employer-form-wrapper {
    padding: 40px 36px;
}

.employer-form-header {
    text-align: center;
    margin-bottom: 32px;
    animation: employer-fade-in-up 0.6s ease 0.1s both;
}

.employer-form-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.employer-form-title {
    font-size: 1.5rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    color: #111827;
    margin-bottom: 8px;
}

.employer-form-subtitle {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 0;
}

/* ===== FORM FIELDS ===== */
.employer-form-fields {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 24px;
}

.employer-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.employer-field-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 2px;
}

.employer-required {
    color: #ef4444;
    margin-left: 2px;
}

.employer-field-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.employer-field-icon {
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

.employer-field-input {
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

.employer-field-input::placeholder {
    color: #b0b7c3;
}

.employer-field-input:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.employer-field-input--with-icon {
    padding-right: 44px;
}

.employer-field-input--error {
    border-color: #ef4444;
    background: #fef2f2;
}

.employer-field-input--error:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.employer-field-error {
    font-size: 0.8rem;
    color: #ef4444;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.employer-password-toggle {
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

.employer-password-toggle:hover {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.06);
}

/* ===== CHECKBOX ===== */
.employer-terms-group {
    margin-top: 4px;
}

.employer-checkbox-wrap {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.employer-checkbox-input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: #2563eb;
    cursor: pointer;
    border-radius: 4px;
    flex-shrink: 0;
}

.employer-checkbox-input--error {
    border-color: #ef4444;
}

.employer-checkbox-label {
    font-size: 0.85rem;
    color: #6b7280;
    line-height: 1.4;
    cursor: pointer;
}

.employer-link {
    color: #2563eb;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.15s ease;
}

.employer-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* ===== SUBMIT BUTTON ===== */
.employer-submit-btn {
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

.employer-submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
}

.employer-submit-btn:active:not(:disabled) {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
}

.employer-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.employer-btn-text {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.employer-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: employer-spin 0.7s linear infinite;
}

/* ===== LOGIN LINK ===== */
.employer-login-text {
    text-align: center;
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 0;
}

.employer-login-link {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}

.employer-login-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* ===== ANIMATIONS ===== */
@keyframes employer-fade-in-up {
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

@keyframes employer-spin {
    to {
        transform: rotate(360deg);
    }
}

.employer-form-enter-active {
    animation: employer-fade-in-up 0.6s ease;
}

.employer-form-leave-active {
    animation: employer-fade-in-up 0.4s ease reverse;
}

.employer-form-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.employer-form-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991.98px) {
    .employer-register-main {
        flex-direction: column;
    }

    .employer-left-side {
        flex: none;
        padding: 40px 24px 24px;
    }

    .employer-illustration {
        max-width: 320px;
        margin-bottom: 24px;
    }

    .employer-left-heading {
        margin-bottom: 12px;
    }

    .employer-left-subtitle {
        margin-bottom: 24px;
        font-size: 0.95rem;
    }

    .employer-feature-card {
        padding: 12px 16px;
    }

    .employer-right-side {
        flex: none;
        padding: 24px;
        background: #f8fafc;
    }

    .employer-form-card {
        border-radius: 20px;
        max-width: 100%;
    }

    .employer-form-wrapper {
        padding: 28px 24px;
    }
}

@media (max-width: 575.98px) {
    .employer-nav-inner {
        padding: 0 16px;
        height: 56px;
    }

    .employer-brand-text {
        font-size: 0.95rem;
    }

    .employer-left-side {
        padding: 24px 16px 16px;
    }

    .employer-illustration {
        max-width: 260px;
    }

    .employer-right-side {
        padding: 16px;
    }

    .employer-form-wrapper {
        padding: 24px 20px;
    }

    .employer-feature-card {
        padding: 12px 14px;
        gap: 10px;
    }
}
</style>
