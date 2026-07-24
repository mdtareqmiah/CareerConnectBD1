<template>
  <div class="jas">
    <!-- HERO -->
    <section class="jas-hero">
      <div class="jas-hero-shapes" aria-hidden="true">
        <span class="jas-shape jas-shape--1"></span>
        <span class="jas-shape jas-shape--2"></span>
        <span class="jas-shape jas-shape--3"></span>
      </div>
      <div class="jas-hero-inner">
        <div class="jas-hero-left">
          <nav aria-label="breadcrumb" class="jas-crumbs">
            <ol class="jas-crumbs-list">
              <li class="jas-crumbs-item"><a :href="routes.home || '/'">Home</a></li>
              <li class="jas-crumbs-item"><a :href="routes.jobs || '/jobs'">Jobs</a></li>
              <li class="jas-crumbs-item jas-crumbs-item--active" aria-current="page">Apply</li>
            </ol>
          </nav>
          <span class="jas-pill"><FileText :size="14" /> Your Application</span>
          <h1 class="jas-hero-title">Apply for This Job</h1>
          <p class="jas-hero-sub">Take your time, fill in the details, and send it with confidence.</p>

          <div class="jas-hero-meta">
            <div class="jas-meta-card">
              <div class="jas-meta-ico jas-meta-ico--blue"><Building2 :size="20" /></div>
              <div class="jas-meta-body">
                <div class="jas-meta-label">Company</div>
                <div class="jas-meta-val">{{ job.company?.company_name || 'CareerConnectBD' }}</div>
              </div>
            </div>
            <div class="jas-meta-card">
              <div class="jas-meta-ico jas-meta-ico--cyan"><BriefcaseBusiness :size="20" /></div>
              <div class="jas-meta-body">
                <div class="jas-meta-label">Position</div>
                <div class="jas-meta-val">{{ job.title }}</div>
              </div>
            </div>
            <div class="jas-meta-card" v-if="job.location || job.workplace">
              <div class="jas-meta-ico jas-meta-ico--emerald"><MapPin :size="20" /></div>
              <div class="jas-meta-body">
                <div class="jas-meta-label">Location</div>
                <div class="jas-meta-val">{{ job.location || (job.workplace === 'remote' ? 'Remote' : job.workplace === 'hybrid' ? 'Hybrid' : 'On-site') }}</div>
              </div>
            </div>
            <div class="jas-meta-card" v-if="job.employment_type">
              <div class="jas-meta-ico jas-meta-ico--amber"><Clock3 :size="20" /></div>
              <div class="jas-meta-body">
                <div class="jas-meta-label">Employment Type</div>
                <div class="jas-meta-val">{{ formatEmploymentType(job.employment_type) }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="jas-hero-right">
          <div class="jas-hero-illo">
            <div class="jas-illo-card">
              <div class="jas-illo-card-top">
                <div class="jas-illo-avatar"></div>
                <div class="jas-illo-lines">
                  <div class="jas-illo-line jas-illo-line--title"></div>
                  <div class="jas-illo-line jas-illo-line--short"></div>
                </div>
              </div>
              <div class="jas-illo-card-body">
                <div class="jas-illo-line"></div>
                <div class="jas-illo-line jas-illo-line--80"></div>
                <div class="jas-illo-line jas-illo-line--60"></div>
                <div class="jas-illo-chips">
                  <span class="jas-illo-chip"></span>
                  <span class="jas-illo-chip"></span>
                  <span class="jas-illo-chip"></span>
                </div>
              </div>
              <div class="jas-illo-badge jas-illo-badge--1"><FileText :size="18" /></div>
              <div class="jas-illo-badge jas-illo-badge--2"><UserCheck :size="18" /></div>
              <div class="jas-illo-badge jas-illo-badge--3"><Send :size="18" /></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- WORKSPACE -->
    <section class="jas-workspace">
      <div class="jas-workspace-inner">
        <!-- LEFT: FORM -->
        <div class="jas-form-col">
          <div class="jas-card">
            <div class="jas-card-header">
              <h2 class="jas-card-title">Application Details</h2>
              <p class="jas-card-sub">Fill in the required information below.</p>
            </div>
            <div class="jas-card-body">
              <div v-if="successMessage" class="jas-alert jas-alert--success" role="status">
                <div class="jas-alert-head"><CircleCheck :size="18" /> Success</div>
                <div class="jas-alert-body">{{ successMessage }}</div>
              </div>
              <div v-if="generalError" class="jas-alert jas-alert--danger" role="alert">
                <div class="jas-alert-head"><AlertCircle :size="18" /> Something went wrong</div>
                <div class="jas-alert-body">{{ generalError }}</div>
              </div>
              <form method="POST" :action="storeUrl" enctype="multipart/form-data" @submit.prevent="submitForm">
                <input type="hidden" name="_token" :value="formCsrfToken" />
                <input type="hidden" name="job_id" :value="formJobId" />

                <!-- Resume Select -->
                <div class="jas-field">
                  <label class="jas-label">
                    <span class="jas-label-icon"><FileText :size="16" /></span>
                    Resume <span class="text-danger">*</span>
                  </label>

                  <div class="jas-resume-options">
                    <div class="jas-resume-option">
                      <span class="jas-resume-option-title">Choose from saved resumes</span>
                      <div class="jas-select-wrap">
                        <select id="resume_id" name="resume_id" class="jas-select" :class="{ 'jas-input--error': validationErrors.resume_id || errors.resume_id }" v-model="form.resume_id" :required="!form.resume_file">
                          <option value="">Select your resume</option>
                          <option v-for="resume in resumes" :key="resume.id" :value="resume.id">
                            {{ resume.title }}
                          </option>
                        </select>
                        <div class="jas-select-icon"><ChevronDown :size="18" /></div>
                      </div>
                    </div>

                    <div class="jas-resume-divider">
                      <span>or</span>
                    </div>

                    <div class="jas-resume-option">
                      <span class="jas-resume-option-title">Upload from your device</span>
                      <label class="jas-file-upload">
                        <input type="file" name="resume_file" accept=".pdf,.doc,.docx" @change="handleFileUpload" class="jas-file-input" />
                        <div class="jas-file-upload-area" :class="{ 'jas-file-upload-area--has-file': form.resume_file }">
                          <Upload :size="24" />
                          <span class="jas-file-upload-text">{{ form.resume_file ? form.resume_file.name : 'Click to choose a file' }}</span>
                          <span class="jas-file-upload-hint">PDF, DOC, DOCX up to 5MB</span>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div v-if="validationErrors.resume_id || errors.resume_id" class="jas-error">{{ validationErrors.resume_id || errors.resume_id }}</div>
                  <div v-if="validationErrors.resume_file || errors.resume_file" class="jas-error">{{ validationErrors.resume_file || errors.resume_file }}</div>
                </div>

                <!-- Cover Letter -->
                <div class="jas-field">
                  <label for="cover_letter" class="jas-label">
                    <span class="jas-label-icon"><FileText :size="16" /></span>
                    Cover Letter <span class="text-danger">*</span>
                  </label>
                   <textarea id="cover_letter" name="cover_letter" rows="8" class="jas-textarea" required minlength="50" :class="{ 'jas-input--error': validationErrors.cover_letter || errors.cover_letter }" v-model="form.cover_letter" placeholder="Tell the employer why you are a great fit..."></textarea>
                  <div class="jas-hint">Minimum 50 characters; maximum 3000 characters.</div>
                  <div v-if="validationErrors.cover_letter || errors.cover_letter" class="jas-error">{{ validationErrors.cover_letter || errors.cover_letter }}</div>
                </div>

                <!-- Actions -->
                <div class="jas-form-actions">
                  <a :href="backUrl" class="jas-btn jas-btn--ghost">
                    <ExternalLink :size="18" />
                    Back to Job
                  </a>
                  <button type="submit" class="jas-btn jas-btn--primary" :disabled="submitting || (!form.resume_id && !form.resume_file)">
                    <span v-if="submitting" class="jas-spinner"></span>
                    <Send :size="18" />
                    {{ submitting ? 'Submitting...' : 'Submit Application' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- RIGHT: STICKY PANEL -->
        <aside class="jas-side-col">
          <div class="jas-side-sticky">
            <!-- Job Summary -->
            <div class="jas-card jas-card--summary">
              <div class="jas-card-header">
                <h3 class="jas-card-title jas-card-title--sm">Job Summary</h3>
              </div>
              <div class="jas-summary-list">
                <div class="jas-summary-item">
                  <span class="jas-summary-icon"><Building2 :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Company</div>
                    <div class="jas-summary-value">{{ job.company?.company_name || 'CareerConnectBD' }}</div>
                  </div>
                </div>
                <div class="jas-summary-item">
                  <span class="jas-summary-icon"><BriefcaseBusiness :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Position</div>
                    <div class="jas-summary-value">{{ job.title }}</div>
                  </div>
                </div>
                <div class="jas-summary-item" v-if="job.workplace">
                  <span class="jas-summary-icon"><MapPin :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Workplace Type</div>
                    <div class="jas-summary-value">{{ formatWorkplace(job.workplace) }}</div>
                  </div>
                </div>
                <div class="jas-summary-item" v-if="job.employment_type">
                  <span class="jas-summary-icon"><Clock3 :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Employment Type</div>
                    <div class="jas-summary-value">{{ formatEmploymentType(job.employment_type) }}</div>
                  </div>
                </div>
                <div class="jas-summary-item" v-if="job.salary_range || job.salary_min || job.salary_max">
                  <span class="jas-summary-icon"><FileText :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Salary Range</div>
                    <div class="jas-summary-value">{{ formatSalary() }}</div>
                  </div>
                </div>
                <div class="jas-summary-item" v-if="job.deadline">
                  <span class="jas-summary-icon"><Calendar :size="16" /></span>
                  <div class="jas-summary-content">
                    <div class="jas-summary-label">Deadline</div>
                    <div class="jas-summary-value">{{ formatDate(job.deadline) }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Checklist -->
            <div class="jas-card jas-card--checklist">
              <div class="jas-card-header">
                <h3 class="jas-card-title jas-card-title--sm">Application Checklist</h3>
              </div>
              <div class="jas-checklist">
                <div class="jas-checklist-item">
                  <span class="jas-check-icon"><FileText :size="18" /></span>
                  <span>Resume attached</span>
                </div>
                <div class="jas-checklist-item">
                  <span class="jas-check-icon"><UserCheck :size="18" /></span>
                  <span>Profile completed</span>
                </div>
                <div class="jas-checklist-item">
                  <span class="jas-check-icon"><ShieldCheck :size="18" /></span>
                  <span>Review your information</span>
                </div>
                <div class="jas-checklist-item">
                  <span class="jas-check-icon"><CircleCheck :size="18" /></span>
                  <span>Double-check before submission</span>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </div>
</template>

<script>
import {
  Sparkles,
  Building2,
  BriefcaseBusiness,
  MapPin,
  Clock3,
  FileText,
  Calendar,
  UserCheck,
  Send,
  CircleCheck,
  ChevronDown,
  Upload,
  ExternalLink,
  ShieldCheck,
  AlertCircle,
} from '@lucide/vue';

export default {
  name: 'JobApplicationStudio',
  components: {
    Sparkles,
    Building2,
    BriefcaseBusiness,
    MapPin,
    Clock3,
    FileText,
    Calendar,
    UserCheck,
    Send,
    CircleCheck,
    ChevronDown,
    Upload,
    ExternalLink,
    ShieldCheck,
    AlertCircle,
  },
  props: {
    csrfToken: { type: String, default: '' },
    job: { type: Object, default: () => ({}) },
    resumes: { type: Array, default: () => [] },
    routes: { type: Object, default: () => ({}) },
    errors: { type: Object, default: () => ({}) },
  },
  data() {
    return {
      submitting: false,
      successMessage: '',
      form: {
        resume_id: '',
        cover_letter: '',
        resume_file: null,
      },
      validationErrors: {},
      generalError: '',
      formCsrfToken: this.csrfToken,
      formJobId: this.job?.id,
    };
  },
  mounted() {
    const defaultResume = this.resumes.find((r) => r.is_default);
    if (defaultResume) {
      this.form.resume_id = defaultResume.id;
    }
  },
  computed: {
    backUrl() {
      if (this.routes.show) return this.routes.show;
      if (this.job?.id) return '/jobs/' + this.job.id;
      return '/jobs';
    },
    storeUrl() {
      if (this.routes.store) return this.routes.store;
      return '/job-applications';
    },
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files && event.target.files[0];
      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          this.generalError = 'Resume file must be smaller than 5MB.';
          event.target.value = '';
          this.form.resume_file = null;
          return;
        }
        this.form.resume_file = file;
        if (this.form.resume_id) {
          this.form.resume_id = '';
        }
      } else {
        this.form.resume_file = null;
      }
    },
    submitForm(event) {
      const form = event.target;
      const formData = new FormData(form);

      this.submitting = true;
      this.successMessage = '';
      this.generalError = '';
      this.validationErrors = {};

      fetch(this.storeUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      })
      .then(async (response) => {
        const data = await response.json();

        if (!response.ok) {
          if (response.status === 422 && data.errors) {
            this.validationErrors = data.errors;
          } else {
            this.generalError = data.message || 'Something went wrong. Please try again.';
          }
          return;
        }

        this.handleSuccessfulSubmission(data.message || 'Application submitted successfully.');
      })
      .catch(() => {
        this.generalError = 'Network error. Please check your connection and try again.';
      })
      .finally(() => {
        this.submitting = false;
      });
    },
    handleSuccessfulSubmission(message) {
      const applicationsUrl = this.buildApplicationsUrl();

      try {
        sessionStorage.setItem('jobApplicationsFlashMessage', message);
      } catch (error) {
        // ignore storage failures
      }

      if (applicationsUrl) {
        window.location.href = applicationsUrl;
        return;
      }

      this.successMessage = message;
    },
    buildApplicationsUrl() {
      const rawUrl = this.routes.applications || '/job-seeker/applications';

      try {
        const url = new URL(rawUrl, window.location.origin);
        url.searchParams.set('submitted', '1');
        return url.toString();
      } catch (error) {
        const separator = rawUrl.includes('?') ? '&' : '?';
        return `${rawUrl}${separator}submitted=1`;
      }
    },
    formatEmploymentType(value) {
      if (!value) return '';
      return value
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
    },
    formatWorkplace(value) {
      if (!value) return '';
      return value
        .replace(/-/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
    },
    formatSalary() {
      const parts = [];
      if (this.job.salary_min) parts.push(this.job.salary_min);
      if (this.job.salary_max) parts.push(this.job.salary_max);
      if (this.job.salary_currency) parts.push(this.job.salary_currency);
      if (this.job.salary_period) parts.push(this.job.salary_period);
      return parts.join(' ') || 'Competitive';
    },
    formatDate(value) {
      if (!value) return '';
      return new Date(value).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      });
    },
  },
};
</script>

<style>
.jas {
  opacity: 1;
  transform: none;
}

/* HERO */
.jas-hero {
  position: relative;
  overflow: hidden;
  background: linear-gradient(160deg, #0f172a 0%, #1e293b 35%, #1e3a8a 100%);
  padding: 3rem 0 3.5rem;
}

.jas-hero-shapes {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
}

.jas-shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.4;
}

.jas-shape--1 {
  width: 400px;
  height: 400px;
  background: #3b82f6;
  top: -100px;
  right: -100px;
}

.jas-shape--2 {
  width: 300px;
  height: 300px;
  background: #6366f1;
  bottom: -80px;
  left: -80px;
}

.jas-shape--3 {
  width: 250px;
  height: 250px;
  background: #1d4ed8;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.jas-hero-inner {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0 2rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
  position: relative;
  z-index: 1;
}

.jas-crumbs {
  margin-bottom: 1rem;
}

.jas-crumbs-list {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.85rem;
}

.jas-crumbs-item {
  color: #94a3b8;
}

.jas-crumbs-item a {
  color: #94a3b8;
  text-decoration: none;
  transition: color 0.2s ease;
}

.jas-crumbs-item a:hover {
  color: #cbd5e1;
}

.jas-crumbs-item--active {
  color: #e2e8f0;
  font-weight: 600;
}

.jas-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: rgba(37, 99, 235, 0.12);
  border: 1px solid rgba(37, 99, 235, 0.25);
  border-radius: 999px;
  color: #93c5fd;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.jas-hero-title {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 700;
  color: #ffffff;
  line-height: 1.2;
  margin-bottom: 1rem;
}

.jas-hero-sub {
  font-size: 1.05rem;
  color: #cbd5e1;
  line-height: 1.7;
  max-width: 36rem;
  margin-bottom: 2rem;
}

.jas-hero-meta {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.75rem;
}

.jas-meta-card {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.85rem 1rem;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 1rem;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.jas-meta-card:hover {
  background: rgba(255, 255, 255, 0.07);
  border-color: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

.jas-meta-ico {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 0.75rem;
  flex-shrink: 0;
}

.jas-meta-ico--blue { background: rgba(37, 99, 235, 0.12); color: #60a5fa; }
.jas-meta-ico--cyan { background: rgba(6, 182, 212, 0.12); color: #22d3ee; }
.jas-meta-ico--emerald { background: rgba(16, 185, 129, 0.12); color: #34d399; }
.jas-meta-ico--amber { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }

.jas-meta-body {
  min-width: 0;
}

.jas-meta-label {
  font-size: 0.75rem;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 600;
  margin-bottom: 0.15rem;
}

.jas-meta-val {
  font-size: 0.9rem;
  color: #f1f5f9;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* HERO ILLUSTRATION */
.jas-hero-right {
  display: flex;
  align-items: center;
  justify-content: center;
}

.jas-hero-illo {
  position: relative;
  width: 100%;
  max-width: 380px;
  aspect-ratio: 1;
}

.jas-illo-card {
  position: relative;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 1.5rem;
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.jas-illo-card-top {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.jas-illo-avatar {
  width: 48px;
  height: 48px;
  border-radius: 0.75rem;
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  flex-shrink: 0;
}

.jas-illo-lines {
  flex: 1;
  min-width: 0;
}

.jas-illo-line {
  height: 12px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 999px;
  margin-bottom: 0.5rem;
}

.jas-illo-line--title {
  width: 70%;
  height: 16px;
  background: rgba(255, 255, 255, 0.15);
  margin-bottom: 0.75rem;
}

.jas-illo-line--short {
  width: 50%;
}

.jas-illo-card-body {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.jas-illo-line--80 { width: 80%; }
.jas-illo-line--60 { width: 60%; }

.jas-illo-chips {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.jas-illo-chip {
  width: 48px;
  height: 32px;
  background: rgba(37, 99, 235, 0.12);
  border: 1px solid rgba(37, 99, 235, 0.2);
  border-radius: 0.5rem;
}

.jas-illo-badge {
  position: absolute;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 0.75rem;
  color: #60a5fa;
  backdrop-filter: blur(10px);
}

.jas-illo-badge--1 { top: -12px; right: -12px; }
.jas-illo-badge--2 { bottom: 60px; left: -24px; }
.jas-illo-badge--3 { bottom: -16px; right: 40px; }

/* WORKSPACE */
.jas-workspace {
  padding: 3rem 0;
  background: #f8fafc;
}

.jas-workspace-inner {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0 2rem;
  display: grid;
  grid-template-columns: 7fr 3fr;
  gap: 2rem;
}

.jas-form-col {
  min-width: 0;
}

.jas-side-col {
  min-width: 0;
}

.jas-side-sticky {
  position: sticky;
  top: 6rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  overflow: hidden;
}

/* CARD */
.jas-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 1.25rem;
  box-shadow: 0 14px 40px rgba(15, 23, 42, 0.07);
  overflow: hidden;
  transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.jas-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.1);
}

.jas-card-header {
  padding: 1.5rem 1.75rem 0;
}

.jas-card-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 0.25rem;
}

.jas-card-title--sm {
  font-size: 1.05rem;
}

.jas-card-sub {
  font-size: 0.9rem;
  color: #64748b;
  margin: 0;
}

.jas-card-body {
  padding: 1.5rem 1.75rem 1.75rem;
}

/* FORM */
.jas-field {
  margin-bottom: 1.5rem;
}

.jas-field:last-of-type {
  margin-bottom: 2rem;
}

.jas-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  font-weight: 600;
  color: #334155;
  margin-bottom: 0.5rem;
}

.jas-label-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #2563eb;
}

.jas-resume-options {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.jas-resume-option {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.jas-resume-option-title {
  font-size: 0.8rem;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.jas-resume-divider {
  display: flex;
  align-items: center;
  text-align: center;
  font-size: 0.8rem;
  color: #94a3b8;
  font-weight: 500;
}

.jas-resume-divider::before,
.jas-resume-divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px dashed #e5e7eb;
  margin: 0 0.75rem;
}

.jas-select-wrap {
  position: relative;
}

.jas-select {
  width: 100%;
  min-height: 52px;
  padding: 0.75rem 2.75rem 0.75rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.875rem;
  background-color: #ffffff;
  font-size: 0.95rem;
  color: #0f172a;
  appearance: none;
  transition: all 0.2s ease;
  cursor: pointer;
}

.jas-select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.jas-select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #64748b;
  pointer-events: none;
}

.jas-file-upload {
  display: block;
  cursor: pointer;
}

.jas-file-input {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

.jas-file-upload-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 1.25rem;
  border: 2px dashed #e5e7eb;
  border-radius: 0.875rem;
  background-color: #f8fafc;
  transition: all 0.2s ease;
  text-align: center;
}

.jas-file-upload-area:hover {
  border-color: #2563eb;
  background-color: rgba(37, 99, 235, 0.02);
}

.jas-file-upload-area--has-file {
  border-style: solid;
  border-color: #2563eb;
  background-color: rgba(37, 99, 235, 0.04);
}

.jas-file-upload-text {
  font-size: 0.9rem;
  font-weight: 600;
  color: #0f172a;
}

.jas-file-upload-hint {
  font-size: 0.8rem;
  color: #64748b;
}

.jas-textarea {
  width: 100%;
  padding: 0.85rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.875rem;
  background-color: #ffffff;
  font-size: 0.95rem;
  color: #0f172a;
  resize: vertical;
  transition: all 0.2s ease;
  line-height: 1.6;
}

.jas-textarea:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.jas-input--error {
  border-color: #ef4444 !important;
}

.jas-input--error:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
}

.jas-hint {
  font-size: 0.8rem;
  color: #64748b;
  margin-top: 0.4rem;
}

.jas-error {
  font-size: 0.8rem;
  color: #ef4444;
  margin-top: 0.4rem;
  font-weight: 500;
}

.jas-alert {
  padding: 0.9rem 1.1rem;
  border-radius: 0.875rem;
  margin-bottom: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
.jas-alert--danger {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #991b1b;
}
.jas-alert--success {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  color: #166534;
}
.jas-alert-head {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  font-size: 0.88rem;
}
.jas-alert-body {
  font-size: 0.84rem;
}

/* BUTTONS */
.jas-form-actions {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

@media (min-width: 640px) {
  .jas-form-actions {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}

.jas-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.85rem 1.75rem;
  border-radius: 0.875rem;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.jas-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.jas-btn--primary {
  background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
  color: #ffffff;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
}

.jas-btn--primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 12px 28px rgba(37, 99, 235, 0.35);
}

.jas-btn--ghost {
  background: #ffffff;
  color: #475569;
  border: 1px solid #e5e7eb;
}

.jas-btn--ghost:hover:not(:disabled) {
  background: #f1f5f9;
  border-color: #d1d5db;
  transform: translateY(-1px);
}

.jas-spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: jas-spin 0.7s linear infinite;
}

@keyframes jas-spin {
  to { transform: rotate(360deg); }
}

/* SUMMARY CARD */
.jas-summary-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  min-width: 0;
}

.jas-summary-item {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  overflow-wrap: break-word;
}

.jas-summary-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 0.625rem;
  background: rgba(37, 99, 235, 0.08);
  color: #2563eb;
  flex-shrink: 0;
}

.jas-summary-content {
  min-width: 0;
}

.jas-summary-label {
  font-size: 0.75rem;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 600;
  margin-bottom: 0.15rem;
}

.jas-summary-value {
  font-size: 0.9rem;
  color: #0f172a;
  font-weight: 600;
  word-break: break-word;
}

/* CHECKLIST */
.jas-checklist {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  min-width: 0;
}

.jas-checklist-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: #334155;
  font-weight: 500;
  overflow-wrap: break-word;
}

.jas-check-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #10b981;
  flex-shrink: 0;
}

/* FOOTER */
.jas-footer {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
  border-top: 3px solid #2563eb;
  padding: 2.5rem 0;
  margin-top: 3rem;
}

.jas-footer-inner {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.jas-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
}

.jas-brand-mark {
  width: 40px;
  height: 40px;
  border-radius: 0.75rem;
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 1rem;
  font-family: 'Poppins', sans-serif;
}

.jas-brand-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1.05rem;
  color: #f8fafc;
}

.jas-footer-tagline {
  font-size: 0.9rem;
  color: #cbd5e1;
  margin: 0;
}

.jas-footer-right {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  flex-wrap: wrap;
}

.jas-footer-link {
  font-size: 0.85rem;
  color: #e2e8f0;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}

.jas-footer-link:hover {
  color: #ffffff;
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
  .jas-hero-inner {
    grid-template-columns: 1fr;
  }

  .jas-hero-right {
    display: none;
  }

  .jas-workspace-inner {
    grid-template-columns: 1fr;
  }

  .jas-side-sticky {
    position: static;
  }

  .jas-footer-inner {
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 575.98px) {
  .jas-hero {
    padding: 2.5rem 0 3rem;
  }

  .jas-hero-inner {
    padding: 0 1rem;
  }

  .jas-hero-meta {
    grid-template-columns: 1fr;
  }

  .jas-workspace-inner {
    padding: 0 1rem;
  }

  .jas-card-header,
  .jas-card-body {
    padding-left: 1.25rem;
    padding-right: 1.25rem;
  }
}
</style>
