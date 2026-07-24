<template>
    <div class="rbs">
        <!-- HERO -->
        <section class="rbs-hero">
            <div class="rbs-hero-shapes" aria-hidden="true">
                <span class="rbs-shape rbs-shape--1"></span>
                <span class="rbs-shape rbs-shape--2"></span>
                <span class="rbs-shape rbs-shape--3"></span>
            </div>
            <div class="rbs-hero-inner">
                <div class="rbs-hero-left">
                    <nav aria-label="breadcrumb" class="rbs-crumbs">
                        <ol class="rbs-crumbs-list">
                            <li class="rbs-crumbs-item"><a :href="navRoutes.dashboard || '/job-seeker/dashboard'">Dashboard</a></li>
                            <li class="rbs-crumbs-item"><a :href="navRoutes.builder || '#'">Resume Builder</a></li>
                            <li class="rbs-crumbs-item rbs-crumbs-item--active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                    <span class="rbs-pill"><Sparkles :size="14" /> Resume Studio</span>
                    <h1 class="rbs-hero-title">Resume Studio</h1>
                    <p class="rbs-hero-sub">Build a professional resume using a beautiful guided editor.</p>

                    <div class="rbs-hero-meta">
                        <div class="rbs-meta-card">
                            <div class="rbs-meta-ring" :style="progressRingStyle">
                                <span>{{ completionPercent }}<small>%</small></span>
                            </div>
                            <div class="rbs-meta-body">
                                <div class="rbs-meta-label">Resume Progress</div>
                                <div class="rbs-meta-val">{{ filledCount }} / {{ totalSections }} sections</div>
                            </div>
                        </div>
                        <div class="rbs-meta-card">
                            <div class="rbs-meta-ico rbs-meta-ico--cyan"><Clock3 :size="20" /></div>
                            <div class="rbs-meta-body">
                                <div class="rbs-meta-label">Est. Completion</div>
                                <div class="rbs-meta-val">~ {{ estimatedMinutes }} min</div>
                            </div>
                        </div>
                        <div class="rbs-meta-badge" :class="qualityClass">
                            <ShieldCheck :size="16" /> {{ qualityLabel }}
                        </div>
                    </div>
                </div>

                <div class="rbs-hero-right">
                    <div class="rbs-hero-illo">
                        <div class="rbs-illo-doc">
                            <div class="rbs-illo-line rbs-illo-line--title"></div>
                            <div class="rbs-illo-line rbs-illo-line--w70"></div>
                            <div class="rbs-illo-line rbs-illo-line--w90"></div>
                            <div class="rbs-illo-line rbs-illo-line--w60"></div>
                            <div class="rbs-illo-chips">
                                <span class="rbs-illo-chip"></span>
                                <span class="rbs-illo-chip"></span>
                                <span class="rbs-illo-chip"></span>
                            </div>
                            <div class="rbs-illo-line rbs-illo-line--w80"></div>
                            <div class="rbs-illo-line rbs-illo-line--w50"></div>
                        </div>
                        <div class="rbs-illo-badge rbs-illo-badge--1"><Sparkles :size="16" /></div>
                        <div class="rbs-illo-badge rbs-illo-badge--2"><FileText :size="16" /></div>
                        <div class="rbs-illo-badge rbs-illo-badge--3"><Award :size="16" /></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STEP INDICATOR -->
        <div class="rbs-steps">
            <div class="rbs-steps-inner">
                <button v-for="(step, i) in steps" :key="step.key"
                        type="button"
                        class="rbs-step"
                        :class="{ 'rbs-step--active': step.key === activeStep, 'rbs-step--done': isStepDone(step.key) }"
                        @click="scrollToSection(step.key)">
                    <span class="rbs-step-dot">{{ i + 1 }}</span>
                    <span class="rbs-step-label">{{ step.label }}</span>
                </button>
            </div>
        </div>

        <!-- STUDIO -->
        <div class="rbs-studio">
            <div class="rbs-studio-inner">
                <form class="rbs-form" ref="form" :action="submitUrl" :method="method" @submit.prevent="onSubmit">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <input type="hidden" name="_method" :value="method">
                    <input type="hidden" name="personal_information" :value="json(state.personal_information)">
                    <input type="hidden" name="education" :value="json(state.education)">
                    <input type="hidden" name="experience" :value="json(state.experience)">
                    <input type="hidden" name="skills" :value="json(state.skills)">
                    <input type="hidden" name="projects" :value="json(state.projects)">
                    <input type="hidden" name="certifications" :value="json(state.certifications)">
                    <input type="hidden" name="languages" :value="json(state.languages)">
                    <input type="hidden" name="references" :value="json(state.references)">
                    <input type="hidden" name="social_links" :value="json(state.social_links)">
                    <input type="hidden" name="professional_summary" :value="state.professional_summary">
                    <input type="hidden" name="template" :value="state.template">
                    <input type="hidden" name="status" value="draft">
                    <input type="hidden" name="is_default" :value="isDefault ? '1' : '0'">
                    <input type="hidden" name="resume_builder_id" :value="builderId">

                    <!-- TITLE -->
                    <section class="rbs-card rbs-title-card">
                        <div class="rbs-field rbs-field--full">
                            <label class="rbs-label" for="title"><FileText :size="14" /> Resume Title <span class="rbs-req">*</span></label>
                            <input id="title" name="title" type="text" class="rbs-input" v-model="state.title" required maxlength="255" placeholder="e.g. Senior Frontend Developer Resume">
                            <div v-if="errors.title" class="rbs-error">{{ errors.title[0] }}</div>
                        </div>
                    </section>

                    <!-- PERSONAL INFORMATION -->
                    <section class="rbs-card" id="section-personal_information" ref="sec-personal_information">
                        <div class="rbs-card-head">
                            <div class="rbs-card-ico rbs-card-ico--blue"><User :size="18" /></div>
                            <div>
                                <h3 class="rbs-card-h">Personal Information</h3>
                                <p class="rbs-card-sub">Your identity and contact details.</p>
                            </div>
                        </div>
                        <div class="rbs-grid">
                            <div class="rbs-field"><label class="rbs-label" for="first_name">First Name</label><input id="first_name" class="rbs-input" v-model="state.personal_information.first_name" placeholder="First name"></div>
                            <div class="rbs-field"><label class="rbs-label" for="last_name">Last Name</label><input id="last_name" class="rbs-input" v-model="state.personal_information.last_name" placeholder="Last name"></div>
                            <div class="rbs-field">
                                <label class="rbs-label" for="pi_email">Email</label>
                                <input id="pi_email" type="email" class="rbs-input" :class="{ 'is-invalid': !validation.email }" v-model="state.personal_information.email" placeholder="Email">
                                <div v-if="!validation.email" class="rbs-error">Enter a valid email address.</div>
                            </div>
                            <div class="rbs-field">
                                <label class="rbs-label" for="pi_phone">Phone</label>
                                <input id="pi_phone" type="tel" class="rbs-input" :class="{ 'is-invalid': !validation.phone }" v-model="state.personal_information.phone" placeholder="Phone">
                                <div v-if="!validation.phone" class="rbs-error">Enter a valid phone number.</div>
                            </div>
                            <div class="rbs-field"><label class="rbs-label" for="pi_linkedin">LinkedIn</label><input id="pi_linkedin" type="url" class="rbs-input" v-model="state.personal_information.linkedin_url" placeholder="https://linkedin.com/..."></div>
                            <div class="rbs-field"><label class="rbs-label" for="pi_github">GitHub</label><input id="pi_github" type="url" class="rbs-input" v-model="state.personal_information.github_url" placeholder="https://github.com/..."></div>
                            <div class="rbs-field"><label class="rbs-label" for="pi_portfolio">Portfolio</label><input id="pi_portfolio" type="url" class="rbs-input" v-model="state.personal_information.portfolio_url" placeholder="Portfolio URL"></div>
                            <div class="rbs-field"><label class="rbs-label" for="pi_website">Website</label><input id="pi_website" type="url" class="rbs-input" v-model="state.personal_information.website_url" placeholder="Website URL"></div>
                        </div>
                    </section>

                    <!-- PROFESSIONAL SUMMARY -->
                    <section class="rbs-card" id="section-professional_summary" ref="sec-professional_summary">
                        <div class="rbs-card-head">
                            <div class="rbs-card-ico rbs-card-ico--violet"><NotebookPen :size="18" /></div>
                            <div>
                                <h3 class="rbs-card-h">Professional Summary</h3>
                                <p class="rbs-card-sub">A concise pitch about who you are.</p>
                            </div>
                        </div>
                        <div class="rbs-field rbs-field--full">
                            <textarea id="professional_summary" rows="4" maxlength="2000" class="rbs-input rbs-textarea" v-model="state.professional_summary" placeholder="Write your summary..."></textarea>
                        </div>
                    </section>

                    <!-- EDUCATION -->
                    <section class="rbs-card" id="section-education" ref="sec-education">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--amber"><GraduationCap :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Education</h3>
                                    <p class="rbs-card-sub">Your academic background.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addEducation"><Plus :size="15" /> Add Education</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.education" :key="'edu-'+i" class="rbs-item">
                                <div class="rbs-item-head">
                                    <span class="rbs-item-title">Education {{ i + 1 }}</span>
                                    <div class="rbs-item-actions">
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.education, i, -1)" aria-label="Move up"><ArrowUp :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.education, i, 1)" aria-label="Move down"><ArrowDown :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.education, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                                    </div>
                                </div>
                                <div class="rbs-grid">
                                    <div class="rbs-field"><input class="rbs-input" placeholder="School" :value="item.school" @input="updateAt(item,'school',$event.target.value)"></div>
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Degree" :value="item.degree" @input="updateAt(item,'degree',$event.target.value)"></div>
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Field of Study" :value="item.field_of_study" @input="updateAt(item,'field_of_study',$event.target.value)"></div>
                                    <div class="rbs-field"><input type="month" class="rbs-input" placeholder="Start" :value="item.start_date" @input="updateAt(item,'start_date',$event.target.value)"></div>
                                    <div class="rbs-field"><input type="month" class="rbs-input" placeholder="End" :value="item.end_date" @input="updateAt(item,'end_date',$event.target.value)"></div>
                                    <div class="rbs-field rbs-field--full"><textarea rows="2" class="rbs-input rbs-textarea" placeholder="Notes" :value="item.notes" @input="updateAt(item,'notes',$event.target.value)"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!state.education.length" class="rbs-empty"><GraduationCap :size="22" /><span>No education added yet.</span></div>
                    </section>

                    <!-- EXPERIENCE -->
                    <section class="rbs-card" id="section-experience" ref="sec-experience">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--blue"><BriefcaseBusiness :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Experience</h3>
                                    <p class="rbs-card-sub">Your professional journey.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addExperience"><Plus :size="15" /> Add Experience</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.experience" :key="'exp-'+i" class="rbs-item">
                                <div class="rbs-item-head">
                                    <span class="rbs-item-title">Experience {{ i + 1 }}</span>
                                    <div class="rbs-item-actions">
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.experience, i, -1)" aria-label="Move up"><ArrowUp :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.experience, i, 1)" aria-label="Move down"><ArrowDown :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.experience, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                                    </div>
                                </div>
                                <div class="rbs-grid">
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Company" :value="item.company" @input="updateAt(item,'company',$event.target.value)"></div>
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Title" :value="item.title" @input="updateAt(item,'title',$event.target.value)"></div>
                                    <div class="rbs-field"><input type="month" class="rbs-input" placeholder="Start" :value="item.start_date" @input="updateAt(item,'start_date',$event.target.value)"></div>
                                    <div class="rbs-field"><input type="month" class="rbs-input" placeholder="End" :value="item.end_date" @input="updateAt(item,'end_date',$event.target.value)"></div>
                                    <div class="rbs-field rbs-field--full"><textarea rows="2" class="rbs-input rbs-textarea" placeholder="Description" :value="item.description" @input="updateAt(item,'description',$event.target.value)"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!state.experience.length" class="rbs-empty"><BriefcaseBusiness :size="22" /><span>No experience added yet.</span></div>
                    </section>

                    <!-- SKILLS -->
                    <section class="rbs-card" id="section-skills" ref="sec-skills">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--violet"><Sparkles :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Skills</h3>
                                    <p class="rbs-card-sub">Your strongest capabilities.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addSkill"><Plus :size="15" /> Add Skill</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.skills" :key="'skl-'+i" class="rbs-item rbs-item--row">
                                <div class="rbs-item-actions">
                                    <button type="button" class="rbs-icon-btn" @click="moveItem(state.skills, i, -1)" aria-label="Move up"><ArrowUp :size="15" /></button>
                                    <button type="button" class="rbs-icon-btn" @click="moveItem(state.skills, i, 1)" aria-label="Move down"><ArrowDown :size="15" /></button>
                                </div>
                                <input class="rbs-input" placeholder="Skill" :value="item" @input="state.skills.splice(i, 1, $event.target.value)">
                                <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.skills, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                            </div>
                        </div>
                        <div v-if="!state.skills.length" class="rbs-empty"><Sparkles :size="22" /><span>No skills added yet.</span></div>
                    </section>

                    <!-- PROJECTS -->
                    <section class="rbs-card" id="section-projects" ref="sec-projects">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--emerald"><LayoutTemplate :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Projects</h3>
                                    <p class="rbs-card-sub">Showcase your best work.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addProject"><Plus :size="15" /> Add Project</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.projects" :key="'prj-'+i" class="rbs-item">
                                <div class="rbs-item-head">
                                    <span class="rbs-item-title">Project {{ i + 1 }}</span>
                                    <div class="rbs-item-actions">
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.projects, i, -1)" aria-label="Move up"><ArrowUp :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.projects, i, 1)" aria-label="Move down"><ArrowDown :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.projects, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                                    </div>
                                </div>
                                <div class="rbs-grid">
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Project Name" :value="item.name" @input="updateAt(item,'name',$event.target.value)"></div>
                                    <div class="rbs-field"><input type="url" class="rbs-input" placeholder="Project Link" :value="item.link" @input="updateAt(item,'link',$event.target.value)"></div>
                                    <div class="rbs-field rbs-field--full"><textarea rows="2" class="rbs-input rbs-textarea" placeholder="Description" :value="item.description" @input="updateAt(item,'description',$event.target.value)"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!state.projects.length" class="rbs-empty"><LayoutTemplate :size="22" /><span>No projects added yet.</span></div>
                    </section>

                    <!-- CERTIFICATIONS -->
                    <section class="rbs-card" id="section-certifications" ref="sec-certifications">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--amber"><Award :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Certifications</h3>
                                    <p class="rbs-card-sub">Licenses and certificates.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addCertification"><Plus :size="15" /> Add Certification</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.certifications" :key="'cert-'+i" class="rbs-item rbs-item--row">
                                <input class="rbs-input" placeholder="Certification" :value="item.name" @input="updateAt(item,'name',$event.target.value)">
                                <input class="rbs-input" placeholder="Issuer" :value="item.issuer" @input="updateAt(item,'issuer',$event.target.value)">
                                <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.certifications, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                            </div>
                        </div>
                        <div v-if="!state.certifications.length" class="rbs-empty"><Award :size="22" /><span>No certifications added yet.</span></div>
                    </section>

                    <!-- LANGUAGES -->
                    <section class="rbs-card" id="section-languages" ref="sec-languages">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--emerald"><Languages :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">Languages</h3>
                                    <p class="rbs-card-sub">Spoken languages.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addLanguage"><Plus :size="15" /> Add Language</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.languages" :key="'lang-'+i" class="rbs-item rbs-item--row">
                                <input class="rbs-input" placeholder="Language" :value="item.name" @input="updateAt(item,'name',$event.target.value)">
                                <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.languages, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                            </div>
                        </div>
                        <div v-if="!state.languages.length" class="rbs-empty"><Languages :size="22" /><span>No languages added yet.</span></div>
                    </section>

                    <!-- REFERENCES -->
                    <section class="rbs-card" id="section-references" ref="sec-references">
                        <div class="rbs-card-head rbs-card-head--row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rbs-card-ico rbs-card-ico--blue"><User :size="18" /></div>
                                <div>
                                    <h3 class="rbs-card-h">References</h3>
                                    <p class="rbs-card-sub">People who vouch for you.</p>
                                </div>
                            </div>
                            <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="addReference"><Plus :size="15" /> Add Reference</button>
                        </div>
                        <div class="rbs-items">
                            <div v-for="(item, i) in state.references" :key="'ref-'+i" class="rbs-item">
                                <div class="rbs-item-head">
                                    <span class="rbs-item-title">Reference {{ i + 1 }}</span>
                                    <div class="rbs-item-actions">
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.references, i, -1)" aria-label="Move up"><ArrowUp :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn" @click="moveItem(state.references, i, 1)" aria-label="Move down"><ArrowDown :size="15" /></button>
                                        <button type="button" class="rbs-icon-btn rbs-icon-btn--danger" @click="removeItem(state.references, i)" aria-label="Remove"><Trash2 :size="15" /></button>
                                    </div>
                                </div>
                                <div class="rbs-grid">
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Name" :value="item.name" @input="updateAt(item,'name',$event.target.value)"></div>
                                    <div class="rbs-field"><input class="rbs-input" placeholder="Position" :value="item.position" @input="updateAt(item,'position',$event.target.value)"></div>
                                    <div class="rbs-field rbs-field--full"><input class="rbs-input" placeholder="Contact" :value="item.contact" @input="updateAt(item,'contact',$event.target.value)"></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!state.references.length" class="rbs-empty"><User :size="22" /><span>No references added yet.</span></div>
                    </section>

                    <!-- SOCIAL LINKS -->
                    <section class="rbs-card" id="section-social_links" ref="sec-social_links">
                        <div class="rbs-card-head">
                            <div class="rbs-card-ico rbs-card-ico--cyan"><User :size="18" /></div>
                            <div>
                                <h3 class="rbs-card-h">Social Links</h3>
                                <p class="rbs-card-sub">Where employers can find you online.</p>
                            </div>
                        </div>
                        <div class="rbs-grid">
                            <div class="rbs-field"><label class="rbs-label" for="sl_linkedin">LinkedIn</label><input id="sl_linkedin" type="url" class="rbs-input" v-model="state.social_links.linkedin" placeholder="LinkedIn URL"></div>
                            <div class="rbs-field"><label class="rbs-label" for="sl_github">GitHub</label><input id="sl_github" type="url" class="rbs-input" v-model="state.social_links.github" placeholder="GitHub URL"></div>
                            <div class="rbs-field"><label class="rbs-label" for="sl_portfolio">Portfolio</label><input id="sl_portfolio" type="url" class="rbs-input" v-model="state.social_links.portfolio" placeholder="Portfolio URL"></div>
                            <div class="rbs-field"><label class="rbs-label" for="sl_website">Website</label><input id="sl_website" type="url" class="rbs-input" v-model="state.social_links.website" placeholder="Website URL"></div>
                        </div>
                    </section>

                    <div class="rbs-form-foot">
                        <label class="rbs-check">
                            <input type="checkbox" v-model="isDefault">
                            <span class="rbs-check-box"><CircleCheck :size="14" /></span>
                            <span>Set as default resume</span>
                        </label>
                        <div class="rbs-autosave" :class="{'rbs-autosave--saving': saving}">
                            <CircleCheck :size="14" v-if="!saving" /> {{ saving ? 'Saving…' : lastSavedText }}
                        </div>
                    </div>
                </form>

                <!-- RIGHT SIDEBAR -->
                <aside class="rbs-side">
                    <div class="rbs-side-sticky">
                        <!-- LIVE SUMMARY -->
                        <section class="rbs-card rbs-summary">
                            <div class="rbs-summary-head">
                                <Eye :size="16" />
                                <h4 class="rbs-side-h">Live Resume Summary</h4>
                            </div>
                            <div class="rbs-summary-name">{{ fullName }}</div>
                            <div class="rbs-summary-title">{{ state.personal_information.professional_title || 'Professional Title' }}</div>
                            <div class="rbs-summary-contact">{{ contactLine }}</div>
                            <div class="rbs-summary-rows">
                                <div class="rbs-summary-row"><span>Resume Name</span><strong>{{ state.title || 'Untitled Resume' }}</strong></div>
                                <div class="rbs-summary-row"><span>Completion</span><strong>{{ completionPercent }}%</strong></div>
                                <div class="rbs-summary-row"><span>Template</span><strong class="rbs-cap">{{ state.template }}</strong></div>
                                <div class="rbs-summary-row"><span>Last Saved</span><strong>{{ lastSavedText }}</strong></div>
                                <div class="rbs-summary-row"><span>Status</span><strong class="rbs-status">{{ builderId ? 'Saved Draft' : 'Draft' }}</strong></div>
                            </div>
                        </section>

                        <!-- PROGRESS -->
                        <section class="rbs-card rbs-progress">
                            <div class="rbs-progress-ring" :style="progressRingStyle">
                                <span>{{ completionPercent }}<small>%</small></span>
                            </div>
                            <h4 class="rbs-side-h">Resume Progress</h4>
                            <div class="rbs-progress-bar"><span :style="{ width: completionPercent + '%' }"></span></div>
                            <p class="rbs-progress-note">{{ filledCount }} of {{ totalSections }} sections complete. Keep going!</p>
                        </section>

                        <!-- LIVE PREVIEW + TEMPLATE -->
                        <section class="rbs-card rbs-preview-card">
                            <div class="rbs-card-head rbs-card-head--row">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rbs-card-ico rbs-card-ico--violet"><LayoutTemplate :size="18" /></div>
                                    <div>
                                        <h4 class="rbs-side-h">Live Preview</h4>
                                        <p class="rbs-card-sub">Tap a template to restyle instantly.</p>
                                    </div>
                                </div>
                                <span class="rbs-preview-tag rbs-cap">{{ state.template }}</span>
                            </div>

                            <div class="rbs-preview-frame">
                                <div class="rbs-preview-page" :class="'rbs-tpl-' + state.template">
                                    <div class="rbs-pv-head">
                                        <h3 class="rbs-pv-name">{{ fullName }}</h3>
                                        <div class="rbs-pv-title">{{ state.personal_information.professional_title || 'Professional Title' }}</div>
                                        <div class="rbs-pv-contact">{{ contactLine || 'email@domain.com · +00 000 000' }}</div>
                                    </div>

                                    <div v-if="state.professional_summary" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Summary</div>
                                        <p class="rbs-pv-text">{{ state.professional_summary }}</p>
                                    </div>

                                    <div v-if="state.experience.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Experience</div>
                                        <div v-for="(x, i) in state.experience" :key="'pvx'+i" class="rbs-pv-item">
                                            <div class="rbs-pv-item-t">{{ x.title || x.company || 'Role' }}</div>
                                            <div class="rbs-pv-item-s">{{ [x.company, x.start_date, x.end_date].filter(Boolean).join(' · ') }}</div>
                                            <div v-if="x.description" class="rbs-pv-text">{{ x.description }}</div>
                                        </div>
                                    </div>

                                    <div v-if="state.education.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Education</div>
                                        <div v-for="(e, i) in state.education" :key="'pve'+i" class="rbs-pv-item">
                                            <div class="rbs-pv-item-t">{{ e.degree || e.school || 'Degree' }}</div>
                                            <div class="rbs-pv-item-s">{{ [e.school, e.start_date, e.end_date].filter(Boolean).join(' · ') }}</div>
                                        </div>
                                    </div>

                                    <div v-if="state.skills.filter(Boolean).length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Skills</div>
                                        <div class="rbs-pv-chips">
                                            <span v-for="(s, i) in state.skills.filter(Boolean)" :key="'pvs'+i" class="rbs-pv-chip">{{ s }}</span>
                                        </div>
                                    </div>

                                    <div v-if="state.projects.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Projects</div>
                                        <div v-for="(p, i) in state.projects" :key="'pvp'+i" class="rbs-pv-item">
                                            <div class="rbs-pv-item-t">{{ p.name || 'Project' }}</div>
                                            <div v-if="p.description" class="rbs-pv-text">{{ p.description }}</div>
                                        </div>
                                    </div>

                                    <div v-if="state.certifications.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Certifications</div>
                                        <div v-for="(c, i) in state.certifications" :key="'pvc'+i" class="rbs-pv-item">
                                            <div class="rbs-pv-item-t">{{ c.name || 'Certification' }}</div>
                                            <div class="rbs-pv-item-s">{{ c.issuer || '' }}</div>
                                        </div>
                                    </div>

                                    <div v-if="state.languages.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">Languages</div>
                                        <div class="rbs-pv-chips">
                                            <span v-for="(l, i) in state.languages.filter(x => x.name)" :key="'pvl'+i" class="rbs-pv-chip">{{ l.name }}</span>
                                        </div>
                                    </div>

                                    <div v-if="state.references.length" class="rbs-pv-block">
                                        <div class="rbs-pv-h">References</div>
                                        <div v-for="(r, i) in state.references" :key="'pvr'+i" class="rbs-pv-item">
                                            <div class="rbs-pv-item-t">{{ r.name || 'Reference' }}</div>
                                            <div class="rbs-pv-item-s">{{ [r.position, r.contact].filter(Boolean).join(' · ') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rbs-templates">
                                <button v-for="t in templates" :key="t.value" type="button"
                                        class="rbs-template" :class="{ 'rbs-template--active': state.template === t.value }"
                                        @click="selectTemplate(t.value)">
                                    <span class="rbs-template-swatch" :class="'rbs-template-swatch--' + t.value"></span>
                                    <span class="rbs-template-name">{{ t.label }}</span>
                                </button>
                            </div>
                            <div class="rbs-export-links">
                                <button type="button" class="rbs-btn rbs-btn-outline rbs-btn-sm" @click="openExport('preview')"><Eye :size="15" /> Full Preview</button>
                                <button type="button" class="rbs-btn rbs-btn-primary rbs-btn-sm" @click="openExport('download')"><Download :size="15" /> PDF</button>
                                <button type="button" class="rbs-btn rbs-btn-soft rbs-btn-sm" @click="openExport('print')"><Printer :size="15" /> Print</button>
                            </div>
                        </section>

                        <!-- TIPS -->
                        <section class="rbs-card">
                            <div class="rbs-card-head">
                                <div class="rbs-card-ico rbs-card-ico--emerald"><Lightbulb :size="18" /></div>
                                <div>
                                    <h4 class="rbs-side-h">Quick Tips</h4>
                                </div>
                            </div>
                            <ul class="rbs-tips">
                                <li v-for="(tip, i) in tips" :key="i"><CircleCheck :size="14" /> {{ tip }}</li>
                            </ul>
                        </section>
                    </div>
                </aside>
            </div>
        </div>

        <!-- SAVE BAR -->
        <div class="rbs-savebar">
            <div class="rbs-savebar-inner">
                <div class="rbs-savebar-info">
                    <span v-if="saveError" class="rbs-savebar-status rbs-savebar-status--err"><AlertCircle :size="15" /> {{ saveError }}</span>
                    <span v-else class="rbs-savebar-status"><CircleCheck :size="15" /> {{ saving ? 'Saving draft…' : 'Draft autosaves while you work' }}</span>
                </div>
                <div class="rbs-savebar-actions">
                    <a :href="navRoutes.builder || '#'" class="rbs-btn rbs-btn-ghost">Cancel</a>
                    <button type="button" class="rbs-btn rbs-btn-soft" @click="saveDraft(true)"><Save :size="15" /> Save Draft</button>
                    <button type="button" class="rbs-btn rbs-btn-primary" @click="saveAndExit"><Save :size="16" /> Save Resume Builder</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Sparkles, FileText, Award, User, ShieldCheck, Clock3, NotebookPen, GraduationCap,
    BriefcaseBusiness, Plus, ArrowUp, ArrowDown, Trash2, LayoutTemplate, Languages, CircleCheck,
    Eye, Download, Printer, Save, Lightbulb, AlertCircle,
} from '@lucide/vue';

export default {
    name: 'ResumeBuilderStudio',
    components: {
        Sparkles, FileText, Award, User, ShieldCheck, Clock3, NotebookPen, GraduationCap,
        BriefcaseBusiness, Plus, ArrowUp, ArrowDown, Trash2, LayoutTemplate, Languages, CircleCheck,
        Eye, Download, Printer, Save, Lightbulb, AlertCircle,
    },
    props: {
        csrfToken: { type: String, default: '' },
        isEdit: { type: Boolean, default: false },
        builderId: { type: [Number, String], default: null },
        storeUrl: { type: String, default: '' },
        updateUrl: { type: String, default: '' },
        indexUrl: { type: String, default: '#' },
        routes: { type: Object, default: () => ({}) },
        profile: { type: Object, default: () => ({}) },
        initial: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    data() {
        const p = this.profile || {};
        const init = this.initial || {};
        const pi = Object.assign({
            first_name: '', last_name: '', email: '', phone: '',
            linkedin_url: '', github_url: '', portfolio_url: '', website_url: '', professional_title: '',
        }, p.personal_information || {}, init.personal_information || {});
        pi.email = pi.email || (this.routes.userEmail || '');

        return {
            state: {
                title: init.title || '',
                professional_summary: init.professional_summary || '',
                personal_information: pi,
                education: init.education || [],
                experience: init.experience || [],
                skills: init.skills || [],
                projects: init.projects || [],
                certifications: init.certifications || [],
                languages: init.languages || [],
                references: init.references || [],
                social_links: Object.assign({ linkedin: '', github: '', portfolio: '', website: '' }, p.social_links || {}, init.social_links || {}),
                template: init.template || 'modern',
            },
            isDefault: !!init.is_default,
            saving: false,
            saveError: '',
            lastSaved: null,
            method: this.isEdit ? 'PATCH' : 'POST',
            timed: { interval: null, dirty: false },
            activeStep: 'personal_information',
            tips: [
                'Complete every section for a stronger resume.',
                'Use professional, clear language.',
                'Keep your resume updated regularly.',
                'Add measurable achievements (e.g. +20% sales).',
            ],
            templates: [
                { value: 'modern', label: 'Modern' },
                { value: 'professional', label: 'Professional' },
                { value: 'minimal', label: 'Minimal' },
                { value: 'creative', label: 'Creative' },
            ],
            steps: [
                { key: 'personal_information', label: 'Personal Info' },
                { key: 'education', label: 'Education' },
                { key: 'experience', label: 'Experience' },
                { key: 'skills', label: 'Skills' },
                { key: 'projects', label: 'Projects' },
                { key: 'references', label: 'Review' },
            ],
            sectionKeys: ['personal_information', 'professional_summary', 'education', 'experience', 'skills', 'projects', 'certifications', 'languages', 'references', 'social_links'],
        };
    },
    computed: {
        navRoutes() {
            return Object.assign({
                dashboard: '/job-seeker/dashboard',
                builder: this.indexUrl || '#',
            }, this.routes || {});
        },
        validation() {
            const email = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(this.state.personal_information.email || '');
            const phone = !this.state.personal_information.phone || /^[0-9+\-()\s]{6,25}$/.test(this.state.personal_information.phone);
            return { email, phone };
        },
        socialValid() {
            const keys = ['linkedin', 'github', 'portfolio', 'website'];
            return keys.every((k) => {
                const v = (this.state.social_links[k] || '').trim();
                if (!v) return true;
                return /^(https?:\/\/|www\.)/i.test(v);
            });
        },
        fullName() {
            const n = `${this.state.personal_information.first_name || ''} ${this.state.personal_information.last_name || ''}`.trim();
            return n || 'Your Name';
        },
        contactLine() {
            return [
                this.state.personal_information.email,
                this.state.personal_information.phone,
                this.state.social_links.linkedin,
                this.state.social_links.github,
            ].filter(Boolean).join('  •  ');
        },
        filledCount() {
            let count = 0;
            if (this.state.title) count++;
            if (this.state.professional_summary) count++;
            if (this.state.personal_information.first_name || this.state.personal_information.last_name || this.state.personal_information.email) count++;
            if (this.state.education.length) count++;
            if (this.state.experience.length) count++;
            if (this.state.skills.filter(Boolean).length) count++;
            if (this.state.projects.length) count++;
            if (this.state.certifications.length) count++;
            if (this.state.languages.length) count++;
            if (this.state.references.length) count++;
            if (this.state.social_links.linkedin || this.state.social_links.github || this.state.social_links.portfolio || this.state.social_links.website) count++;
            return count;
        },
        totalSections() { return this.sectionKeys.length + 1; },
        completionPercent() {
            return Math.min(100, Math.round((this.filledCount / this.totalSections) * 100));
        },
        estimatedMinutes() {
            return Math.max(2, 18 - Math.round(this.completionPercent / 8));
        },
        qualityLabel() {
            if (this.completionPercent >= 85) return 'Excellent';
            if (this.completionPercent >= 55) return 'Good';
            if (this.completionPercent >= 25) return 'Getting There';
            return 'Just Started';
        },
        qualityClass() {
            if (this.completionPercent >= 85) return 'rbs-meta-badge--excellent';
            if (this.completionPercent >= 55) return 'rbs-meta-badge--good';
            return 'rbs-meta-badge--low';
        },
        progressRingStyle() {
            const deg = this.completionPercent * 3.6;
            return { background: `conic-gradient(#2563eb ${deg}deg, rgba(37,99,235,0.12) 0deg)` };
        },
        lastSavedText() {
            if (this.saving) return 'Saving…';
            if (!this.lastSaved) return this.builderId ? 'Saved' : 'Not saved yet';
            return 'Saved ' + this.lastSaved.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },
        previewPageUrl() {
            if (!this.builderId) return '#';
            const base = `/job-seeker/resume-builders/${this.builderId}`;
            return `${base}/preview?template=${encodeURIComponent(this.state.template)}`;
        },
        submitUrl() {
            return this.builderId ? this.updateUrl : this.storeUrl;
        },
    },
    mounted() {
        this.setupObserver();
        this.timed.interval = setInterval(() => {
            if (this.timed.dirty) this.saveDraft(false);
        }, 30000);
        window.addEventListener('beforeunload', this.onUnload);
    },
    beforeUnmount() {
        clearInterval(this.timed.interval);
        window.removeEventListener('beforeunload', this.onUnload);
    },
    methods: {
        json(v) { return JSON.stringify(v ?? {}); },
        normalizeSocialLinks() {
            const out = {};
            ['linkedin', 'github', 'portfolio', 'website'].forEach((k) => {
                let v = (this.state.social_links[k] || '').trim();
                if (v && !/^(https?:\/\/|www\.)/i.test(v)) {
                    v = 'https://' + v;
                }
                out[k] = v;
            });
            return out;
        },
        currentCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            const fromMeta = meta ? meta.getAttribute('content') : '';
            return fromMeta || this.csrfToken || '';
        },
        updateAt(obj, key, value) { obj[key] = value; this.timed.dirty = true; },
        addEducation() { this.state.education.push({ school: '', degree: '', field_of_study: '', start_date: '', end_date: '', notes: '' }); this.timed.dirty = true; },
        addExperience() { this.state.experience.push({ company: '', title: '', start_date: '', end_date: '', description: '' }); this.timed.dirty = true; },
        addSkill() { this.state.skills.push(''); this.timed.dirty = true; },
        addProject() { this.state.projects.push({ name: '', link: '', description: '' }); this.timed.dirty = true; },
        addCertification() { this.state.certifications.push({ name: '', issuer: '', date: '' }); this.timed.dirty = true; },
        addLanguage() { this.state.languages.push({ name: '' }); this.timed.dirty = true; },
        addReference() { this.state.references.push({ name: '', position: '', contact: '' }); this.timed.dirty = true; },
        removeItem(collection, index) { collection.splice(index, 1); this.timed.dirty = true; },
        moveItem(collection, index, delta) {
            const target = index + delta;
            if (target < 0 || target >= collection.length) return;
            const tmp = collection[index];
            collection[index] = collection[target];
            collection[target] = tmp;
            this.timed.dirty = true;
        },
        isStepDone(key) {
            if (key === 'personal_information') return !!(this.state.personal_information.first_name || this.state.personal_information.email);
            if (key === 'education') return this.state.education.length > 0;
            if (key === 'experience') return this.state.experience.length > 0;
            if (key === 'skills') return this.state.skills.filter(Boolean).length > 0;
            if (key === 'projects') return this.state.projects.length > 0;
            if (key === 'references') return this.state.references.length > 0;
            return false;
        },
        scrollToSection(key) {
            const el = this.$refs['sec-' + key];
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
        setupObserver() {
            if (!('IntersectionObserver' in window)) return;
            const obs = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        const key = e.target.getAttribute('id')?.replace('section-', '');
                        if (key) this.activeStep = key;
                    }
                });
            }, { rootMargin: '-40% 0px -55% 0px' });
            this.sectionKeys.forEach((k) => {
                const el = this.$refs['sec-' + k];
                if (el) obs.observe(el);
            });
        },
        onUnload(e) {
            if (this.timed.dirty) { e.preventDefault(); e.returnValue = ''; }
        },
        async saveDraft(showFeedback) {
            if (this.saving) return;
            if (!this.socialValid) {
                const msg = 'Social links must be valid URLs (include https://).';
                this.saveError = msg;
                if (showFeedback) alert(msg);
                return false;
            }
            this.saving = true;
            this.saveError = '';
            const url = this.builderId
                ? `/job-seeker/resume-builders/${this.builderId}`
                : this.storeUrl;
            const method = this.builderId ? 'PATCH' : 'POST';
            const socialLinks = this.normalizeSocialLinks();
            try {
                const res = await fetch(url, {
                    method,
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.currentCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        title: this.state.title,
                        professional_summary: this.state.professional_summary,
                        template: this.state.template,
                        status: 'draft',
                        is_default: this.isDefault ? 1 : 0,
                        personal_information: this.state.personal_information,
                        education: this.state.education,
                        experience: this.state.experience,
                        skills: this.state.skills,
                        projects: this.state.projects,
                        certifications: this.state.certifications,
                        languages: this.state.languages,
                        references: this.state.references,
                        social_links: socialLinks,
                        resume_builder_id: this.builderId,
                    }),
                });
                if (!res.ok) {
                    let message = 'Could not save. Please check required fields.';
                    try {
                        const data = await res.json();
                        if (data && data.errors) {
                            const first = Object.values(data.errors)[0];
                            if (Array.isArray(first) && first.length) message = first[0];
                        }
                    } catch (e) { /* ignore */ }
                    this.saveError = message;
                    if (showFeedback) alert(message);
                    return false;
                }
                const payload = await res.json();
                if (payload && payload.id) {
                    this.builderId = payload.id;
                    this.$emit('update:builderId', payload.id);
                }
                this.lastSaved = new Date();
                this.timed.dirty = false;
                return true;
                } catch (err) {
                    const msg = (err && err.message) ? err.message : 'Network error while saving.';
                    this.saveError = msg + ' (Please refresh the page and try again.)';
                    if (showFeedback) {
                        alert('Save failed: ' + msg + ' Please refresh and try again.');
                    }
                    if (showFeedback) console.error('Autosave failed', err);
                    return false;
                } finally {
                this.saving = false;
            }
        },
        async saveAndExit() {
            const ok = await this.saveDraft(true);
            if (!ok) return;
            window.location.href = this.indexUrl || '/job-seeker/resume-builders';
        },
        onSubmit() {
            if (!this.validation.email || !this.validation.phone) {
                alert('Please enter a valid email and phone number.');
                return;
            }
            this.timed.dirty = false;
        },
        async openExport(type) {
            if (!this.builderId || this.timed.dirty) {
                const ok = await this.saveDraft(false);
                if (!ok || !this.builderId) {
                    if (!this.saveError) alert('Save your resume first (a title is required) before exporting.');
                    return;
                }
            }
            const base = `/job-seeker/resume-builders/${this.builderId}`;
            const q = `?template=${encodeURIComponent(this.state.template)}`;
            const url = type === 'download'
                ? `${base}/download${q}`
                : type === 'print'
                    ? `${base}/print${q}`
                    : `${base}/preview${q}`;
            window.open(url, '_blank', 'noopener');
        },
        selectTemplate(value) {
            this.state.template = value;
            this.timed.dirty = true;
        },
    },
};
</script>

<style scoped>
.rbs {
    --rbs-primary: #2563EB;
    --rbs-secondary: #7C3AED;
    --rbs-accent: #06B6D4;
    --rbs-ink: #0f172a;
    --rbs-muted: #64748b;
    --rbs-border: #E5E7EB;
    --rbs-bg: #F8FAFC;
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem) 7rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--rbs-ink);
    animation: rbs-fade 0.5s ease both;
}
@keyframes rbs-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* HERO */
.rbs-hero {
    position: relative;
    border-radius: 28px;
    overflow: hidden;
    padding: clamp(1.75rem, 4vw, 3rem);
    margin: 1.5rem 0 1.25rem;
    background: radial-gradient(120% 130% at 0% 0%, #1e3a8a 0%, #1d4ed8 38%, #2563eb 70%, #3b82f6 100%);
    box-shadow: 0 30px 60px -30px rgba(37,99,235,0.6);
    isolation: isolate;
}
.rbs-hero-shapes { position: absolute; inset: 0; z-index: -1; overflow: hidden; }
.rbs-shape { position: absolute; border-radius: 50%; filter: blur(30px); opacity: 0.5; }
.rbs-shape--1 { width: 320px; height: 320px; background: radial-gradient(circle, rgba(124,58,237,0.6), transparent 70%); top: -100px; right: -60px; }
.rbs-shape--2 { width: 260px; height: 260px; background: radial-gradient(circle, rgba(6,182,212,0.5), transparent 70%); bottom: -120px; left: 20%; }
.rbs-shape--3 { width: 200px; height: 200px; background: radial-gradient(circle, rgba(96,165,250,0.5), transparent 70%); top: 30%; right: 25%; }
.rbs-hero-inner { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
.rbs-hero-left { flex: 1 1 480px; min-width: 0; }
.rbs-crumbs-list { display: flex; align-items: center; gap: 0.5rem; list-style: none; margin: 0 0 1rem; padding: 0; font-size: 0.82rem; color: rgba(255,255,255,0.8); }
.rbs-crumbs-item a { color: rgba(255,255,255,0.85); text-decoration: none; transition: color 0.2s; }
.rbs-crumbs-item a:hover { color: #fff; }
.rbs-crumbs-item--active { color: rgba(255,255,255,0.55); }
.rbs-crumbs-item + .rbs-crumbs-item::before { content: '/'; margin-right: 0.5rem; color: rgba(255,255,255,0.4); }
.rbs-pill { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.9rem; border-radius: 999px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #fff; font-size: 0.76rem; font-weight: 600; letter-spacing: 0.03em; margin-bottom: 0.85rem; }
.rbs-hero-title { font-family: 'Poppins', sans-serif; font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 800; color: #fff; margin: 0 0 0.4rem; letter-spacing: -0.02em; }
.rbs-hero-sub { color: rgba(255,255,255,0.9); margin: 0 0 1.5rem; font-size: 1rem; max-width: 480px; }
.rbs-hero-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 0.85rem; }
.rbs-meta-card { display: flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,0.12); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; padding: 0.7rem 1rem; }
.rbs-meta-ring { width: 46px; height: 46px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rbs-meta-ring span { width: 36px; height: 36px; border-radius: 50%; background: #1d4ed8; color: #fff; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; }
.rbs-meta-ico { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rbs-meta-ico--cyan { background: rgba(6,182,212,0.25); color: #a5f3fc; }
.rbs-meta-body { display: flex; flex-direction: column; }
.rbs-meta-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.7); font-weight: 600; }
.rbs-meta-val { font-size: 0.86rem; font-weight: 700; color: #fff; }
.rbs-meta-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.95rem; border-radius: 999px; font-size: 0.78rem; font-weight: 700; background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.25); color: #fff; }
.rbs-meta-badge--excellent { background: rgba(16,185,129,0.3); border-color: rgba(16,185,129,0.5); }
.rbs-meta-badge--good { background: rgba(6,182,212,0.3); border-color: rgba(6,182,212,0.5); }
.rbs-meta-badge--low { background: rgba(251,191,36,0.25); border-color: rgba(251,191,36,0.45); color: #fef3c7; }

.rbs-hero-right { flex: 0 0 auto; }
.rbs-hero-illo { position: relative; width: 280px; height: 320px; display: flex; align-items: center; justify-content: center; }
.rbs-illo-doc { width: 230px; height: 290px; background: rgba(255,255,255,0.95); border-radius: 18px; box-shadow: 0 30px 60px -20px rgba(15,23,42,0.5); padding: 1.4rem; display: flex; flex-direction: column; gap: 0.8rem; transform: rotate(-4deg); }
.rbs-illo-line { height: 10px; border-radius: 6px; background: #e2e8f0; }
.rbs-illo-line--title { height: 16px; width: 60%; background: linear-gradient(135deg, #2563eb, #7c3aed); }
.rbs-illo-line--w90 { width: 90%; } .rbs-illo-line--w80 { width: 80%; } .rbs-illo-line--w70 { width: 70%; } .rbs-illo-line--w60 { width: 60%; } .rbs-illo-line--w50 { width: 50%; }
.rbs-illo-chips { display: flex; gap: 0.5rem; }
.rbs-illo-chip { width: 56px; height: 22px; border-radius: 999px; background: rgba(37,99,235,0.12); }
.rbs-illo-badge { position: absolute; width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 12px 24px -10px rgba(15,23,42,0.5); }
.rbs-illo-badge--1 { top: 6px; right: 0; background: linear-gradient(135deg, #2563eb, #6366f1); }
.rbs-illo-badge--2 { bottom: 30px; left: -10px; background: linear-gradient(135deg, #06b6d4, #3b82f6); }
.rbs-illo-badge--3 { bottom: -6px; right: 24px; background: linear-gradient(135deg, #7c3aed, #a855f7); }

/* STEP INDICATOR */
.rbs-steps { margin-bottom: 1.5rem; }
.rbs-steps-inner { display: flex; flex-wrap: wrap; gap: 0.5rem; background: #fff; border: 1px solid var(--rbs-border); border-radius: 18px; padding: 0.7rem; box-shadow: 0 8px 24px -18px rgba(15,23,42,0.2); }
.rbs-step { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.85rem; border-radius: 999px; border: 1px solid transparent; background: #f8fafc; color: var(--rbs-muted); cursor: pointer; font-size: 0.82rem; font-weight: 600; transition: all 0.25s; }
.rbs-step:hover { color: var(--rbs-primary); background: #eff6ff; }
.rbs-step-dot { width: 22px; height: 22px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; }
.rbs-step--active { background: rgba(37,99,235,0.1); color: #1d4ed8; border-color: rgba(37,99,235,0.25); }
.rbs-step--active .rbs-step-dot { background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; }
.rbs-step--done .rbs-step-dot { background: #10b981; color: #fff; }

/* STUDIO LAYOUT */
.rbs-studio { }
.rbs-studio-inner { display: grid; grid-template-columns: 65fr 35fr; gap: 1.5rem; align-items: start; }
.rbs-form { display: flex; flex-direction: column; gap: 1.25rem; min-width: 0; }
.rbs-side { min-width: 0; }

/* CARD */
.rbs-card { background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); border: 1px solid rgba(226,232,240,0.9); border-radius: 20px; box-shadow: 0 10px 30px -22px rgba(15,23,42,0.25); padding: clamp(1.25rem, 2.5vw, 1.75rem); transition: box-shadow 0.3s, transform 0.3s; }
.rbs-card:hover { box-shadow: 0 20px 44px -24px rgba(15,23,42,0.3); }
.rbs-card-head { display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.15rem; }
.rbs-card-head--row { justify-content: space-between; }
.rbs-card-ico { width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: #fff; }
.rbs-card-ico--blue { background: linear-gradient(135deg, #2563eb, #6366f1); box-shadow: 0 8px 16px rgba(37,99,235,0.25); }
.rbs-card-ico--violet { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 8px 16px rgba(124,58,237,0.25); }
.rbs-card-ico--amber { background: linear-gradient(135deg, #f59e0b, #f97316); box-shadow: 0 8px 16px rgba(245,158,11,0.25); }
.rbs-card-ico--emerald { background: linear-gradient(135deg, #06b6d4, #10b981); box-shadow: 0 8px 16px rgba(6,182,212,0.25); }
.rbs-card-ico--cyan { background: linear-gradient(135deg, #06b6d4, #3b82f6); box-shadow: 0 8px 16px rgba(6,182,212,0.25); }
.rbs-card-h { font-family: 'Poppins', sans-serif; font-size: 1.12rem; font-weight: 600; color: var(--rbs-ink); margin: 0; }
.rbs-card-sub { color: var(--rbs-muted); margin: 0; font-size: 0.84rem; }
.rbs-title-card { padding: 1.5rem clamp(1.25rem, 2.5vw, 1.75rem); }

/* FORM GRID */
.rbs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.9rem 1.1rem; }
.rbs-field { display: flex; flex-direction: column; gap: 0.35rem; min-width: 0; }
.rbs-field--full { grid-column: 1 / -1; }
.rbs-label { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.79rem; font-weight: 600; color: #374151; }
.rbs-label svg { color: var(--rbs-primary); }
.rbs-req { color: #ef4444; }
.rbs-input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--rbs-border); border-radius: 12px; font-size: 0.9rem; color: var(--rbs-ink); background: #fff; transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s; outline: none; }
.rbs-input::placeholder { color: #94a3b8; }
.rbs-input:focus { border-color: var(--rbs-primary); box-shadow: 0 0 0 4px rgba(37,99,235,0.12); transform: translateY(-1px); }
.rbs-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
.rbs-textarea { resize: vertical; min-height: 90px; }
.rbs-error { font-size: 0.76rem; color: #ef4444; font-weight: 500; }

/* ITEMS */
.rbs-items { display: flex; flex-direction: column; gap: 0.9rem; }
.rbs-item { border: 1px solid #eef2f7; border-radius: 16px; padding: 1rem; background: linear-gradient(180deg, #fbfcff, #fff); transition: border-color 0.25s, box-shadow 0.25s; }
.rbs-item:hover { border-color: rgba(37,99,235,0.3); box-shadow: 0 10px 24px -16px rgba(37,99,235,0.25); }
.rbs-item--row { display: flex; align-items: center; gap: 0.6rem; }
.rbs-item--row .rbs-input { flex: 1; }
.rbs-item-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
.rbs-item-title { font-weight: 700; font-size: 0.9rem; color: var(--rbs-ink); }
.rbs-item-actions { display: flex; gap: 0.35rem; }
.rbs-icon-btn { width: 32px; height: 32px; border-radius: 9px; border: 1px solid var(--rbs-border); background: #fff; color: #475569; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
.rbs-icon-btn:hover { background: #eff6ff; color: #2563eb; border-color: #2563eb; }
.rbs-icon-btn--danger:hover { background: #fef2f2; color: #dc2626; border-color: #dc2626; }
.rbs-empty { text-align: center; padding: 1.5rem 1rem; color: var(--rbs-muted); display: flex; flex-direction: column; align-items: center; gap: 0.4rem; }
.rbs-empty svg { color: #cbd5e1; }

/* CHECK */
.rbs-form-foot { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding: 0.5rem 0.25rem; }
.rbs-check { display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer; font-size: 0.88rem; font-weight: 500; user-select: none; }
.rbs-check input { display: none; }
.rbs-check-box { width: 22px; height: 22px; border-radius: 7px; border: 2px solid var(--rbs-border); display: inline-flex; align-items: center; justify-content: center; color: transparent; transition: all 0.2s; background: #fff; }
.rbs-check input:checked + .rbs-check-box { background: var(--rbs-primary); border-color: var(--rbs-primary); color: #fff; }
.rbs-autosave { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: var(--rbs-muted); }
.rbs-autosave svg { color: #10b981; }
.rbs-autosave--saving svg { color: #f59e0b; }

/* SIDEBAR */
.rbs-side-sticky { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 1.25rem; }
.rbs-side-h { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600; color: var(--rbs-ink); margin: 0; }

/* SUMMARY */
.rbs-summary-head { display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; color: var(--rbs-primary); }
.rbs-summary-head .rbs-side-h { font-size: 1.02rem; }
.rbs-summary-name { font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 700; color: var(--rbs-ink); }
.rbs-summary-title { font-size: 0.86rem; color: var(--rbs-primary); font-weight: 600; margin-bottom: 0.35rem; }
.rbs-summary-contact { font-size: 0.76rem; color: var(--rbs-muted); margin-bottom: 0.85rem; word-break: break-word; }
.rbs-summary-rows { display: flex; flex-direction: column; gap: 0.5rem; border-top: 1px solid #f1f5f9; padding-top: 0.85rem; }
.rbs-summary-row { display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; }
.rbs-summary-row span { color: var(--rbs-muted); }
.rbs-summary-row strong { color: var(--rbs-ink); font-weight: 600; }
.rbs-cap { text-transform: capitalize; }
.rbs-status { color: #10b981 !important; }

/* PROGRESS */
.rbs-progress { text-align: center; }
.rbs-progress-ring { width: 96px; height: 96px; border-radius: 50%; margin: 0 auto 0.75rem; display: flex; align-items: center; justify-content: center; }
.rbs-progress-ring span { width: 74px; height: 74px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; color: var(--rbs-primary); }
.rbs-progress-bar { height: 8px; border-radius: 999px; background: rgba(37,99,235,0.1); overflow: hidden; margin: 0.75rem 0 0.5rem; }
.rbs-progress-bar span { display: block; height: 100%; background: linear-gradient(90deg, #2563eb, #7c3aed); border-radius: 999px; transition: width 0.4s ease; }
.rbs-progress-note { font-size: 0.8rem; color: var(--rbs-muted); margin: 0; }

/* TEMPLATES */
.rbs-templates { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-bottom: 0.85rem; }
.rbs-template { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.7rem; border: 1px solid var(--rbs-border); border-radius: 12px; background: #fff; cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #374151; transition: all 0.2s; }
.rbs-template:hover { border-color: rgba(37,99,235,0.4); transform: translateY(-1px); }
.rbs-template--active { border-color: var(--rbs-primary); background: rgba(37,99,235,0.08); color: #1d4ed8; }
.rbs-template-swatch { width: 22px; height: 22px; border-radius: 7px; flex-shrink: 0; }
.rbs-template-swatch--modern { background: linear-gradient(135deg, #2563eb, #4f46e5); }
.rbs-template-swatch--professional { background: linear-gradient(135deg, #0f172a, #334155); }
.rbs-template-swatch--minimal { background: linear-gradient(135deg, #e2e8f0, #94a3b8); }
.rbs-template-swatch--creative { background: linear-gradient(135deg, #7c3aed, #ec4899); }
.rbs-export-links { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.rbs-export-links .disabled { opacity: 0.5; pointer-events: none; }

/* LIVE PREVIEW */
.rbs-preview-card { padding-bottom: 1.1rem; }
.rbs-preview-tag { margin-left: auto; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.04em; color: #1d4ed8; background: rgba(37,99,235,0.1); padding: 0.25rem 0.6rem; border-radius: 999px; }
.rbs-preview-frame { background: #eef2f7; border-radius: 14px; padding: 0.75rem; margin-bottom: 0.9rem; max-height: 480px; overflow-y: auto; }
.rbs-preview-page { background: #fff; border-radius: 8px; padding: 1.1rem 1.2rem; box-shadow: 0 8px 24px -16px rgba(15,23,42,0.35); font-family: 'Inter', sans-serif; color: #1f2937; font-size: 0.62rem; line-height: 1.45; transition: all 0.3s ease; }
.rbs-pv-head { border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 0.6rem; }
.rbs-pv-name { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 800; margin: 0; color: #0f172a; }
.rbs-pv-title { font-size: 0.7rem; font-weight: 600; color: #2563eb; margin-top: 1px; }
.rbs-pv-contact { font-size: 0.58rem; color: #6b7280; margin-top: 2px; word-break: break-word; }
.rbs-pv-block { margin-bottom: 0.6rem; }
.rbs-pv-h { font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #374151; margin-bottom: 0.25rem; }
.rbs-pv-item { margin-bottom: 0.35rem; }
.rbs-pv-item-t { font-weight: 700; font-size: 0.66rem; color: #111827; }
.rbs-pv-item-s { font-size: 0.58rem; color: #6b7280; }
.rbs-pv-text { font-size: 0.6rem; color: #4b5563; margin: 1px 0 0; }
.rbs-pv-chips { display: flex; flex-wrap: wrap; gap: 0.25rem; }
.rbs-pv-chip { font-size: 0.55rem; font-weight: 600; padding: 0.12rem 0.45rem; border-radius: 999px; background: #eff6ff; color: #1d4ed8; border: 1px solid rgba(37,99,235,0.2); }

/* TEMPLATE: MODERN (default) */
.rbs-tpl-modern .rbs-pv-name { color: #1d4ed8; }
.rbs-tpl-modern .rbs-pv-h { color: #2563eb; border-left: 3px solid #2563eb; padding-left: 0.4rem; }
.rbs-tpl-modern .rbs-pv-head { border-bottom-color: #2563eb; }

/* TEMPLATE: PROFESSIONAL */
.rbs-tpl-professional { background: #fff; }
.rbs-tpl-professional .rbs-pv-head { border-bottom: 2px solid #0f172a; }
.rbs-tpl-professional .rbs-pv-name { color: #0f172a; letter-spacing: -0.01em; }
.rbs-tpl-professional .rbs-pv-title { color: #334155; }
.rbs-tpl-professional .rbs-pv-h { color: #0f172a; border-bottom: 1px solid #cbd5e1; padding-bottom: 2px; }
.rbs-tpl-professional .rbs-pv-chip { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }

/* TEMPLATE: MINIMAL */
.rbs-tpl-minimal { background: #fff; padding: 1.3rem 1.1rem; }
.rbs-tpl-minimal .rbs-pv-head { border: 0; text-align: center; padding-bottom: 0.6rem; }
.rbs-tpl-minimal .rbs-pv-name { font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.92rem; }
.rbs-tpl-minimal .rbs-pv-title, .rbs-tpl-minimal .rbs-pv-contact { text-align: center; }
.rbs-tpl-minimal .rbs-pv-h { text-align: center; color: #9ca3af; font-weight: 600; letter-spacing: 0.12em; }
.rbs-tpl-minimal .rbs-pv-item-t, .rbs-tpl-minimal .rbs-pv-item-s, .rbs-tpl-minimal .rbs-pv-text { text-align: center; }
.rbs-tpl-minimal .rbs-pv-chips { justify-content: center; }
.rbs-tpl-minimal .rbs-pv-chip { background: transparent; color: #6b7280; border: 1px solid #e5e7eb; }

/* TEMPLATE: CREATIVE */
.rbs-tpl-creative { background: linear-gradient(180deg, #fdf4ff 0%, #ffffff 40%); }
.rbs-tpl-creative .rbs-pv-head { border: 0; background: linear-gradient(135deg, #7c3aed, #ec4899); border-radius: 10px; padding: 0.7rem 0.8rem; color: #fff; }
.rbs-tpl-creative .rbs-pv-name, .rbs-tpl-creative .rbs-pv-title, .rbs-tpl-creative .rbs-pv-contact { color: #fff; }
.rbs-tpl-creative .rbs-pv-title { opacity: 0.92; }
.rbs-tpl-creative .rbs-pv-h { color: #7c3aed; }
.rbs-tpl-creative .rbs-pv-block .rbs-pv-h::before { content: '◆ '; color: #ec4899; }
.rbs-tpl-creative .rbs-pv-chip { background: rgba(124,58,237,0.12); color: #7c3aed; border-color: rgba(124,58,237,0.25); }

/* TIPS */
.rbs-tips { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.55rem; }
.rbs-tips li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.82rem; color: #475569; line-height: 1.4; }
.rbs-tips svg { color: #10b981; flex-shrink: 0; margin-top: 2px; }

/* BUTTONS */
.rbs-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem; padding: 0.7rem 1.3rem; border-radius: 0.85rem; font-weight: 600; font-size: 0.9rem; border: 0; cursor: pointer; text-decoration: none; transition: all 0.25s cubic-bezier(0.22,1,0.36,1); white-space: nowrap; }
.rbs-btn-sm { padding: 0.5rem 0.9rem; font-size: 0.82rem; border-radius: 0.7rem; }
.rbs-btn-primary { background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; box-shadow: 0 10px 24px rgba(37,99,235,0.28); }
.rbs-btn-primary:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 14px 32px rgba(37,99,235,0.38); }
.rbs-btn-soft { background: #eef2ff; color: #4338ca; }
.rbs-btn-soft:hover { background: #e0e7ff; color: #3730a3; transform: translateY(-1px); }
.rbs-btn-outline { background: #fff; color: var(--rbs-primary); border: 1px solid var(--rbs-border); }
.rbs-btn-outline:hover { border-color: var(--rbs-primary); color: var(--rbs-primary); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.12); }
.rbs-btn-ghost { background: #f1f5f9; color: #475569; }
.rbs-btn-ghost:hover { background: #e2e8f0; color: #1e293b; transform: translateY(-1px); }

/* SAVE BAR */
.rbs-savebar { position: fixed; left: 0; right: 0; bottom: 0; z-index: 900; padding: 0.85rem clamp(1rem, 3vw, 2.25rem); pointer-events: none; }
.rbs-savebar-inner { max-width: 1320px; margin: 0 auto; background: rgba(255,255,255,0.92); backdrop-filter: blur(16px); border: 1px solid rgba(226,232,240,0.9); border-radius: 18px; box-shadow: 0 20px 50px -24px rgba(15,23,42,0.4); padding: 0.75rem 1.1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; pointer-events: auto; }
.rbs-savebar-info { font-size: 0.84rem; color: var(--rbs-muted); display: inline-flex; align-items: center; gap: 0.4rem; }
.rbs-savebar-info svg { color: #10b981; }
.rbs-savebar-status--err { color: #dc2626; font-weight: 600; }
.rbs-savebar-status--err svg { color: #dc2626; }
.rbs-savebar-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; }

@media (max-width: 991.98px) {
    .rbs-studio-inner { grid-template-columns: 1fr; }
    .rbs-side-sticky { position: static; }
    .rbs-hero-illo { display: none; }
}
@media (max-width: 575.98px) {
    .rbs-grid { grid-template-columns: 1fr; }
    .rbs-savebar-inner { flex-direction: column; align-items: stretch; }
    .rbs-savebar-actions { justify-content: stretch; }
    .rbs-savebar-actions .rbs-btn { flex: 1; }
    .rbs-meta-badge { display: none; }
}
</style>
