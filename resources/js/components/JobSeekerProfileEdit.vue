<template>
    <div class="spe">
        <!-- PAGE HEADER -->
        <section class="spe-header">
            <div class="spe-header-bg" aria-hidden="true"></div>
            <div class="spe-header-inner">
                <span class="spe-pill"><Sparkles :size="14" /> Profile Editor</span>
                <h1 class="spe-title">Edit Profile</h1>
                <p class="spe-sub">Keep your profile updated to receive better job recommendations.</p>
            </div>
        </section>

        <!-- FORM -->
        <form :action="actionUrl" method="POST" enctype="multipart/form-data" ref="form" @submit="onSubmit">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="_method" value="PATCH">

            <div class="spe-grid">
                <!-- LEFT MAIN -->
                <div class="spe-main">
                    <!-- PROFILE HEADER CARD -->
                    <section class="spe-card spe-profile">
                        <div class="spe-profile-cover"></div>
                        <div class="spe-profile-body">
                            <div class="spe-profile-left">
                                <div class="spe-avatar-wrap">
                                    <img v-if="photoPreview || profilePhoto" :src="photoPreview || profilePhoto" :alt="fullName" class="spe-avatar">
                                    <span v-else class="spe-avatar-ph">{{ initials(fullName) }}</span>
                                    <button type="button" class="spe-avatar-cam" @click="triggerPhoto" aria-label="Upload photo">
                                        <Camera :size="18" />
                                    </button>
                                    <input ref="photoInput" type="file" name="profile_photo" accept="image/png,image/jpeg,image/jpg,image/webp" class="d-none" @change="onPhotoChange">
                                </div>
                                <div class="spe-profile-actions">
                                    <button type="button" class="spe-btn spe-btn-soft spe-btn-sm" @click="triggerPhoto"><Upload :size="15" /> Upload Photo</button>
                                    <button v-if="photoPreview || hasPhoto" type="button" class="spe-btn spe-btn-ghost spe-btn-sm" @click="removePhoto"><ImageOff :size="15" /> Remove</button>
                                </div>
                            </div>

                            <div class="spe-profile-info">
                                <h2 class="spe-profile-name">{{ fullName }}</h2>
                                <div class="spe-profile-title">{{ form.professional_title || 'Professional' }}</div>
                                <div class="spe-profile-meta">
                                    <span v-if="location"><MapPin :size="14" /> {{ location }}</span>
                                    <span><Mail :size="14" /> {{ userEmail }}</span>
                                    <span v-if="form.phone"><Phone :size="14" /> {{ form.phone }}</span>
                                </div>
                                <div class="spe-profile-badges">
                                    <span v-if="isVerified" class="spe-badge spe-badge--ok"><BadgeCheck :size="14" /> Verified</span>
                                    <span class="spe-badge spe-badge--blue"><CircleCheck :size="14" /> Profile Editor</span>
                                    <span class="spe-badge" :class="availability ? 'spe-badge--ok' : 'spe-badge--muted'">
                                        {{ availability ? 'Available for Work' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>

                            <div class="spe-profile-ring">
                                <div class="spe-ring" :style="ringStyle">
                                    <div class="spe-ring-inner">
                                        <span class="spe-ring-pct">{{ completion.percentage }}<small>%</small></span>
                                    </div>
                                </div>
                                <div class="spe-ring-label">Completion</div>
                            </div>
                        </div>
                    </section>

                    <!-- PERSONAL INFORMATION -->
                    <section class="spe-card">
                        <div class="spe-card-head">
                            <div class="spe-card-ico spe-card-ico--blue"><User :size="18" /></div>
                            <div>
                                <h3 class="spe-card-h">Personal Information</h3>
                                <p class="spe-card-sub">Your basic identity details.</p>
                            </div>
                        </div>
                        <div class="spe-form-grid">
                            <div class="spe-field" :class="{ 'has-error': errors.first_name }">
                                <label class="spe-label" for="first_name"><User :size="14" /> First Name <span class="spe-req">*</span></label>
                                <input id="first_name" name="first_name" v-model="form.first_name" class="spe-input" :class="{ 'is-invalid': errors.first_name }" required>
                                <div v-if="errors.first_name" class="spe-error">{{ errors.first_name[0] }}</div>
                            </div>
                            <div class="spe-field" :class="{ 'has-error': errors.last_name }">
                                <label class="spe-label" for="last_name"><User :size="14" /> Last Name <span class="spe-req">*</span></label>
                                <input id="last_name" name="last_name" v-model="form.last_name" class="spe-input" :class="{ 'is-invalid': errors.last_name }" required>
                                <div v-if="errors.last_name" class="spe-error">{{ errors.last_name[0] }}</div>
                            </div>
                            <div class="spe-field" :class="{ 'has-error': errors.phone }">
                                <label class="spe-label" for="phone"><Phone :size="14" /> Phone <span class="spe-req">*</span></label>
                                <input id="phone" name="phone" v-model="form.phone" class="spe-input" :class="{ 'is-invalid': errors.phone }" required>
                                <div v-if="errors.phone" class="spe-error">{{ errors.phone[0] }}</div>
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="date_of_birth"><Calendar :size="14" /> Date of Birth</label>
                                <input id="date_of_birth" type="date" name="date_of_birth" v-model="form.date_of_birth" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="gender"><Users :size="14" /> Gender</label>
                                <input id="gender" name="gender" v-model="form.gender" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="nationality"><Globe :size="14" /> Nationality</label>
                                <input id="nationality" name="nationality" v-model="form.nationality" class="spe-input">
                            </div>
                            <div class="spe-field spe-field--full">
                                <label class="spe-label" for="address"><MapPin :size="14" /> Address</label>
                                <textarea id="address" name="address" rows="2" v-model="form.address" class="spe-input"></textarea>
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="city"><MapPin :size="14" /> City</label>
                                <input id="city" name="city" v-model="form.city" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="state"><MapPin :size="14" /> State</label>
                                <input id="state" name="state" v-model="form.state" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="country"><Globe :size="14" /> Country</label>
                                <input id="country" name="country" v-model="form.country" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="postal_code"><MapPin :size="14" /> Postal Code</label>
                                <input id="postal_code" name="postal_code" v-model="form.postal_code" class="spe-input">
                            </div>
                        </div>
                    </section>

                    <!-- PROFESSIONAL INFORMATION -->
                    <section class="spe-card">
                        <div class="spe-card-head">
                            <div class="spe-card-ico spe-card-ico--emerald"><BriefcaseBusiness :size="18" /></div>
                            <div>
                                <h3 class="spe-card-h">Professional Information</h3>
                                <p class="spe-card-sub">Showcase your career and preferences.</p>
                            </div>
                        </div>
                        <div class="spe-form-grid">
                            <div class="spe-field spe-field--full">
                                <label class="spe-label" for="professional_title"><Award :size="14" /> Professional Title</label>
                                <input id="professional_title" name="professional_title" v-model="form.professional_title" class="spe-input">
                            </div>
                            <div class="spe-field spe-field--full">
                                <label class="spe-label" for="professional_summary"><FileText :size="14" /> Professional Summary</label>
                                <textarea id="professional_summary" name="professional_summary" rows="4" v-model="form.professional_summary" class="spe-input"></textarea>
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="current_job_title"><Briefcase :size="14" /> Current Job Title</label>
                                <input id="current_job_title" name="current_job_title" v-model="form.current_job_title" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="current_company"><Building2 :size="14" /> Current Company</label>
                                <input id="current_company" name="current_company" v-model="form.current_company" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="years_of_experience"><Clock3 :size="14" /> Years of Experience</label>
                                <input id="years_of_experience" type="number" min="0" max="60" name="years_of_experience" v-model="form.years_of_experience" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="expected_salary"><Banknote :size="14" /> Expected Salary</label>
                                <input id="expected_salary" type="number" step="0.01" name="expected_salary" v-model="form.expected_salary" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="preferred_job_type"><Target :size="14" /> Preferred Job Type</label>
                                <input id="preferred_job_type" name="preferred_job_type" v-model="form.preferred_job_type" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="preferred_workplace"><Laptop :size="14" /> Preferred Workplace</label>
                                <input id="preferred_workplace" name="preferred_workplace" v-model="form.preferred_workplace" class="spe-input">
                            </div>
                            <div class="spe-field spe-field--full">
                                <label class="spe-label" for="preferred_location"><MapPin :size="14" /> Preferred Location</label>
                                <input id="preferred_location" name="preferred_location" v-model="form.preferred_location" class="spe-input">
                            </div>
                        </div>

                        <div class="spe-check-row">
                            <label class="spe-check">
                                <input type="checkbox" name="is_available_for_work" value="1" v-model="form.is_available_for_work">
                                <span class="spe-check-box"><CircleCheck :size="14" /></span>
                                <span>Available for Work</span>
                            </label>
                        </div>

                        <!-- SOCIAL LINKS -->
                        <div class="spe-social">
                            <a :href="socialPreview.linkedin || '#'" target="_blank" rel="noopener" class="spe-social-btn" :class="{ 'is-empty': !form.linkedin_url }"><Link :size="16" /></a>
                            <a :href="form.github_url || '#'" target="_blank" rel="noopener" class="spe-social-btn" :class="{ 'is-empty': !form.github_url }"><Code2 :size="16" /></a>
                            <a :href="form.portfolio_url || '#'" target="_blank" rel="noopener" class="spe-social-btn" :class="{ 'is-empty': !form.portfolio_url }"><Globe :size="16" /></a>
                            <a :href="form.website_url || '#'" target="_blank" rel="noopener" class="spe-social-btn" :class="{ 'is-empty': !form.website_url }"><Globe :size="16" /></a>
                        </div>
                        <div class="spe-form-grid mt-3">
                            <div class="spe-field">
                                <label class="spe-label" for="linkedin_url"><Link :size="14" /> LinkedIn Profile</label>
                                <input id="linkedin_url" type="url" name="linkedin_url" v-model="form.linkedin_url" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="github_url"><Code2 :size="14" /> GitHub</label>
                                <input id="github_url" type="url" name="github_url" v-model="form.github_url" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="portfolio_url"><Globe :size="14" /> Portfolio Website</label>
                                <input id="portfolio_url" type="url" name="portfolio_url" v-model="form.portfolio_url" class="spe-input">
                            </div>
                            <div class="spe-field">
                                <label class="spe-label" for="website_url"><Globe :size="14" /> Personal Website</label>
                                <input id="website_url" type="url" name="website_url" v-model="form.website_url" class="spe-input">
                            </div>
                        </div>
                    </section>

                    <!-- EDUCATION -->
                    <section class="spe-card">
                        <div class="spe-card-head spe-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="spe-card-ico spe-card-ico--amber"><GraduationCap :size="18" /></div>
                                <div>
                                    <h3 class="spe-card-h">Education</h3>
                                    <p class="spe-card-sub">Your academic background.</p>
                                </div>
                            </div>
                            <a :href="routes.educations" class="spe-btn spe-btn-outline spe-btn-sm"><Plus :size="15" /> Add Education</a>
                        </div>
                        <div v-if="educations.length" class="spe-list">
                            <div v-for="e in educations" :key="e.id" class="spe-list-item">
                                <div class="spe-list-ico"><GraduationCap :size="18" /></div>
                                <div class="spe-list-body">
                                    <div class="spe-list-title">{{ e.degree }} <span v-if="e.field_of_study" class="spe-list-sub">· {{ e.field_of_study }}</span></div>
                                    <div class="spe-list-sub">{{ e.institution_name }}</div>
                                    <div class="spe-list-meta" v-if="e.passing_year || e.result">Passing {{ e.passing_year }} · {{ e.result }}</div>
                                </div>
                                <div class="spe-list-actions">
                                    <a :href="eduEdit(e.id)" class="spe-icon-btn" aria-label="Edit"><Pencil :size="15" /></a>
                                    <a :href="eduEdit(e.id)" class="spe-icon-btn spe-icon-btn--danger" aria-label="Delete"><Trash2 :size="15" /></a>
                                </div>
                            </div>
                        </div>
                        <div v-else class="spe-empty">
                            <div class="spe-empty-ico"><GraduationCap :size="24" /></div>
                            <p class="mb-0 spe-muted">No education added yet.</p>
                        </div>
                    </section>

                    <!-- EXPERIENCE -->
                    <section class="spe-card">
                        <div class="spe-card-head spe-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="spe-card-ico spe-card-ico--blue"><Briefcase :size="18" /></div>
                                <div>
                                    <h3 class="spe-card-h">Experience</h3>
                                    <p class="spe-card-sub">Your professional journey.</p>
                                </div>
                            </div>
                            <a :href="routes.experiences" class="spe-btn spe-btn-outline spe-btn-sm"><Plus :size="15" /> Add Experience</a>
                        </div>
                        <div v-if="experiences.length" class="spe-timeline">
                            <div v-for="x in experiences" :key="x.id" class="spe-tl-item">
                                <div class="spe-tl-dot"><Building2 :size="16" /></div>
                                <div class="spe-tl-body">
                                    <div class="spe-list-title">{{ x.job_title }}</div>
                                    <div class="spe-list-sub">{{ x.company_name }} · {{ x.employment_type }}</div>
                                    <div class="spe-list-meta">{{ durationText(x) }}</div>
                                    <p v-if="x.job_description" class="spe-list-desc">{{ x.job_description }}</p>
                                    <div class="spe-list-actions mt-2">
                                        <a :href="expEdit(x.id)" class="spe-icon-btn" aria-label="Edit"><Pencil :size="15" /></a>
                                        <a :href="expEdit(x.id)" class="spe-icon-btn spe-icon-btn--danger" aria-label="Delete"><Trash2 :size="15" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="spe-empty">
                            <div class="spe-empty-ico"><Briefcase :size="24" /></div>
                            <p class="mb-0 spe-muted">No experience added yet.</p>
                        </div>
                    </section>

                    <!-- SKILLS -->
                    <section class="spe-card">
                        <div class="spe-card-head spe-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="spe-card-ico spe-card-ico--violet"><Award :size="18" /></div>
                                <div>
                                    <h3 class="spe-card-h">Skills</h3>
                                    <p class="spe-card-sub">Your strongest capabilities.</p>
                                </div>
                            </div>
                            <a :href="routes.skills" class="spe-btn spe-btn-outline spe-btn-sm"><Plus :size="15" /> Add Skill</a>
                        </div>
                        <div v-if="skills.length" class="spe-chips">
                            <span v-for="(s, i) in skills" :key="i" class="spe-chip" :class="'spe-chip--' + (i % 4)">
                                {{ s.skill_name }}
                                <span class="spe-chip-lvl">{{ s.proficiency_level }}</span>
                            </span>
                        </div>
                        <div v-else class="spe-empty">
                            <div class="spe-empty-ico"><Award :size="24" /></div>
                            <p class="mb-0 spe-muted">No skills added yet.</p>
                        </div>
                    </section>

                    <!-- RESUME -->
                    <section class="spe-card">
                        <div class="spe-card-head spe-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="spe-card-ico spe-card-ico--emerald"><FileText :size="18" /></div>
                                <div>
                                    <h3 class="spe-card-h">Resume</h3>
                                    <p class="spe-card-sub">Upload and manage your resume.</p>
                                </div>
                            </div>
                            <a :href="routes.resumes" class="spe-btn spe-btn-outline spe-btn-sm"><Plus :size="15" /> Add Resume</a>
                        </div>
                        <div v-if="defaultResume" class="spe-resume-box">
                            <div class="spe-resume-ico"><FileText :size="22" /></div>
                            <div class="spe-resume-info">
                                <div class="spe-list-title">{{ defaultResume.title || 'Default Resume' }}</div>
                                <div class="spe-list-sub">Last updated {{ defaultResume.uploaded_at ? formatDate(defaultResume.uploaded_at) : '—' }}</div>
                            </div>
                            <div class="spe-resume-actions">
                                <a v-if="defaultResume.id" :href="resumeDownload(defaultResume.id)" class="spe-btn spe-btn-soft spe-btn-sm"><Download :size="15" /> Download</a>
                                <a :href="routes.resumes" class="spe-btn spe-btn-ghost spe-btn-sm">Replace</a>
                            </div>
                        </div>
                        <div v-else-if="resumes.length" class="spe-resume-box">
                            <div class="spe-resume-ico"><FileText :size="22" /></div>
                            <div class="spe-resume-info">
                                <div class="spe-list-title">{{ resumes[0].title || 'Resume' }}</div>
                                <div class="spe-list-sub">{{ resumeCount }} resume{{ resumeCount === 1 ? '' : 's' }} uploaded</div>
                            </div>
                            <a :href="routes.resumes" class="spe-btn spe-btn-ghost spe-btn-sm">Manage</a>
                        </div>
                        <div v-else class="spe-resume-drop">
                            <div class="spe-resume-upload"><UploadCloud :size="26" /></div>
                            <p class="mb-1 spe-fw">No resume uploaded</p>
                            <p class="mb-0 spe-muted small">Add a polished resume from the resume section.</p>
                            <a :href="routes.resumes" class="spe-btn spe-btn-primary spe-btn-sm mt-2">Upload Resume</a>
                        </div>
                    </section>
                </div>

                <!-- RIGHT SIDEBAR -->
                <aside class="spe-side">
                    <div class="spe-side-sticky">
                        <!-- PROFILE COMPLETION -->
                        <section class="spe-card spe-side-completion">
                            <h4 class="spe-side-h">Profile Completion</h4>
                            <div class="spe-side-ring" :style="sideRingStyle">
                                <span>{{ completion.percentage }}%</span>
                            </div>
                            <div class="spe-side-section">
                                <div class="spe-side-section-h"><CircleCheck :size="14" /> Completed</div>
                                <div class="spe-chips-sm">
                                    <span v-for="s in completion.completed_sections" :key="'c'+s" class="spe-tag spe-tag--ok">{{ humanize(s) }}</span>
                                    <span v-if="!completion.completed_sections.length" class="spe-muted small">None</span>
                                </div>
                            </div>
                            <div class="spe-side-section">
                                <div class="spe-side-section-h"><CircleDashed :size="14" /> Missing</div>
                                <div class="spe-chips-sm">
                                    <span v-for="s in completion.missing_sections" :key="'m'+s" class="spe-tag">{{ humanize(s) }}</span>
                                    <span v-if="!completion.missing_sections.length" class="spe-tag spe-tag--ok">All done</span>
                                </div>
                            </div>
                            <a :href="routes.profile" class="spe-btn spe-btn-primary spe-btn-block spe-btn-sm mt-2">Complete Profile</a>
                        </section>

                        <!-- QUICK NAV -->
                        <section class="spe-card">
                            <h4 class="spe-side-h">Quick Navigation</h4>
                            <div class="spe-qn">
                                <a :href="routes.dashboard" class="spe-qn-item"><LayoutDashboard :size="16" /> Dashboard</a>
                                <a :href="routes.educations" class="spe-qn-item"><GraduationCap :size="16" /> Education</a>
                                <a :href="routes.experiences" class="spe-qn-item"><Briefcase :size="16" /> Experience</a>
                                <a :href="routes.skills" class="spe-qn-item"><Award :size="16" /> Skills</a>
                                <a :href="routes.resumes" class="spe-qn-item"><FileText :size="16" /> Resume</a>
                                <a :href="routes.applications" class="spe-qn-item"><Send :size="16" /> Applications</a>
                            </div>
                        </section>

                        <!-- RESUME STATUS -->
                        <section class="spe-card">
                            <h4 class="spe-side-h">Resume Status</h4>
                            <div class="spe-resume-mini">
                                <div class="spe-resume-ico-sm"><FileText :size="18" /></div>
                                <div>
                                    <div class="spe-fw" v-if="defaultResume">{{ defaultResume.title || 'Default Resume' }}</div>
                                    <div class="spe-fw" v-else-if="resumeCount">Resume uploaded</div>
                                    <div class="spe-fw" v-else>No resume yet</div>
                                    <div class="spe-muted small">{{ resumeCount }} resume{{ resumeCount === 1 ? '' : 's' }}</div>
                                </div>
                            </div>
                        </section>

                        <!-- TIPS -->
                        <section class="spe-card">
                            <h4 class="spe-side-h">Tips to Improve</h4>
                            <ul class="spe-tips">
                                <li><Sparkles :size="14" /> Add a professional photo.</li>
                                <li><Sparkles :size="14" /> Complete at least 3 skills.</li>
                                <li><Sparkles :size="14" /> Keep your summary concise.</li>
                                <li><Sparkles :size="14" /> Upload a default resume.</li>
                            </ul>
                        </section>
                    </div>
                </aside>
            </div>

            <!-- STICKY SAVE BAR -->
            <div class="spe-savebar">
                <div class="spe-savebar-inner">
                    <span class="spe-savebar-hint"><CircleCheck :size="15" /> Changes save to your profile</span>
                    <div class="spe-savebar-actions">
                        <a :href="routes.dashboard" class="spe-btn spe-btn-ghost">Cancel</a>
                        <button type="reset" class="spe-btn spe-btn-outline">Reset</button>
                        <a :href="routes.profile" class="spe-btn spe-btn-soft">Preview</a>
                        <button type="submit" class="spe-btn spe-btn-primary"><Save :size="16" /> Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {
    Sparkles, Camera, Upload, ImageOff, MapPin, Mail, Phone, User, Users, Globe, Calendar,
    BadgeCheck, CircleCheck, BriefcaseBusiness, Briefcase, Award, FileText, Clock3, Banknote,
    Target, Laptop, Link, Code2, Plus, Pencil, Trash2, Download, UploadCloud, Save, Send,
    CircleDashed, LayoutDashboard, Building2, ArrowUp,
} from '@lucide/vue';

function humanize(str) {
    return String(str || '').replace(/[_-]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
}
function initials(name) {
    const parts = (name || 'U').trim().split(/\s+/);
    return (parts.slice(0, 2).map((p) => p[0]).join('') || 'U').toUpperCase();
}

export default {
    name: 'JobSeekerProfileEdit',
    components: {
        Sparkles, Camera, Upload, ImageOff, MapPin, Mail, Phone, User, Users, Globe, Calendar,
        BadgeCheck, CircleCheck, BriefcaseBusiness, Briefcase, Award, FileText, Clock3, Banknote,
        Target, Laptop, Link, Code2, Plus, Pencil, Trash2, Download, UploadCloud, Save, Send,
        CircleDashed, LayoutDashboard, Building2, ArrowUp,
    },
    props: {
        profile: { type: Object, default: () => ({}) },
        user: { type: Object, default: () => ({}) },
        completion: { type: Object, default: () => ({ percentage: 0, completed_sections: [], missing_sections: [] }) },
        educations: { type: Array, default: () => [] },
        experiences: { type: Array, default: () => [] },
        skills: { type: Array, default: () => [] },
        resumes: { type: Array, default: () => [] },
        defaultResume: { type: Object, default: null },
        resumeCount: { type: Number, default: 0 },
        csrfToken: { type: String, default: '' },
        actionUrl: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    data() {
        const p = this.profile || {};
        return {
            photoPreview: null,
            photoRemoved: false,
            form: {
                first_name: p.first_name || '',
                last_name: p.last_name || '',
                phone: p.phone || '',
                date_of_birth: p.date_of_birth ? (p.date_of_birth.date ? p.date_of_birth.date.substring(0, 10) : String(p.date_of_birth).substring(0, 10)) : '',
                gender: p.gender || '',
                nationality: p.nationality || '',
                address: p.address || '',
                city: p.city || '',
                state: p.state || '',
                country: p.country || '',
                postal_code: p.postal_code || '',
                professional_title: p.professional_title || '',
                professional_summary: p.professional_summary || '',
                current_job_title: p.current_job_title || '',
                current_company: p.current_company || '',
                years_of_experience: p.years_of_experience ?? '',
                expected_salary: p.expected_salary ?? '',
                preferred_job_type: p.preferred_job_type || '',
                preferred_workplace: p.preferred_workplace || '',
                preferred_location: p.preferred_location || '',
                linkedin_url: p.linkedin_url || '',
                github_url: p.github_url || '',
                portfolio_url: p.portfolio_url || '',
                website_url: p.website_url || '',
                is_available_for_work: !!p.is_available_for_work,
            },
        };
    },
    computed: {
        completionData() { return this.completion || { percentage: 0, completed_sections: [], missing_sections: [] }; },
        fullName() {
            const p = this.profile || {};
            const n = `${p.first_name || ''} ${p.last_name || ''}`.trim();
            return n || (this.user?.name || 'Job Seeker');
        },
        userEmail() { return this.user?.email || (this.profile?.email || ''); },
        profilePhoto() { return this.profile?.profile_photo_url || '/images/default-avatar.svg'; },
        hasPhoto() { return !!this.profile?.profile_photo; },
        isVerified() { return !!this.profile?.verified_at; },
        availability() { return !!this.form.is_available_for_work; },
        location() {
            const p = this.profile || {};
            return [p.city, p.country].filter(Boolean).join(', ') || p.preferred_location || '';
        },
        ringStyle() {
            const pct = this.completionData.percentage || 0;
            return { background: `conic-gradient(#2563eb ${pct * 3.6}deg, rgba(37,99,235,0.12) 0deg)` };
        },
        sideRingStyle() {
            const pct = this.completionData.percentage || 0;
            return { background: `conic-gradient(#10b981 ${pct * 3.6}deg, rgba(16,185,129,0.14) 0deg)` };
        },
        socialPreview() { return { linkedin: this.form.linkedin_url }; },
    },
    methods: {
        humanize,
        initials,
        triggerPhoto() { this.$refs.photoInput.click(); },
        onPhotoChange(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            this.photoRemoved = false;
            const reader = new FileReader();
            reader.onload = (ev) => { this.photoPreview = ev.target.result; };
            reader.readAsDataURL(file);
        },
        removePhoto() {
            this.photoPreview = null;
            this.photoRemoved = true;
            this.$refs.photoInput.value = '';
        },
        eduEdit(id) { return this.routes.educations ? `${this.routes.educations}/${id}/edit` : '#'; },
        expEdit(id) { return this.routes.experiences ? `${this.routes.experiences}/${id}/edit` : '#'; },
        resumeDownload(id) { return this.routes.resumeDownload ? this.routes.resumeDownload.replace('__ID__', id) : '#'; },
        durationText(x) {
            const s = x.start_date ? (x.start_date.date ? x.start_date.date.substring(0, 7) : String(x.start_date).substring(0, 7)) : '';
            const e = x.currently_working ? 'Present' : (x.end_date ? (x.end_date.date ? x.end_date.date.substring(0, 7) : String(x.end_date).substring(0, 7)) : '');
            return [s, e].filter(Boolean).join(' — ') || '';
        },
        formatDate(v) {
            const d = new Date(v.date ? v.date : v);
            if (isNaN(d)) return '—';
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        onSubmit() {
            if (this.photoRemoved && this.$refs.photoInput) {
                // keep file input empty so no upload happens; backend keeps/removes as needed
            }
        },
    },
};
</script>

<style scoped>
.spe {
    --spe-primary: #2563eb;
    --spe-secondary: #10b981;
    --spe-accent: #f59e0b;
    --spe-danger: #ef4444;
    --spe-ink: #111827;
    --spe-muted: #6b7280;
    --spe-border: #e5e7eb;
    --spe-bg: #f8fafc;
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--spe-ink);
    animation: spe-fade 0.5s ease both;
}
@keyframes spe-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.spe-muted { color: var(--spe-muted); }
.spe-fw { font-weight: 700; }
.small { font-size: 0.82rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-3 { margin-top: 1rem; }
.d-none { display: none !important; }

/* HEADER */
.spe-header {
    position: relative; border-radius: 24px; overflow: hidden; padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem; background: radial-gradient(120% 130% at 0% 0%, #1e3a8a 0%, #1d4ed8 40%, #2563eb 70%, #3b82f6 100%);
    box-shadow: 0 24px 50px -28px rgba(37,99,235,0.5); isolation: isolate;
}
.spe-header-bg { position: absolute; inset: 0; z-index: -1; overflow: hidden; }
.spe-header-bg::before { content: ''; position: absolute; width: 360px; height: 360px; border-radius: 50%; background: radial-gradient(circle, rgba(59,130,246,0.5), transparent 70%); top: -120px; right: -80px; filter: blur(30px); }
.spe-pill { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.9rem; border-radius: 999px; background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25); color: #e5e7eb; font-size: 0.76rem; font-weight: 600; letter-spacing: 0.03em; margin-bottom: 0.75rem; }
.spe-title { font-family: 'Poppins', sans-serif; color: #fff; font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 700; margin: 0 0 0.4rem; }
.spe-sub { color: rgba(255,255,255,0.9); margin: 0; font-size: 0.95rem; }

/* GRID */
.spe-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
.spe-main { display: flex; flex-direction: column; gap: 1.5rem; min-width: 0; }
.spe-side { min-width: 0; }

/* CARD */
.spe-card { background: #fff; border: 1px solid var(--spe-border); border-radius: 22px; box-shadow: 0 6px 24px rgba(15,23,42,0.05); padding: clamp(1.25rem, 2.5vw, 1.75rem); transition: box-shadow 0.3s; }
.spe-card:hover { box-shadow: 0 14px 40px rgba(15,23,42,0.1); }
.spe-card-head { display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem; }
.spe-card-head--row { justify-content: space-between; }
.spe-card-ico { width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.spe-card-ico--blue { background: rgba(37,99,235,0.12); color: #2563eb; }
.spe-card-ico--emerald { background: rgba(16,185,129,0.12); color: #059669; }
.spe-card-ico--amber { background: rgba(245,158,11,0.12); color: #d97706; }
.spe-card-ico--violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
.spe-card-h { font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 600; color: var(--spe-ink); margin: 0; }
.spe-card-sub { color: var(--spe-muted); margin: 0; font-size: 0.85rem; }

/* PROFILE CARD */
.spe-profile { padding: 0; overflow: hidden; }
.spe-profile-cover { height: 84px; background: linear-gradient(135deg, #2563eb, #6366f1); }
.spe-profile-body { padding: 0 clamp(1.25rem, 2.5vw, 1.75rem) 1.5rem; margin-top: -38px; display: grid; grid-template-columns: auto 1fr auto; gap: 1.25rem; align-items: center; }
.spe-profile-left { display: flex; flex-direction: column; align-items: center; gap: 0.6rem; }
.spe-avatar-wrap { position: relative; }
.spe-avatar { width: 92px; height: 92px; border-radius: 24px; border: 4px solid #fff; object-fit: cover; box-shadow: 0 12px 28px rgba(0,0,0,0.18); }
.spe-avatar-ph { width: 92px; height: 92px; border-radius: 24px; border: 4px solid #fff; background: linear-gradient(135deg, #2563eb, #6366f1); color: #fff; font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 2rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 28px rgba(0,0,0,0.18); }
.spe-avatar-cam { position: absolute; bottom: -6px; right: -6px; width: 34px; height: 34px; border-radius: 50%; background: #fff; color: #2563eb; border: 2px solid #e5e7eb; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.12); transition: all 0.2s; }
.spe-avatar-cam:hover { background: #2563eb; color: #fff; }
.spe-profile-info { min-width: 0; }
.spe-profile-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.35rem; margin: 0; }
.spe-profile-title { font-size: 0.9rem; color: var(--spe-primary); font-weight: 600; }
.spe-profile-meta { display: flex; flex-wrap: wrap; gap: 0.4rem 0.9rem; margin: 0.5rem 0; }
.spe-profile-meta span { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.8rem; color: var(--spe-muted); }
.spe-profile-meta svg { color: var(--spe-primary); }
.spe-profile-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.spe-badge { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.72rem; font-weight: 600; padding: 0.3rem 0.7rem; border-radius: 999px; background: #f1f5f9; color: #64748b; }
.spe-badge--ok { background: rgba(16,185,129,0.12); color: #059669; }
.spe-badge--blue { background: rgba(37,99,235,0.1); color: #2563eb; }
.spe-badge--muted { background: #f1f5f9; color: #94a3b8; }
.spe-profile-ring { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; }
.spe-ring { width: 86px; height: 86px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.spe-ring-inner { width: 68px; height: 68px; border-radius: 50%; background: #1d4ed8; display: flex; align-items: center; justify-content: center; color: #fff; }
.spe-ring-pct { font-family: 'Poppins', sans-serif; font-size: 1.3rem; font-weight: 700; line-height: 1; }
.spe-ring-pct small { font-size: 0.75rem; opacity: 0.85; }
.spe-ring-label { font-size: 0.72rem; color: var(--spe-muted); font-weight: 600; }

/* BUTTONS */
.spe-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem; padding: 0.7rem 1.3rem; border-radius: 0.85rem; font-weight: 600; font-size: 0.9rem; border: 0; cursor: pointer; text-decoration: none; transition: all 0.25s cubic-bezier(0.22,1,0.36,1); white-space: nowrap; }
.spe-btn-sm { padding: 0.5rem 0.9rem; font-size: 0.82rem; border-radius: 0.7rem; }
.spe-btn-block { width: 100%; }
.spe-btn-primary { background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; box-shadow: 0 10px 24px rgba(37,99,235,0.25); }
.spe-btn-primary:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 14px 32px rgba(37,99,235,0.34); }
.spe-btn-soft { background: #eff6ff; color: #2563eb; }
.spe-btn-soft:hover { background: #dbeafe; color: #1d4ed8; transform: translateY(-1px); }
.spe-btn-outline { background: #fff; color: var(--spe-primary); border: 1px solid var(--spe-border); }
.spe-btn-outline:hover { border-color: var(--spe-primary); color: var(--spe-primary); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.12); }
.spe-btn-ghost { background: #f1f5f9; color: #475569; }
.spe-btn-ghost:hover { background: #e2e8f0; color: #1e293b; }

/* FORM */
.spe-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.1rem; }
.spe-field { display: flex; flex-direction: column; gap: 0.4rem; }
.spe-field--full { grid-column: 1 / -1; }
.spe-label { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; font-weight: 600; color: #374151; }
.spe-label svg { color: var(--spe-primary); }
.spe-req { color: var(--spe-danger); }
.spe-input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--spe-border); border-radius: 12px; font-size: 0.9rem; color: var(--spe-ink); background: #fff; transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s; }
.spe-input:focus { outline: none; border-color: var(--spe-primary); box-shadow: 0 0 0 4px rgba(37,99,235,0.12); transform: translateY(-1px); }
.spe-input.is-invalid { border-color: var(--spe-danger); }
.spe-error { font-size: 0.76rem; color: var(--spe-danger); }
.spe-check-row { margin-top: 1rem; }
.spe-check { display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer; font-size: 0.88rem; font-weight: 500; }
.spe-check input { display: none; }
.spe-check-box { width: 22px; height: 22px; border-radius: 7px; border: 2px solid var(--spe-border); display: flex; align-items: center; justify-content: center; color: transparent; transition: all 0.2s; }
.spe-check input:checked + .spe-check-box { background: var(--spe-secondary); border-color: var(--spe-secondary); color: #fff; }

/* SOCIAL */
.spe-social { display: flex; gap: 0.6rem; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #f1f5f9; }
.spe-social-btn { width: 42px; height: 42px; border-radius: 12px; border: 1px solid var(--spe-border); background: #fff; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; }
.spe-social-btn:hover { background: var(--spe-primary); color: #fff; border-color: var(--spe-primary); transform: translateY(-2px); }
.spe-social-btn.is-empty { opacity: 0.5; }

/* LIST (education) */
.spe-list { display: flex; flex-direction: column; gap: 0.75rem; }
.spe-list-item { display: flex; align-items: center; gap: 0.9rem; padding: 0.85rem 1rem; border: 1px solid var(--spe-border); border-radius: 14px; background: linear-gradient(180deg, #fbfcff, #fff); transition: all 0.25s; }
.spe-list-item:hover { border-color: rgba(37,99,235,0.4); box-shadow: 0 8px 20px rgba(37,99,235,0.08); transform: translateX(3px); }
.spe-list-ico { width: 40px; height: 40px; border-radius: 11px; background: rgba(245,158,11,0.12); color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.spe-list-body { flex: 1; min-width: 0; }
.spe-list-title { font-weight: 700; font-size: 0.92rem; color: var(--spe-ink); }
.spe-list-sub { font-size: 0.8rem; color: var(--spe-muted); }
.spe-list-meta { font-size: 0.76rem; color: #9ca3af; margin-top: 2px; }
.spe-list-desc { font-size: 0.8rem; color: #4b5563; margin: 0.35rem 0 0; line-height: 1.5; }
.spe-list-actions { display: flex; gap: 0.4rem; }
.spe-icon-btn { width: 34px; height: 34px; border-radius: 9px; border: 1px solid var(--spe-border); background: #fff; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; }
.spe-icon-btn:hover { background: #eff6ff; color: #2563eb; border-color: #2563eb; }
.spe-icon-btn--danger:hover { background: #fef2f2; color: #dc2626; border-color: #dc2626; }

/* TIMELINE (experience) */
.spe-timeline { display: flex; flex-direction: column; }
.spe-tl-item { display: flex; gap: 0.9rem; padding-bottom: 1.1rem; position: relative; }
.spe-tl-item:not(:last-child)::before { content: ''; position: absolute; left: 19px; top: 40px; bottom: 0; width: 2px; background: #e5e7eb; }
.spe-tl-dot { width: 40px; height: 40px; border-radius: 11px; background: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; z-index: 1; }
.spe-tl-body { flex: 1; min-width: 0; padding-top: 0.2rem; }

/* CHIPS (skills) */
.spe-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.spe-chip { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 600; padding: 0.45rem 0.85rem; border-radius: 999px; border: 1px solid transparent; }
.spe-chip--0 { background: rgba(37,99,235,0.1); color: #1d4ed8; border-color: rgba(37,99,235,0.2); }
.spe-chip--1 { background: rgba(16,185,129,0.1); color: #059669; border-color: rgba(16,185,129,0.2); }
.spe-chip--2 { background: rgba(245,158,11,0.12); color: #d97706; border-color: rgba(245,158,11,0.22); }
.spe-chip--3 { background: rgba(139,92,246,0.12); color: #7c3aed; border-color: rgba(139,92,246,0.22); }
.spe-chip-lvl { font-size: 0.68rem; opacity: 0.7; font-weight: 700; text-transform: uppercase; }

/* RESUME */
.spe-resume-box { display: flex; align-items: center; gap: 0.9rem; padding: 1rem; border: 1px solid var(--spe-border); border-radius: 14px; background: linear-gradient(180deg, #ecfdf5, #fff); }
.spe-resume-ico { width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.spe-resume-info { flex: 1; min-width: 0; }
.spe-resume-actions { display: flex; gap: 0.5rem; }
.spe-resume-drop { text-align: center; padding: 1.5rem 1rem; border: 2px dashed #cbd5e1; border-radius: 16px; background: #f8fafc; }
.spe-resume-upload { width: 56px; height: 56px; margin: 0 auto 0.5rem; border-radius: 16px; background: rgba(37,99,235,0.08); color: #2563eb; display: flex; align-items: center; justify-content: center; }

/* EMPTY */
.spe-empty { text-align: center; padding: 1.5rem 1rem; }
.spe-empty-ico { width: 56px; height: 56px; border-radius: 50%; margin: 0 auto 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(37,99,235,0.08); color: var(--spe-primary); }

/* SIDEBAR */
.spe-side-sticky { position: sticky; top: 92px; display: flex; flex-direction: column; gap: 1.25rem; }
.spe-side-h { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600; color: var(--spe-ink); margin: 0 0 1rem; }
.spe-side-completion { text-align: center; }
.spe-side-ring { width: 96px; height: 96px; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; }
.spe-side-ring span { width: 74px; height: 74px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; font-weight: 700; color: #059669; }
.spe-side-section { text-align: left; margin-bottom: 0.85rem; }
.spe-side-section-h { display: flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: #374151; margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.03em; }
.spe-side-section-h svg { color: #2563eb; }
.spe-chips-sm { display: flex; flex-wrap: wrap; gap: 0.35rem; }
.spe-tag { font-size: 0.74rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 999px; background: #f1f5f9; color: #64748b; border: 1px solid #e5e7eb; }
.spe-tag--ok { background: rgba(16,185,129,0.1); color: #059669; border-color: rgba(16,185,129,0.2); }

.spe-qn { display: flex; flex-direction: column; gap: 0.4rem; }
.spe-qn-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 0.7rem; border-radius: 10px; background: #f8fafc; border: 1px solid var(--spe-border); text-decoration: none; color: #334155; font-size: 0.84rem; font-weight: 600; transition: all 0.2s; }
.spe-qn-item:hover { background: #fff; border-color: rgba(37,99,235,0.4); color: var(--spe-primary); transform: translateX(3px); }
.spe-qn-item svg { color: var(--spe-primary); }

.spe-resume-mini { display: flex; align-items: center; gap: 0.7rem; }
.spe-resume-ico-sm { width: 40px; height: 40px; border-radius: 11px; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.spe-tips { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.55rem; }
.spe-tips li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.82rem; color: #4b5563; }
.spe-tips svg { color: var(--spe-accent); flex-shrink: 0; margin-top: 2px; }

/* SAVE BAR */
.spe-savebar { position: sticky; bottom: 0; z-index: 50; margin-top: 1.5rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); border-top: 1px solid var(--spe-border); border-radius: 18px 18px 0 0; box-shadow: 0 -10px 30px -18px rgba(15,23,42,0.4); }
.spe-savebar-inner { max-width: 1320px; margin: 0 auto; padding: 0.85rem clamp(1rem, 3vw, 2.25rem); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.spe-savebar-hint { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--spe-muted); }
.spe-savebar-hint svg { color: var(--spe-secondary); }
.spe-savebar-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; }

/* RESPONSIVE */
@media (max-width: 1199.98px) {
    .spe-grid { grid-template-columns: 1fr 320px; }
}
@media (max-width: 991.98px) {
    .spe-grid { grid-template-columns: 1fr; }
    .spe-side-sticky { position: static; }
    .spe-profile-body { grid-template-columns: 1fr; text-align: center; justify-items: center; }
    .spe-profile-meta, .spe-profile-badges { justify-content: center; }
}
@media (max-width: 575.98px) {
    .spe-form-grid { grid-template-columns: 1fr; }
    .spe-savebar-actions { width: 100%; }
    .spe-savebar-actions .spe-btn { flex: 1; }
}
</style>
