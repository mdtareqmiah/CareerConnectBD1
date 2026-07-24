<template>
    <div class="jd-page">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="jd-breadcrumb mb-4">
            <ol class="jd-breadcrumb-list">
                <li><a href="/">Home</a></li>
                <li><span class="jd-bc-sep">/</span></li>
                <li><a :href="routes.jobs">Jobs</a></li>
                <li><span class="jd-bc-sep">/</span></li>
                <li class="active">{{ job.title }}</li>
            </ol>
        </nav>

        <!-- HERO -->
        <section class="jd-hero">
            <div class="jd-hero-bg" aria-hidden="true">
                <div class="jd-hero-glow"></div>
                <div class="jd-shape jd-shape--1"></div>
                <div class="jd-shape jd-shape--2"></div>
                <div class="jd-shape jd-shape--3"></div>
                <div class="jd-grid-lines"></div>
            </div>

            <div class="jd-hero-inner">
                <!-- LEFT: large company image card -->
                <div class="jd-hero-visual">
                    <div class="jd-company-card">
                        <div class="jd-company-card-glow"></div>
                        <div class="jd-company-card-inner">
                            <img v-if="companyLogoUrl" :src="companyLogoUrl" :alt="companyName + ' logo'" class="jd-company-img" @error="logoFailed = true">
                            <div v-else class="jd-company-img jd-company-ph">{{ companyInitials }}</div>
                            <span v-if="company.verified_at" class="jd-company-verified" title="Verified company">
                                <BadgeCheck :size="20" stroke-width="2.5" />
                            </span>
                        </div>
                        <div class="jd-company-card-name">{{ companyName }}</div>
                    </div>
                </div>

                <!-- RIGHT: job info -->
                <div class="jd-hero-main">
                    <div class="jd-hero-toprow">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="jd-chip jd-chip--soft"><Sparkles :size="14" /> Job Detail</span>
                            <span v-if="isFeatured" class="jd-chip jd-chip--featured"><Sparkles :size="14" /> Featured</span>
                            <span v-if="isUrgent" class="jd-chip jd-chip--urgent"><Flame :size="14" /> Urgent</span>
                        </div>
                        <button type="button" class="jd-back" @click="goBack">
                            <ArrowLeft :size="16" /> Back to Jobs
                        </button>
                    </div>

                    <h1 class="jd-hero-title">{{ job.title }}</h1>

                    <div class="jd-hero-company">
                        <span class="jd-hero-company-name">{{ companyName }}</span>
                        <span v-if="company.verified_at" class="jd-inline-verified" title="Verified company"><BadgeCheck :size="18" stroke-width="2.5" /></span>
                        <span v-if="company.industry" class="jd-hero-dot">{{ company.industry }}</span>
                    </div>

                    <div class="jd-hero-meta">
                        <span class="jd-meta-pill"><MapPin :size="15" />{{ job.location }}</span>
                        <span class="jd-meta-pill"><BriefcaseBusiness :size="15" />{{ job.job_type }}</span>
                        <span class="jd-meta-pill"><Clock3 :size="15" />{{ job.employment_status }}</span>
                        <span v-if="isRemote" class="jd-meta-pill jd-meta-pill--remote"><Globe :size="15" />Remote</span>
                        <span v-else-if="job.workplace === 'hybrid'" class="jd-meta-pill jd-meta-pill--hybrid"><Laptop :size="15" />Hybrid</span>
                        <span v-else-if="job.workplace === 'on-site'" class="jd-meta-pill jd-meta-pill--onsite"><Building2 :size="15" />On-site</span>
                        <span v-if="job.salary_min || job.salary_max" class="jd-meta-pill jd-meta-pill--salary">
                            <DollarSign :size="15" />{{ salaryText }}
                        </span>
                    </div>

                    <div class="jd-hero-actions">
                        <template v-if="user">
                            <template v-if="alreadyApplied">
                                <button type="button" class="jd-btn jd-btn--done" disabled><CircleCheck :size="18" /> Already Applied</button>
                            </template>
                            <template v-else-if="canApply">
                                <a :href="routes.apply" class="jd-btn jd-btn--primary"><Send :size="18" /> Apply Now</a>
                            </template>
                            <button v-if="userRoleSlug === 'job-seeker'" type="button" class="jd-btn jd-btn--save" :class="{ 'is-saved': isSaved }" @click="toggleSave">
                                <Bookmark :size="18" :fill="isSaved ? '#f59e0b' : 'none'" :stroke="isSaved ? '#f59e0b' : 'currentColor'" />
                                {{ isSaved ? 'Saved' : 'Save Job' }}
                            </button>
                        </template>
                        <template v-else>
                            <a :href="routes.login" class="jd-btn jd-btn--primary"><LogIn :size="18" /> Login to Apply</a>
                        </template>
                        <div class="dropdown jd-dropdown" ref="shareWrap">
                            <button class="jd-btn jd-btn--ghost" type="button" :aria-expanded="shareOpen" @click="toggleShare('hero')"><Share2 :size="18" /> Share</button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 jd-share-menu" :class="{ show: shareOpen }" @click.stop>
                                    <li><button type="button" class="dropdown-item" @click="copyLink"><component :is="copied ? 'CircleCheck' : 'Copy'" :size="16" class="me-2" :class="{ 'jd-copied-ico': copied }" /> {{ copied ? 'Copied!' : 'Copy Link' }}</button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" :href="shareWhatsApp" target="_blank" rel="noopener"><MessageCircle :size="16" class="me-2" /> WhatsApp</a></li>
                                <li><a class="dropdown-item" :href="shareFacebook" target="_blank" rel="noopener"><i class="bi bi-facebook me-2"></i> Facebook</a></li>
                                <li><a class="dropdown-item" :href="shareLinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin me-2"></i> LinkedIn</a></li>
                                <li><a class="dropdown-item" :href="shareTwitter" target="_blank" rel="noopener"><i class="bi bi-twitter-x me-2"></i> Twitter / X</a></li>
                                <li><a class="dropdown-item" :href="shareTelegram" target="_blank" rel="noopener"><Send :size="16" class="me-2" /> Telegram</a></li>
                                <li><a class="dropdown-item" :href="shareEmail"><Mail :size="16" class="me-2" /> Email</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BODY GRID -->
        <div class="jd-grid">
            <div class="jd-main">
                <!-- MATCH SCORE -->
                <div v-if="matchData" class="jd-card jd-card--glass mb-4">
                    <div class="jd-card-body">
                        <div class="jd-card-head">
                            <div>
                                <h5 class="jd-h mb-1">Your Match Score</h5>
                                <p class="jd-sub">Based on your profile, resume, and job requirements.</p>
                            </div>
                            <span class="jd-chip jd-chip--success">{{ matchData.recommendationLevel || 'Match' }}</span>
                        </div>
                        <div class="jd-match-row">
                            <div class="jd-match-value">{{ matchData.score }}<span>%</span></div>
                            <div class="jd-match-meta">
                                <div class="small text-muted">Recommended score: {{ matchData.recommendationScore || matchData.score }}%</div>
                                <div class="jd-match-bar"><span :style="{ width: matchData.score + '%' }"></span></div>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <div class="jd-subcard">
                                    <div class="small text-muted mb-2">Matched Skills</div>
                                    <div v-if="matchData.matchedSkills && matchData.matchedSkills.length" class="jd-skill-list">
                                        <span v-for="skill in matchData.matchedSkills" :key="skill" class="jd-skill jd-skill--ok"><CircleCheck :size="14" /> {{ ucfirst(skill) }}</span>
                                    </div>
                                    <div v-else class="text-muted small">No matched skills yet.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="jd-subcard">
                                    <div class="small text-muted mb-2">Missing Skills</div>
                                    <div v-if="matchData.missingSkills && matchData.missingSkills.length" class="jd-skill-list">
                                        <span v-for="skill in matchData.missingSkills" :key="skill" class="jd-skill jd-skill--miss">{{ ucfirst(skill) }}</span>
                                    </div>
                                    <div v-else class="text-muted small">No missing skills detected.</div>
                                </div>
                            </div>
                            <div class="col-sm-6"><div class="jd-subcard"><div class="small text-muted">Profile Completion</div><div class="fw-semibold">{{ matchData.profileCompletion }}%</div></div></div>
                            <div class="col-sm-6"><div class="jd-subcard"><div class="small text-muted">Resume Uploaded</div><div class="fw-semibold">{{ matchData.resumeUploaded ? 'Yes' : 'No' }}</div></div></div>
                            <div class="col-12"><div class="jd-subcard"><div class="small text-muted">Recommendation</div><div class="fw-semibold">{{ matchData.recommendationReason || 'Matches your profile and job requirements.' }}</div></div></div>
                            <div v-if="matchData.resumeAnalysis" class="col-sm-4"><div class="jd-subcard"><div class="small text-muted">ATS Score</div><div class="fw-semibold">{{ matchData.resumeAnalysis.atsScore || 0 }}%</div></div></div>
                            <div v-if="matchData.resumeAnalysis" class="col-sm-4"><div class="jd-subcard"><div class="small text-muted">ATS Readiness</div><div class="fw-semibold">{{ matchData.resumeAnalysis.estimatedATS || 'N/A' }}</div></div></div>
                            <div v-if="matchData.resumeAnalysis" class="col-sm-4"><div class="jd-subcard"><div class="small text-muted">ATS Keywords</div><div class="fw-semibold">{{ matchData.resumeAnalysis.keywordCount || 0 }}</div></div></div>
                        </div>
                    </div>
                </div>

                <!-- QUICK INFORMATION -->
                <div class="jd-card jd-card--blurple mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico"><Sparkles :size="18" /></span>
                            <h5 class="jd-h">Quick Information</h5>
                        </div>
                        <div class="jd-info-grid">
                            <div v-for="info in quickInfoItems" :key="info.label" class="jd-info-card">
                                <div class="jd-info-bg" aria-hidden="true"></div>
                                <div class="jd-info-ico"><component :is="info.icon" :size="22" /></div>
                                <div class="jd-info-label">{{ info.label }}</div>
                                <div class="jd-info-value">{{ info.value }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="jd-card jd-card--sky mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico"><FileText :size="18" /></span>
                            <h5 class="jd-h">Job Description</h5>
                        </div>
                        <div class="jd-prose">{{ job.description }}</div>
                    </div>
                </div>

                <!-- RESPONSIBILITIES -->
                <div class="jd-card jd-card--emerald mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico jd-section-ico--green"><ListChecks :size="18" /></span>
                            <h5 class="jd-h">Responsibilities</h5>
                        </div>
                        <div class="jd-checklist">
                            <div v-for="(item, i) in splitLines(job.responsibilities)" :key="'r' + i" class="jd-check-item">
                                <span class="jd-check-ico"><CircleCheck :size="20" /></span>
                                <span>{{ item }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REQUIREMENTS -->
                <div class="jd-card jd-card--blurple mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico jd-section-ico--amber"><ShieldCheck :size="18" /></span>
                            <h5 class="jd-h">Requirements</h5>
                        </div>
                        <div class="jd-req-grid">
                            <div v-for="(item, i) in splitLines(job.requirements)" :key="'q' + i" class="jd-req-card">
                                <span class="jd-req-ico"><CircleCheck :size="18" /></span>
                                <span>{{ item }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BENEFITS -->
                <div v-if="job.benefits && job.benefits !== 'N/A'" class="jd-card jd-card--amber mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico jd-section-ico--emerald"><Gift :size="18" /></span>
                            <h5 class="jd-h">Benefits</h5>
                        </div>
                        <div class="jd-benefit-grid">
                            <div v-for="(item, i) in splitLines(job.benefits)" :key="'b' + i" class="jd-benefit-card">
                                <span class="jd-benefit-ico"><Star :size="18" /></span>
                                <span>{{ item }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMPANY PROFILE -->
                <div class="jd-card jd-card--violet mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico"><Building2 :size="18" /></span>
                            <h5 class="jd-h">Company Profile</h5>
                        </div>
                        <div class="jd-company-profile">
                            <div class="jd-cp-logo">
                                <img v-if="companyLogoUrl" :src="companyLogoUrl" :alt="companyName + ' logo'" @error="logoFailed = true">
                                <div v-else class="jd-cp-logo-ph">{{ companyInitials }}</div>
                                <span v-if="company.verified_at" class="jd-cp-verified" title="Verified company"><BadgeCheck :size="20" stroke-width="2.5" /></span>
                            </div>
                            <div class="jd-cp-info">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h4 class="jd-cp-name mb-0">{{ companyName }}</h4>
                                    <span v-if="company.verified_at" class="jd-chip jd-chip--success"><BadgeCheck :size="13" /> Verified</span>
                                </div>
                                <div class="jd-cp-industry" v-if="company.industry">{{ company.industry }}</div>
                                <p class="jd-cp-desc small text-muted">{{ company.company_description || 'No description available.' }}</p>
                            </div>
                        </div>

                        <div class="jd-cp-stats">
                            <div class="jd-cp-stat" v-if="company.company_size"><Building2 :size="16" /><div><div class="small text-muted">Company Size</div><div class="fw-semibold">{{ company.company_size }}</div></div></div>
                            <div class="jd-cp-stat" v-if="company.website"><Globe :size="16" /><div class="jd-cp-stat-body"><div class="small text-muted">Website</div><div class="fw-semibold"><a :href="company.website" target="_blank" rel="noopener" class="text-primary text-decoration-none jd-link-break">{{ websiteHost }}</a></div></div></div>
                            <div class="jd-cp-stat" v-if="company.address || company.city || company.country"><MapPin :size="16" /><div><div class="small text-muted">Address</div><div class="fw-semibold">{{ company.address }}{{ company.city ? ', ' + company.city : '' }}{{ company.country ? ', ' + company.country : '' }}</div></div></div>
                            <div class="jd-cp-stat" v-if="company.employer_since"><Calendar :size="16" /><div><div class="small text-muted">Employer Since</div><div class="fw-semibold">{{ company.employer_since }}</div></div></div>
                            <div class="jd-cp-stat" v-if="typeof openJobsCount === 'number'"><BriefcaseBusiness :size="16" /><div><div class="small text-muted">Open Jobs</div><div class="fw-semibold">{{ openJobsCount }}</div></div></div>
                        </div>

                        <div class="jd-cp-socials" v-if="socialLinks.length">
                            <a v-for="(s, k) in socialLinks" :key="k" :href="s.url" target="_blank" rel="noopener" class="jd-social" :title="s.label">
                                <component :is="s.icon" :size="18" />
                            </a>
                        </div>

                        <div class="mt-3">
                            <a v-if="routes.company" :href="routes.company" class="jd-btn jd-btn--outline jd-btn--sm"><ArrowRight :size="16" /> View Company Profile</a>
                        </div>
                    </div>
                </div>

                <!-- ADDITIONAL COMPANY CARD -->
                <div class="jd-card jd-company-card2 mb-4">
                    <div class="jd-cc2-glow" aria-hidden="true"></div>
                    <div class="jd-card-body jd-cc2-body">
                        <div class="jd-cc2-logo">
                            <img v-if="companyLogoUrl" :src="companyLogoUrl" :alt="companyName + ' logo'" @error="logoFailed = true">
                            <div v-else class="jd-cc2-logo-ph">{{ companyInitials }}</div>
                        </div>
                        <div class="jd-cc2-meta">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="mb-0 fw-bold">{{ companyName }}</h5>
                                <span v-if="company.verified_at" class="jd-inline-verified"><BadgeCheck :size="16" stroke-width="2.5" /></span>
                            </div>
                            <div class="jd-cc2-tags">
                                <span v-if="company.industry"><Building2 :size="13" /> {{ company.industry }}</span>
                                <span v-if="company.website"><Globe :size="13" /> <span class="jd-cc2-website">{{ websiteHost }}</span></span>
                                <span v-if="job.location"><MapPin :size="13" /> {{ job.location }}</span>
                            </div>
                            <div class="jd-cc2-facts">
                                <span v-if="company.verified_at" class="jd-fact jd-fact--ok"><BadgeCheck :size="13" /> Verified</span>
                                <span v-if="company.company_size" class="jd-fact"><Users :size="13" /> {{ company.company_size }}</span>
                            </div>
                        </div>
                        <a v-if="routes.company" :href="routes.company" class="jd-btn jd-btn--primary jd-btn--sm ms-auto">View <ArrowRight :size="15" /></a>
                    </div>
                </div>

                <!-- RELATED JOBS -->
                <div class="jd-card jd-card--rose mb-4">
                    <div class="jd-card-body">
                        <div class="jd-section-head">
                            <span class="jd-section-ico"><BriefcaseBusiness :size="18" /></span>
                            <h5 class="jd-h">Related Jobs</h5>
                        </div>
                        <div v-if="relatedJobs.length" class="jd-related-grid">
                            <article v-for="rj in relatedJobs" :key="rj.id" class="jd-related-card">
                                <div class="jd-related-logo">
                                    <img v-if="rjLogo(rj)" :src="rjLogo(rj)" :alt="(rj.company?.company_name || 'Company') + ' logo'" @error="logoFailed = true">
                                    <span v-else class="jd-related-logo-ph">{{ companyInitialsFor(rj.company?.company_name) }}</span>
                                </div>
                                <div class="jd-related-body">
                                    <a :href="`/jobs/${rj.id}`" class="jd-related-title">{{ rj.title }}</a>
                                    <div class="jd-related-sub">{{ rj.company?.company_name || 'Company' }}</div>
                                    <div class="jd-related-meta">
                                        <span><MapPin :size="13" /> {{ rj.location || 'Bangladesh' }}</span>
                                        <span v-if="rj.salary_min || rj.salary_max"><DollarSign :size="13" /> {{ salaryTextFor(rj) }}</span>
                                        <span><BriefcaseBusiness :size="13" /> {{ rj.job_type }}</span>
                                    </div>
                                </div>
                                <div class="jd-related-actions">
                                    <button type="button" class="jd-rel-save" @click="() => {}"><Bookmark :size="16" /></button>
                                    <a :href="`/jobs/${rj.id}`" class="jd-btn jd-btn--primary jd-btn--sm">Apply <ArrowRight :size="14" /></a>
                                </div>
                            </article>
                        </div>
                        <div v-else class="jd-empty">
                            <div class="jd-empty-ico"><Sparkles :size="28" /></div>
                            <p class="mb-0 text-muted">Related job recommendations will appear here based on your profile and preferences.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR -->
            <aside class="jd-side">
                <div class="jd-side-sticky">
                    <div class="jd-card jd-side-apply">
                        <div class="jd-card-body">
                            <template v-if="user">
                                <template v-if="alreadyApplied">
                                    <button type="button" class="jd-btn jd-btn--done w-100 mb-2" disabled><CircleCheck :size="18" /> Already Applied</button>
                                </template>
                                <template v-else-if="canApply">
                                    <a :href="routes.apply" class="jd-btn jd-btn--primary w-100 mb-2"><Send :size="18" /> Apply Now</a>
                                </template>
                                <button v-if="userRoleSlug === 'job-seeker'" type="button" class="jd-btn jd-btn--save w-100 mb-2" :class="{ 'is-saved': isSaved }" @click="toggleSave">
                                    <Bookmark :size="18" :fill="isSaved ? '#f59e0b' : 'none'" :stroke="isSaved ? '#f59e0b' : 'currentColor'" />
                                    {{ isSaved ? 'Unsave Job' : 'Save Job' }}
                                </button>
                            </template>
                            <template v-else>
                                <a :href="routes.login" class="jd-btn jd-btn--primary w-100 mb-2"><LogIn :size="18" /> Login to Apply</a>
                            </template>
                            <div class="dropdown w-100 jd-dropdown" ref="shareWrapSide">
                                <button class="jd-btn jd-btn--ghost w-100" type="button" :aria-expanded="shareOpenSide" @click="toggleShare('side')"><Share2 :size="18" /> Share Job</button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 w-100 jd-share-menu" :class="{ show: shareOpenSide }" @click.stop>
                                <li><button type="button" class="dropdown-item" @click="copyLink"><component :is="copied ? 'CircleCheck' : 'Copy'" :size="16" class="me-2" :class="{ 'jd-copied-ico': copied }" /> {{ copied ? 'Copied!' : 'Copy Link' }}</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" :href="shareWhatsApp" target="_blank" rel="noopener"><MessageCircle :size="16" class="me-2" /> WhatsApp</a></li>
                                    <li><a class="dropdown-item" :href="shareFacebook" target="_blank" rel="noopener"><i class="bi bi-facebook me-2"></i> Facebook</a></li>
                                    <li><a class="dropdown-item" :href="shareLinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin me-2"></i> LinkedIn</a></li>
                                    <li><a class="dropdown-item" :href="shareTwitter" target="_blank" rel="noopener"><i class="bi bi-twitter-x me-2"></i> Twitter / X</a></li>
                                    <li><a class="dropdown-item" :href="shareTelegram" target="_blank" rel="noopener"><Send :size="16" class="me-2" /> Telegram</a></li>
                                    <li><a class="dropdown-item" :href="shareEmail"><i class="bi bi-envelope me-2"></i> Email</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="jd-card jd-card--glass jd-card--blurple">
                        <div class="jd-card-body">
                            <h6 class="jd-side-title">Quick Overview</h6>
                            <div class="jd-overview">
                                <div v-for="item in quickInfoItems" :key="item.label" class="jd-overview-item">
                                    <span class="jd-overview-ico"><component :is="item.icon" :size="16" /></span>
                                    <span class="jd-overview-label">{{ item.label }}</span>
                                    <span class="jd-overview-value">{{ item.value }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="jd-card jd-card--glass jd-card--emerald">
                        <div class="jd-card-body">
                            <h6 class="jd-side-title">About Company</h6>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="jd-side-logo">
                                    <img v-if="companyLogoUrl" :src="companyLogoUrl" :alt="companyName + ' logo'" @error="logoFailed = true">
                                    <span v-else class="jd-side-logo-ph">{{ companyInitials }}</span>
                                </div>
                                <div>
                                    <div class="fw-semibold small d-flex align-items-center gap-1">{{ companyName }} <span v-if="company.verified_at" class="jd-inline-verified"><BadgeCheck :size="14" stroke-width="2.5" /></span></div>
                                    <div class="small text-muted">{{ company.industry || 'Industry' }}</div>
                                </div>
                            </div>
                            <a v-if="routes.company" :href="routes.company" class="jd-btn jd-btn--outline jd-btn--sm w-100">View Profile <ArrowRight :size="15" /></a>
                        </div>
                    </div>

                    <div class="jd-card">
                        <div class="jd-card-body">
                            <button class="jd-btn jd-btn--report w-100" @click="reportJob"><Flag :size="18" /> Report Job</button>
                        </div>
                    </div>

                    <div v-if="canManage" class="jd-card">
                        <div class="jd-card-body">
                            <h6 class="jd-side-title">Manage Job</h6>
                            <div class="d-grid gap-2">
                                <a :href="routes.edit" class="jd-btn jd-btn--outline jd-btn--sm w-100"><Pencil :size="16" /> Edit Job</a>
                                <form :action="routes.destroy" method="POST" @submit.prevent="confirmDelete">
                                    <input type="hidden" name="_token" :value="csrfToken">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="jd-btn jd-btn--danger w-100"><Trash2 :size="16" /> Delete Job</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>

<script>
import {
    Bookmark, BadgeCheck, Star, Globe, Laptop, Building2, MapPin, BriefcaseBusiness, Banknote,
    CalendarClock, Clock3, ArrowRight, Sparkles, Flame, ArrowLeft, Send, CircleCheck,
    Share2, Copy, Mail, LogIn, Flag, Pencil, Trash2, MessageCircle,
    Users, DollarSign, Calendar, Timer, GraduationCap, FileText, ListChecks, ShieldCheck, Gift,
    Link,
} from '@lucide/vue';

function formatSalary(job) {
    const min = job.salary_min ? Number(job.salary_min) : null;
    const max = job.salary_max ? Number(job.salary_max) : null;
    if (!min && !max) return 'Negotiable';
    const fmt = (v) => (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : '' + v);
    const type = job.salary_type ? job.salary_type + ' ' : '';
    if (min && max) return type + '৳' + fmt(min) + '–' + fmt(max);
    if (min) return type + '৳' + fmt(min) + '+';
    return 'Up to ৳' + fmt(max);
}

function splitLines(text) {
    if (!text) return [];
    return String(text)
        .split(/\r?\n/)
        .map((l) => l.replace(/^[\s•\-*]+/, '').trim())
        .filter((l) => l.length > 0);
}

export default {
    name: 'JobDetails',
    components: {
        Bookmark, BadgeCheck, Star, Globe, Laptop, Building2, MapPin, BriefcaseBusiness, Banknote,
        CalendarClock, Clock3, ArrowRight, Sparkles, Flame, ArrowLeft, Send, CircleCheck,
        Share2, Copy, Mail, LogIn, Flag, Pencil, Trash2, MessageCircle,
        Users, DollarSign, Calendar, Timer, GraduationCap, FileText, ListChecks, ShieldCheck, Gift,
        Link,
    },
    props: {
        job: { type: Object, required: true },
        matchData: { type: Object, default: null },
        saved: { type: Boolean, default: false },
        authUserId: { type: [Number, String], default: null },
        user: { type: Object, default: null },
        alreadyApplied: { type: Boolean, default: false },
        canApply: { type: Boolean, default: false },
        canManage: { type: Boolean, default: false },
        csrfToken: { type: String, default: '' },
        routes: { type: Object, default: () => ({}) },
        relatedJobs: { type: Array, default: () => [] },
        openJobsCount: { type: [Number, String], default: null },
    },
    data() {
        return {
            logoFailed: false,
            isSaved: this.saved,
            shareOpen: false,
            shareOpenSide: false,
            copied: false,
        };
    },
    mounted() {
        document.addEventListener('click', this.onDocClick);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.onDocClick);
    },
    computed: {
        company() {
            return this.job.company || {};
        },
        companyName() {
            return this.company.company_name || 'Company';
        },
        companyInitials() {
            const parts = (this.companyName || 'C').trim().split(/\s+/);
            const initials = parts.slice(0, 2).map((p) => p[0]).join('');
            return (initials || 'C').toUpperCase();
        },
        companyLogoUrl() {
            if (this.logoFailed || !this.company.company_logo) return null;
            if (/^https?:\/\//.test(this.company.company_logo)) return this.company.company_logo;
            if (this.company.company_logo.startsWith('/')) return this.company.company_logo;
            return `/storage/company-logos/${this.company.company_logo}`;
        },
        isFeatured() {
            return this.job.featured === true || this.job.is_featured === true;
        },
        isUrgent() {
            return this.job.urgent === true || this.job.is_urgent === true;
        },
        isRemote() {
            return this.job.workplace === 'remote';
        },
        userRoleSlug() {
            return this.user?.role_slug || this.user?.role?.slug || null;
        },
        jobUrl() {
            return `${window.location.origin}/jobs/${this.job.id}`;
        },
        shareFacebook() {
            return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.jobUrl)}`;
        },
        shareLinkedIn() {
            return `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.jobUrl)}`;
        },
        shareTwitter() {
            return `https://twitter.com/intent/tweet?url=${encodeURIComponent(this.jobUrl)}&text=${encodeURIComponent('Check out this job: ' + this.job.title)}`;
        },
        shareEmail() {
            return `mailto:?subject=${encodeURIComponent('Job opportunity: ' + this.job.title)}&body=${encodeURIComponent(this.jobUrl)}`;
        },
        shareWhatsApp() {
            return `https://api.whatsapp.com/send?text=${encodeURIComponent('Check out this job: ' + this.job.title + ' ' + this.jobUrl)}`;
        },
        shareTelegram() {
            return `https://t.me/share/url?url=${encodeURIComponent(this.jobUrl)}&text=${encodeURIComponent('Check out this job: ' + this.job.title)}`;
        },
        salaryText() {
            return formatSalary(this.job);
        },
        websiteHost() {
            const raw = this.company.website || '';
            if (!raw) return '';
            try {
                return new URL(raw).host.replace(/^www\./, '');
            } catch (e) {
                return raw.replace(/^https?:\/\//, '').replace(/^www\./, '');
            }
        },
        socialLinks() {
            const links = [];
            const raw = this.company.social_links || this.company.social || {};
            const map = {
                linkedin: { icon: 'Link', label: 'LinkedIn' },
                twitter: { icon: 'Link', label: 'Twitter' },
                facebook: { icon: 'Link', label: 'Facebook' },
                instagram: { icon: 'Link', label: 'Instagram' },
                youtube: { icon: 'Link', label: 'YouTube' },
                website: { icon: 'Globe', label: 'Website' },
                email: { icon: 'Mail', label: 'Email' },
            };
            Object.keys(map).forEach((k) => {
                if (raw[k]) links.push({ ...map[k], url: raw[k] });
            });
            return links;
        },
        quickInfoItems() {
            const items = [
                { label: 'Salary', value: this.salaryText, icon: DollarSign },
                { label: 'Vacancy', value: this.job.vacancy, icon: Users },
                { label: 'Experience', value: this.job.experience_level, icon: Timer },
                { label: 'Education', value: this.job.education_level, icon: GraduationCap },
                { label: 'Workplace', value: this.job.workplace, icon: Building2 },
                { label: 'Employment Type', value: this.job.employment_status, icon: BriefcaseBusiness },
                { label: 'Deadline', value: this.job.deadline ? new Date(this.job.deadline).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A', icon: Calendar },
                { label: 'Published', value: this.job.published_at ? new Date(this.job.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A', icon: CalendarClock },
            ];
            return items;
        },
    },
    methods: {
        onDocClick(e) {
            if (this.shareOpen && this.$refs.shareWrap && !this.$refs.shareWrap.contains(e.target)) this.shareOpen = false;
            if (this.shareOpenSide && this.$refs.shareWrapSide && !this.$refs.shareWrapSide.contains(e.target)) this.shareOpenSide = false;
        },
        toggleShare(which) {
            if (which === 'side') {
                this.shareOpenSide = !this.shareOpenSide;
                this.shareOpen = false;
            } else {
                this.shareOpen = !this.shareOpen;
                this.shareOpenSide = false;
            }
        },
        splitLines(text) {
            return splitLines(text);
        },
        salaryTextFor(job) {
            return formatSalary(job);
        },
        rjLogo(rj) {
            const raw = rj.company?.company_logo;
            if (!raw) return null;
            if (/^https?:\/\//.test(raw)) return raw;
            if (raw.startsWith('/')) return raw;
            return `/storage/company-logos/${raw}`;
        },
        companyInitialsFor(name) {
            const parts = (name || 'C').trim().split(/\s+/);
            const initials = parts.slice(0, 2).map((p) => p[0]).join('');
            return (initials || 'C').toUpperCase();
        },
        ucfirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        goBack() {
            window.location.href = this.routes.jobs || '/jobs';
        },
        toggleSave() {
            this.isSaved = !this.isSaved;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.routes.save || '/';
            form.innerHTML = `<input type="hidden" name="_token" value="${this.csrfToken}"><input type="hidden" name="_method" value="POST">`;
            document.body.appendChild(form);
            form.submit();
        },
        copyLink() {
            const onCopied = () => {
                this.copied = true;
                this.toast('Link copied successfully.');
                clearTimeout(this.copyTimeout);
                this.copyTimeout = setTimeout(() => { this.copied = false; }, 2000);
            };
            const onError = () => prompt('Copy this link:', this.jobUrl);

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(this.jobUrl).then(onCopied, () => {
                    if (this.fallbackCopy()) onCopied(); else onError();
                });
            } else if (this.fallbackCopy()) {
                onCopied();
            } else {
                onError();
            }
        },
        fallbackCopy() {
            try {
                const ta = document.createElement('textarea');
                ta.value = this.jobUrl;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                const ok = document.execCommand('copy');
                document.body.removeChild(ta);
                return ok;
            } catch (e) {
                return false;
            }
        },
        reportJob() {
            const reason = prompt('Why are you reporting this job?');
            if (reason !== null) this.toast('Thank you. We will review this report.');
        },
        toast(message) {
            const el = document.createElement('div');
            el.className = 'jd-toast';
            el.textContent = message;
            document.body.appendChild(el);
            requestAnimationFrame(() => el.classList.add('is-show'));
            setTimeout(() => {
                el.classList.remove('is-show');
                setTimeout(() => el.remove(), 300);
            }, 2400);
        },
        confirmDelete() {
            return confirm('Delete this job?');
        },
    },
};
</script>

<style scoped>
:root {
    --jd-primary: #2563EB;
    --jd-secondary: #10B981;
    --jd-accent: #F59E0B;
    --jd-danger: #EF4444;
    --jd-bg: #F8FAFC;
    --jd-card: #FFFFFF;
    --jd-border: #E5E7EB;
    --jd-ink: #0F172A;
    --jd-muted: #64748B;
}

.jd-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--jd-ink);
    animation: jd-fade 0.6s ease both;
}

@keyframes jd-fade {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Breadcrumb */
.jd-breadcrumb-list {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
    font-size: 0.85rem;
    color: var(--jd-muted);
}
.jd-breadcrumb-list a { color: var(--jd-muted); text-decoration: none; transition: color 0.2s; }
.jd-breadcrumb-list a:hover { color: var(--jd-primary); }
.jd-bc-sep { opacity: 0.5; }
.jd-breadcrumb-list .active { color: var(--jd-ink); font-weight: 600; }

/* ===== HERO ===== */
.jd-hero {
    position: relative;
    border-radius: 28px;
    overflow: hidden;
    background:
        radial-gradient(120% 120% at 0% 0%, #1e3a8a 0%, #1d4ed8 35%, #2563eb 65%, #3b82f6 100%);
    padding: clamp(2rem, 4vw, 3.5rem);
    margin-bottom: 2.5rem;
    box-shadow: 0 30px 60px -25px rgba(37, 99, 235, 0.55);
    isolation: isolate;
}
.jd-hero-bg { position: absolute; inset: 0; overflow: hidden; z-index: -1; }
.jd-hero-glow {
    position: absolute; width: 520px; height: 520px; border-radius: 50%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.55), transparent 70%);
    top: -180px; right: -120px; filter: blur(40px);
    animation: jd-float 9s ease-in-out infinite;
}
.jd-shape { position: absolute; border-radius: 50%; filter: blur(70px); opacity: 0.45; }
.jd-shape--1 { width: 260px; height: 260px; background: #7c3aed; bottom: -90px; left: -40px; animation: jd-float 11s ease-in-out infinite reverse; }
.jd-shape--2 { width: 200px; height: 200px; background: #06b6d4; top: 30%; right: 18%; animation: jd-pulse 6s ease-in-out infinite; }
.jd-shape--3 { width: 160px; height: 160px; background: #f59e0b; bottom: 20%; left: 30%; opacity: 0.3; animation: jd-pulse 8s ease-in-out infinite; }
.jd-grid-lines {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: radial-gradient(circle at 70% 20%, black, transparent 70%);
}
@keyframes jd-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-22px); } }
@keyframes jd-pulse { 0%,100% { opacity: 0.25; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.12); } }

.jd-hero-inner { display: grid; grid-template-columns: 300px 1fr; gap: clamp(1.5rem, 3vw, 3rem); align-items: center; }

/* Company image card */
.jd-hero-visual { display: flex; justify-content: center; }
.jd-company-card {
    position: relative;
    width: 100%;
    max-width: 280px;
    aspect-ratio: 1 / 1;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.35);
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255,255,255,0.4);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.4s ease;
    animation: jd-rise 0.7s ease both;
}
.jd-company-card:hover { transform: translateY(-8px) scale(1.02); }
.jd-company-card-glow {
    position: absolute; inset: -2px; border-radius: 26px; z-index: -1;
    background: linear-gradient(135deg, #60a5fa, #a78bfa, #f59e0b);
    opacity: 0.6; filter: blur(22px);
}
.jd-company-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex; align-items: center; justify-content: center;
    border-radius: 16px; overflow: hidden;
}
.jd-company-img {
    width: 100%; height: 100%; object-fit: contain;
    border-radius: 16px;
    transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.jd-company-card:hover .jd-company-img { transform: scale(1.08); }
.jd-company-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 3.5rem;
    color: #fff;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
}
.jd-company-verified {
    position: absolute; bottom: -10px; right: -10px;
    width: 38px; height: 38px; border-radius: 50%;
    background: var(--jd-secondary); color: #fff;
    display: flex; align-items: center; justify-content: center;
    border: 3px solid #2563eb; box-shadow: 0 6px 16px rgba(16,185,129,0.5);
}
.jd-company-card-name {
    margin-top: 1rem; color: #fff; font-weight: 700; font-size: 0.95rem;
    font-family: 'Poppins', sans-serif; text-align: center;
}

/* Hero main */
.jd-hero-toprow { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.jd-hero-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.8rem, 3.5vw, 2.9rem);
    font-weight: 700; line-height: 1.15; color: #fff;
    margin-bottom: 0.9rem; letter-spacing: -0.01em;
}
.jd-hero-company { display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1.1rem; }
.jd-hero-company-name { color: rgba(255,255,255,0.95); font-weight: 600; font-size: 1.05rem; }
.jd-hero-dot { color: rgba(255,255,255,0.6); font-size: 0.95rem; position: relative; padding-left: 0.9rem; }
.jd-hero-dot::before { content: ''; position: absolute; left: 0; top: 50%; width: 4px; height: 4px; border-radius: 50%; background: rgba(255,255,255,0.5); transform: translateY(-50%); }
.jd-inline-verified { color: #34d399; display: inline-flex; }

.jd-hero-meta { display: flex; flex-wrap: wrap; gap: 0.55rem; margin-bottom: 1.75rem; }
.jd-meta-pill {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 0.95rem; border-radius: 999px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    color: #e5e7eb; font-size: 0.85rem; font-weight: 500;
    backdrop-filter: blur(6px);
}
.jd-meta-pill--remote { background: rgba(16,185,129,0.22); border-color: rgba(16,185,129,0.4); color: #a7f3d0; }
.jd-meta-pill--hybrid { background: rgba(139,92,246,0.22); border-color: rgba(139,92,246,0.4); color: #ddd6fe; }
.jd-meta-pill--onsite { background: rgba(15,23,42,0.25); border-color: rgba(255,255,255,0.3); }
.jd-meta-pill--salary { background: rgba(245,158,11,0.2); border-color: rgba(245,158,11,0.4); color: #fde68a; }

.jd-hero-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; }

/* ===== BUTTONS ===== */
.jd-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.8rem 1.5rem; border-radius: 0.9rem;
    font-weight: 600; font-size: 0.95rem; border: 0; cursor: pointer;
    text-decoration: none; transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap; position: relative; overflow: hidden;
}
.jd-btn--sm { padding: 0.55rem 1rem; font-size: 0.85rem; border-radius: 0.75rem; }
.jd-btn--primary {
    background: linear-gradient(135deg, #ffffff, #e0e7ff); color: #1d4ed8;
    box-shadow: 0 10px 28px rgba(0,0,0,0.2);
}
.jd-btn--primary:hover { transform: translateY(-3px); color: #1e40af; box-shadow: 0 16px 36px rgba(0,0,0,0.28); }
.jd-btn--save {
    background: rgba(255,255,255,0.14); color: #fff; border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(6px);
}
.jd-btn--save:hover { background: rgba(255,255,255,0.24); color: #fff; transform: translateY(-3px); }
.jd-btn--save.is-saved { background: rgba(245,158,11,0.22); border-color: rgba(245,158,11,0.5); color: #fde68a; }
.jd-btn--outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.4); }
.jd-btn--outline:hover { background: rgba(255,255,255,0.12); color: #fff; transform: translateY(-2px); }
.jd-btn--ghost { background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.2); }
.jd-btn--ghost:hover { background: rgba(255,255,255,0.16); color: #fff; }
.jd-btn--done { background: rgba(16,185,129,0.2); color: #a7f3d0; border: 1px solid rgba(16,185,129,0.4); cursor: default; }
.jd-btn--danger { background: var(--jd-danger); color: #fff; }
.jd-btn--danger:hover { background: #dc2626; color: #fff; transform: translateY(-2px); }
.jd-btn--report { background: rgba(239,68,68,0.08); color: var(--jd-danger); border: 1px solid rgba(239,68,68,0.2); }
.jd-btn--report:hover { background: rgba(239,68,68,0.14); color: var(--jd-danger); }
.jd-back {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1rem; border-radius: 999px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    color: #fff; font-size: 0.85rem; font-weight: 500; cursor: pointer;
    backdrop-filter: blur(6px); transition: all 0.2s;
}
.jd-back:hover { background: rgba(255,255,255,0.22); transform: translateX(-2px); }

/* ripple */
.jd-btn::after {
    content: ''; position: absolute; top: 50%; left: 50%;
    width: 5px; height: 5px; background: rgba(255,255,255,0.6); border-radius: 50%;
    transform: translate(-50%, -50%) scale(0); opacity: 0;
}
.jd-btn:active::after { animation: jd-ripple 0.6s ease-out; }
@keyframes jd-ripple { to { transform: translate(-50%, -50%) scale(30); opacity: 0; } }

/* self-contained share dropdown */
.jd-dropdown { position: relative; }
.jd-share-menu {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 0.5rem);
    min-width: 220px;
    z-index: 50;
    padding: 0.5rem;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 18px 40px rgba(15,23,42,0.18);
    transform-origin: top right;
    animation: jd-menu-in 0.18s ease;
}
.jd-share-menu.show { display: block; }
.jd-share-menu .dropdown-item {
    display: flex; align-items: center;
    padding: 0.6rem 0.85rem; border-radius: 10px;
    font-size: 0.88rem; color: #334155; font-weight: 500;
}
.jd-share-menu .dropdown-item:hover { background: rgba(37,99,235,0.08); color: #1d4ed8; }
.jd-share-menu .dropdown-item .jd-copied-ico { color: #10b981; }
@keyframes jd-menu-in { from { opacity: 0; transform: translateY(-6px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }

/* ===== GRID ===== */
.jd-grid { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }
.jd-main { display: flex; flex-direction: column; gap: 1.5rem; min-width: 0; }
.jd-side { min-width: 0; }

/* ===== CARDS ===== */
.jd-card {
    background: var(--jd-card); border: 1px solid var(--jd-border);
    border-radius: 22px; box-shadow: 0 4px 24px rgba(15,23,42,0.04);
    overflow: hidden; transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
}
.jd-card:hover { box-shadow: 0 12px 40px rgba(15,23,42,0.09); }
.jd-card--glass {
    background: linear-gradient(180deg, rgba(37,99,235,0.03), #fff 60%);
    border-color: rgba(37,99,235,0.12);
}
/* distinct per-section card skins (hand-picked hues) */
.jd-card--blurple {
    background: linear-gradient(180deg, #eef2ff 0%, #ffffff 55%);
    border-color: rgba(99,102,241,0.22);
    border-width: 1px;
    border-style: solid;
}
.jd-card--emerald {
    background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 60%);
    border-color: rgba(16,185,129,0.24);
}
.jd-card--amber {
    background: linear-gradient(180deg, #fffbeb 0%, #ffffff 60%);
    border-color: rgba(245,158,11,0.26);
}
.jd-card--rose {
    background: linear-gradient(180deg, #fff1f2 0%, #ffffff 60%);
    border-color: rgba(244,63,94,0.22);
}
.jd-card--sky {
    background: linear-gradient(180deg, #ecfeff 0%, #ffffff 60%);
    border-color: rgba(6,182,212,0.24);
}
.jd-card--violet {
    background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 60%);
    border-color: rgba(139,92,246,0.24);
}
.jd-card-body { padding: clamp(1.25rem, 2.5vw, 1.9rem); }
.jd-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; gap: 1rem; }

.jd-h { font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 600; color: var(--jd-ink); margin: 0; }
.jd-sub { color: var(--jd-muted); margin: 0; font-size: 0.88rem; }
.jd-section-head { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.4rem; }
.jd-section-ico {
    width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    background: rgba(37,99,235,0.1); color: var(--jd-primary);
}
.jd-section-ico--green { background: rgba(16,185,129,0.12); color: #059669; }
.jd-section-ico--amber { background: rgba(245,158,11,0.12); color: #d97706; }
.jd-section-ico--emerald { background: rgba(16,185,129,0.12); color: #059669; }
.jd-section-ico--sky { background: rgba(6,182,212,0.12); color: #0891b2; }
.jd-section-ico--rose { background: rgba(244,63,94,0.12); color: #e11d48; }
.jd-section-ico--violet { background: rgba(139,92,246,0.12); color: #7c3aed; }

/* chips */
.jd-chip {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.4rem 0.85rem; border-radius: 999px;
    font-size: 0.74rem; font-weight: 600; letter-spacing: 0.03em; text-transform: uppercase;
}
.jd-chip--soft { background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25); color: #e5e7eb; }
.jd-chip--featured { background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.45); color: #fde68a; }
.jd-chip--urgent { background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.45); color: #fca5a5; }
.jd-chip--success { background: rgba(16,185,129,0.14); border: 1px solid rgba(16,185,129,0.3); color: #059669; }

/* ===== MATCH ===== */
.jd-match-row { display: flex; align-items: center; gap: 1.75rem; margin-bottom: 1.4rem; }
.jd-match-value { font-family: 'Poppins', sans-serif; font-size: 3.25rem; font-weight: 700; color: var(--jd-ink); line-height: 1; }
.jd-match-value span { font-size: 1.4rem; color: var(--jd-muted); margin-left: 2px; }
.jd-match-meta { flex: 1; }
.jd-match-bar { height: 10px; border-radius: 999px; background: #e5e7eb; overflow: hidden; margin-top: 0.5rem; }
.jd-match-bar span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, #10b981, #34d399); transition: width 1s ease; }
.jd-subcard { border: 1px solid #e0e7ff; border-radius: 14px; padding: 1rem; background: #fbfcff; transition: border-color 0.25s, box-shadow 0.25s; }
.jd-subcard:hover { border-color: rgba(99,102,241,0.4); box-shadow: 0 6px 20px rgba(99,102,241,0.08); }
.jd-skill-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.jd-skill { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.7rem; border-radius: 999px; font-size: 0.82rem; font-weight: 500; }
.jd-skill--ok { background: rgba(16,185,129,0.1); color: #047857; border: 1px solid rgba(16,185,129,0.2); }
.jd-skill--miss { background: rgba(239,68,68,0.1); color: #b91c1c; border: 1px solid rgba(239,68,68,0.2); }

/* ===== QUICK INFO CARDS ===== */
.jd-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.jd-info-card {
    position: relative; overflow: hidden;
    border: 1px solid #e0e7ff; border-radius: 18px; padding: 1.25rem 1.1rem;
    background: linear-gradient(180deg, #fbfcff, #ffffff); transition: transform 0.3s cubic-bezier(0.22,1,0.36,1), box-shadow 0.3s, border-color 0.3s;
}
.jd-info-card:nth-child(4n+1) { border-color: rgba(37,99,235,0.22); background: linear-gradient(180deg, #eff6ff, #ffffff); }
.jd-info-card:nth-child(4n+2) { border-color: rgba(16,185,129,0.22); background: linear-gradient(180deg, #ecfdf5, #ffffff); }
.jd-info-card:nth-child(4n+3) { border-color: rgba(245,158,11,0.22); background: linear-gradient(180deg, #fffbeb, #ffffff); }
.jd-info-card:nth-child(4n+4) { border-color: rgba(139,92,246,0.22); background: linear-gradient(180deg, #f5f3ff, #ffffff); }
.jd-info-card::before {
    content: ''; position: absolute; inset: 0; border-radius: 18px; padding: 1px;
    background: linear-gradient(135deg, rgba(37,99,235,0.5), rgba(16,185,129,0.4), transparent);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    opacity: 0; transition: opacity 0.3s;
}
.jd-info-card:hover { transform: translateY(-6px); box-shadow: 0 16px 36px rgba(15,23,42,0.1); }
.jd-info-card:nth-child(4n+1):hover { border-color: rgba(37,99,235,0.5); }
.jd-info-card:nth-child(4n+2):hover { border-color: rgba(16,185,129,0.5); }
.jd-info-card:nth-child(4n+3):hover { border-color: rgba(245,158,11,0.5); }
.jd-info-card:nth-child(4n+4):hover { border-color: rgba(139,92,246,0.5); }
.jd-info-bg {
    position: absolute; top: -30px; right: -30px; width: 90px; height: 90px;
    border-radius: 50%; background: radial-gradient(circle, rgba(37,99,235,0.08), transparent 70%);
    transition: transform 0.4s;
}
.jd-info-card:hover .jd-info-bg { transform: scale(1.6); }
.jd-info-ico {
    width: 44px; height: 44px; border-radius: 12px; margin-bottom: 0.85rem;
    display: inline-flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, rgba(37,99,235,0.12), rgba(16,185,129,0.12)); color: var(--jd-primary);
    position: relative;
}
.jd-info-label { font-size: 0.78rem; color: var(--jd-muted); font-weight: 500; }
.jd-info-value { font-weight: 700; font-size: 1.02rem; color: var(--jd-ink); margin-top: 0.15rem; font-family: 'Poppins', sans-serif; }

/* ===== PROSE ===== */
.jd-prose { color: #334155; line-height: 1.85; font-size: 0.96rem; white-space: pre-line; }
.jd-prose::before {
    content: ''; display: block; width: 4px; height: 100%; float: left; margin-right: 1.1rem;
    border-radius: 999px; background: linear-gradient(180deg, #2563eb, #10b981);
}

/* ===== CHECKLIST ===== */
.jd-checklist { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
.jd-check-item {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 1rem 1.1rem; border-radius: 14px; background: linear-gradient(180deg, #ecfdf5, #ffffff);
    border: 1px solid rgba(16,185,129,0.18); color: #334155; font-size: 0.92rem;
    transition: all 0.25s ease;
}
.jd-check-item:hover { background: #f0fdf4; border-color: rgba(16,185,129,0.45); transform: translateX(4px); }
.jd-check-ico { color: #10b981; flex-shrink: 0; margin-top: 1px; }

/* ===== REQUIREMENTS ===== */
.jd-req-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
.jd-req-card {
    display: flex; align-items: flex-start; gap: 0.7rem;
    padding: 1rem 1.1rem; border-radius: 14px; background: linear-gradient(180deg, #eff6ff, #ffffff);
    border: 1px solid rgba(37,99,235,0.18); color: #334155; font-size: 0.92rem;
    transition: all 0.25s ease;
}
.jd-req-card:hover { border-color: var(--jd-primary); box-shadow: 0 8px 22px rgba(37,99,235,0.1); transform: translateY(-3px); }
.jd-req-ico { color: var(--jd-primary); flex-shrink: 0; margin-top: 1px; }

/* ===== BENEFITS ===== */
.jd-benefit-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.85rem; }
.jd-benefit-card {
    display: flex; align-items: flex-start; gap: 0.7rem;
    padding: 1.1rem 1.15rem; border-radius: 16px; color: #1e293b; font-size: 0.9rem; font-weight: 500;
    background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(37,99,235,0.08));
    border: 1px solid rgba(16,185,129,0.18);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
}
.jd-benefit-card:nth-child(3n+2) { background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(244,63,94,0.06)); border-color: rgba(245,158,11,0.2); }
.jd-benefit-card:nth-child(3n+3) { background: linear-gradient(135deg, rgba(139,92,246,0.1), rgba(99,102,241,0.06)); border-color: rgba(139,92,246,0.2); }
.jd-benefit-card:hover { transform: translateY(-5px); box-shadow: 0 14px 30px rgba(16,185,129,0.18); border-color: rgba(16,185,129,0.4); }
.jd-benefit-card:nth-child(3n+2):hover { box-shadow: 0 14px 30px rgba(245,158,11,0.18); border-color: rgba(245,158,11,0.45); }
.jd-benefit-card:nth-child(3n+3):hover { box-shadow: 0 14px 30px rgba(139,92,246,0.18); border-color: rgba(139,92,246,0.45); }
.jd-benefit-ico { color: #059669; flex-shrink: 0; margin-top: 1px; }

/* ===== COMPANY PROFILE ===== */
.jd-company-profile { display: flex; gap: 1.5rem; align-items: flex-start; flex-wrap: wrap; margin-bottom: 1.5rem; }
.jd-cp-logo { position: relative; width: 96px; height: 96px; border-radius: 20px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; border: 1px solid var(--jd-border); display: flex; align-items: center; justify-content: center; }
.jd-cp-logo img { width: 100%; height: 100%; object-fit: contain; }
.jd-cp-logo-ph { font-family: 'Poppins'; font-weight: 800; font-size: 2rem; color: var(--jd-primary); }
.jd-cp-verified { position: absolute; bottom: -6px; right: -6px; width: 30px; height: 30px; border-radius: 50%; background: var(--jd-secondary); color: #fff; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; box-shadow: 0 3px 8px rgba(16,185,129,0.4); }
.jd-cp-info { flex: 1; min-width: 200px; }
.jd-cp-name { font-family: 'Poppins'; font-size: 1.35rem; font-weight: 700; }
.jd-cp-industry { color: var(--jd-primary); font-weight: 600; font-size: 0.9rem; margin: 0.2rem 0 0.6rem; }
.jd-cp-desc { line-height: 1.7; }
.jd-cp-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.85rem; margin-bottom: 1.25rem; }
.jd-cp-stat { display: flex; align-items: center; gap: 0.7rem; padding: 0.9rem 1rem; border-radius: 14px; background: linear-gradient(180deg, #f5f3ff, #ffffff); border: 1px solid rgba(139,92,246,0.16); min-width: 0; }
.jd-cp-stat-body { min-width: 0; overflow: hidden; }
.jd-link-break { display: inline-block; max-width: 100%; overflow-wrap: anywhere; word-break: break-word; }
.jd-cp-stat svg { color: var(--jd-primary); flex-shrink: 0; }
.jd-cp-socials { display: flex; gap: 0.6rem; flex-wrap: wrap; }
.jd-social { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: #475569; border: 1px solid var(--jd-border); transition: all 0.25s; }
.jd-social:hover { background: var(--jd-primary); color: #fff; transform: translateY(-3px); }

/* ===== ADDITIONAL COMPANY CARD ===== */
.jd-company-card2 { position: relative; overflow: hidden; border: 1px solid rgba(245,158,11,0.28); background: linear-gradient(180deg, #fffbeb 0%, #ffffff 65%); }
.jd-cc2-glow { position: absolute; width: 280px; height: 280px; border-radius: 50%; background: radial-gradient(circle, rgba(245,158,11,0.18), transparent 70%); top: -100px; right: -80px; filter: blur(30px); }
.jd-cc2-body { display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; position: relative; }
.jd-cc2-logo { width: 72px; height: 72px; border-radius: 18px; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid rgba(245,158,11,0.3); display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 16px rgba(245,158,11,0.12); }
.jd-cc2-logo img { width: 100%; height: 100%; object-fit: contain; }
.jd-cc2-logo-ph { font-family: 'Poppins'; font-weight: 800; font-size: 1.6rem; color: #d97706; }
.jd-cc2-meta { flex: 1; min-width: 200px; }
.jd-cc2-tags { display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; margin-top: 0.4rem; color: var(--jd-muted); font-size: 0.82rem; }
.jd-cc2-tags span { display: inline-flex; align-items: center; gap: 0.3rem; min-width: 0; }
.jd-cc2-tags svg { color: #d97706; flex-shrink: 0; }
.jd-cc2-website { overflow-wrap: anywhere; word-break: break-word; }
.jd-cc2-facts { display: flex; gap: 0.5rem; margin-top: 0.6rem; }
.jd-fact { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.78rem; padding: 0.3rem 0.7rem; border-radius: 999px; background: #f1f5f9; color: #475569; font-weight: 500; }
.jd-fact--ok { background: rgba(16,185,129,0.12); color: #059669; }

/* ===== RELATED ===== */
.jd-related-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.jd-related-card { display: flex; gap: 1rem; align-items: center; padding: 1.1rem; border-radius: 18px; border: 1px solid rgba(6,182,212,0.2); background: linear-gradient(180deg, #ecfeff, #ffffff); transition: all 0.3s cubic-bezier(0.22,1,0.36,1); }
.jd-related-card:nth-child(even) { border-color: rgba(99,102,241,0.2); background: linear-gradient(180deg, #eef2ff, #ffffff); }
.jd-related-card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(6,182,212,0.14); border-color: #06b6d4; }
.jd-related-card:nth-child(even):hover { box-shadow: 0 16px 36px rgba(99,102,241,0.14); border-color: #6366f1; }
.jd-related-logo { width: 56px; height: 56px; border-radius: 14px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; border: 1px solid var(--jd-border); display: flex; align-items: center; justify-content: center; }
.jd-related-logo img { width: 100%; height: 100%; object-fit: contain; }
.jd-related-logo-ph { font-family: 'Poppins'; font-weight: 800; color: var(--jd-primary); }
.jd-related-body { flex: 1; min-width: 0; }
.jd-related-title { display: block; font-weight: 700; color: var(--jd-ink); text-decoration: none; font-size: 0.95rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.jd-related-title:hover { color: var(--jd-primary); }
.jd-related-sub { font-size: 0.82rem; color: var(--jd-muted); margin: 0.1rem 0 0.4rem; }
.jd-related-meta { display: flex; flex-wrap: wrap; gap: 0.4rem 0.75rem; font-size: 0.76rem; color: var(--jd-muted); }
.jd-related-meta span { display: inline-flex; align-items: center; gap: 0.25rem; }
.jd-related-meta svg { color: var(--jd-primary); }
.jd-related-actions { display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end; flex-shrink: 0; }
.jd-rel-save { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--jd-border); background: #fff; color: #94a3b8; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.25s; }
.jd-rel-save:hover { color: var(--jd-accent); border-color: var(--jd-accent); background: rgba(245,158,11,0.08); }

.jd-empty { text-align: center; padding: 2.5rem 1rem; }
.jd-empty-ico { width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; background: rgba(37,99,235,0.08); color: var(--jd-primary); }

/* ===== SIDEBAR ===== */
.jd-side-sticky { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 1.25rem; }
.jd-side-apply { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; }
.jd-side-apply .jd-card-body { padding: 1.5rem; }
.jd-side-apply .jd-btn--primary { background: #fff; color: #1d4ed8; }
.jd-side-apply .jd-btn--primary:hover { background: #eef2ff; }
.jd-side-apply .jd-btn--save { background: rgba(255,255,255,0.16); border-color: rgba(255,255,255,0.35); color: #fff; }
.jd-side-apply .jd-btn--save.is-saved { background: rgba(245,158,11,0.25); border-color: rgba(245,158,11,0.5); color: #fde68a; }
.jd-side-apply .jd-btn--ghost { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); color: #fff; }
.jd-side-apply .dropdown { width: 100%; }
.jd-side-title { font-family: 'Poppins'; font-size: 1rem; font-weight: 600; color: var(--jd-ink); margin-bottom: 1rem; }
.jd-overview { display: flex; flex-direction: column; gap: 0.1rem; }
.jd-overview-item { display: grid; grid-template-columns: 22px 1fr auto; align-items: center; gap: 0.6rem; padding: 0.7rem 0; border-bottom: 1px solid #f1f5f9; }
.jd-overview-item:last-child { border-bottom: none; padding-bottom: 0; }
.jd-overview-ico { color: var(--jd-primary); display: inline-flex; }
.jd-overview-label { font-size: 0.85rem; color: var(--jd-muted); }
.jd-overview-value { font-size: 0.85rem; font-weight: 600; color: var(--jd-ink); text-align: right; }
.jd-side-logo { width: 52px; height: 52px; border-radius: 14px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; border: 1px solid var(--jd-border); display: flex; align-items: center; justify-content: center; }
.jd-side-logo img { width: 100%; height: 100%; object-fit: contain; }
.jd-side-logo-ph { font-family: 'Poppins'; font-weight: 800; color: var(--jd-primary); }

/* toast */
.jd-toast {
    position: fixed; bottom: 28px; left: 50%; transform: translate(-50%, 20px);
    background: #0f172a; color: #fff; padding: 0.85rem 1.4rem; border-radius: 999px;
    font-size: 0.9rem; box-shadow: 0 12px 30px rgba(0,0,0,0.3); z-index: 9999;
    opacity: 0; transition: all 0.3s ease; pointer-events: none;
}
.jd-toast.is-show { opacity: 1; transform: translate(-50%, 0); }

/* ===== RESPONSIVE ===== */
@media (max-width: 1199.98px) {
    .jd-grid { grid-template-columns: 1fr 320px; }
    .jd-info-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 991.98px) {
    .jd-grid { grid-template-columns: 1fr; }
    .jd-side-sticky { position: static; }
    .jd-hero-inner { grid-template-columns: 1fr; text-align: center; }
    .jd-hero-visual { order: -1; }
    .jd-hero-company, .jd-hero-meta, .jd-hero-actions, .jd-hero-toprow { justify-content: center; }
    .jd-info-grid { grid-template-columns: repeat(2, 1fr); }
    .jd-benefit-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .jd-hero { border-radius: 20px; }
    .jd-info-grid, .jd-checklist, .jd-req-grid, .jd-related-grid, .jd-benefit-grid { grid-template-columns: 1fr; }
    .jd-hero-actions .jd-btn { width: 100%; }
    .jd-cc2-body { flex-direction: column; align-items: flex-start; }
}
</style>
