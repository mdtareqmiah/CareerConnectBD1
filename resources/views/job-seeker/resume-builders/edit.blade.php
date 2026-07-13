@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.resume-builders.index') }}">Resume Builder</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h4 mb-1">Professional Resume Builder</h1>
                            <p class="text-muted mb-0">Edit your CV and preview changes instantly.</p>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="refresh-profile">Refresh From Profile</button>
                    </div>

                    <form id="resume-builder-form" action="{{ route('job-seeker.resume-builders.update', $resumeBuilder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="personal_information" id="personal_information_input" value="{{ old('personal_information') ? json_encode(old('personal_information')) : json_encode($resumeBuilder->personal_information) }}">
                        <input type="hidden" name="education" id="education_input" value="{{ old('education') ? json_encode(old('education')) : json_encode($resumeBuilder->education) }}">
                        <input type="hidden" name="experience" id="experience_input" value="{{ old('experience') ? json_encode(old('experience')) : json_encode($resumeBuilder->experience) }}">
                        <input type="hidden" name="skills" id="skills_input" value="{{ old('skills') ? json_encode(old('skills')) : json_encode($resumeBuilder->skills) }}">
                        <input type="hidden" name="projects" id="projects_input" value="{{ old('projects') ? json_encode(old('projects')) : json_encode($resumeBuilder->projects) }}">
                        <input type="hidden" name="certifications" id="certifications_input" value="{{ old('certifications') ? json_encode(old('certifications')) : json_encode($resumeBuilder->certifications) }}">
                        <input type="hidden" name="languages" id="languages_input" value="{{ old('languages') ? json_encode(old('languages')) : json_encode($resumeBuilder->languages) }}">
                        <input type="hidden" name="references" id="references_input" value="{{ old('references') ? json_encode(old('references')) : json_encode($resumeBuilder->references) }}">
                        <input type="hidden" name="social_links" id="social_links_input" value="{{ old('social_links') ? json_encode(old('social_links')) : json_encode($resumeBuilder->social_links) }}">
                        <input type="hidden" name="template" id="template_input" value="{{ old('template', $resumeBuilder->template) }}">
                        <input type="hidden" name="status" id="status" value="{{ old('status', $resumeBuilder->status) }}">
                        <input type="hidden" name="is_default" id="is_default" value="{{ old('is_default', $resumeBuilder->is_default) ? 1 : 0 }}">
                        <input type="hidden" name="resume_builder_id" id="resume_builder_id" value="{{ $resumeBuilder->id }}">

                        <div class="mb-3">
                            <label for="title" class="form-label">Resume Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $resumeBuilder->title) }}" class="form-control @error('title') is-invalid @enderror" required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="accordion" id="resumeSectionsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPersonal">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="true" aria-controls="collapsePersonal">
                                        Personal Information
                                    </button>
                                </h2>
                                <div id="collapsePersonal" class="accordion-collapse collapse show" aria-labelledby="headingPersonal" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">First Name</label>
                                                <input type="text" id="first_name" class="form-control" maxlength="100" placeholder="First name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" id="last_name" class="form-control" maxlength="100" placeholder="Last name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" id="email" class="form-control" maxlength="255" placeholder="Email">
                                                <div class="invalid-feedback" id="email_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Phone</label>
                                                <input type="text" id="phone" class="form-control" maxlength="25" placeholder="Phone">
                                                <div class="invalid-feedback" id="phone_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">LinkedIn</label>
                                                <input type="url" id="linkedin_url" class="form-control" maxlength="255" placeholder="https://linkedin.com/...">
                                                <div class="invalid-feedback" id="linkedin_url_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">GitHub</label>
                                                <input type="url" id="github_url" class="form-control" maxlength="255" placeholder="https://github.com/...">
                                                <div class="invalid-feedback" id="github_url_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Portfolio</label>
                                                <input type="url" id="portfolio_url" class="form-control" maxlength="255" placeholder="Portfolio URL">
                                                <div class="invalid-feedback" id="portfolio_url_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Website</label>
                                                <input type="url" id="website_url" class="form-control" maxlength="255" placeholder="Website URL">
                                                <div class="invalid-feedback" id="website_url_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSummary">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSummary" aria-expanded="false" aria-controls="collapseSummary">
                                        Professional Summary
                                    </button>
                                </h2>
                                <div id="collapseSummary" class="accordion-collapse collapse" aria-labelledby="headingSummary" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <textarea id="professional_summary" class="form-control" rows="4" maxlength="2000" placeholder="Write your summary..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEducation">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEducation" aria-expanded="false" aria-controls="collapseEducation">
                                        Education
                                    </button>
                                </h2>
                                <div id="collapseEducation" class="accordion-collapse collapse" aria-labelledby="headingEducation" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="education_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addEducation">Add Education</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingExperience">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExperience" aria-expanded="false" aria-controls="collapseExperience">
                                        Experience
                                    </button>
                                </h2>
                                <div id="collapseExperience" class="accordion-collapse collapse" aria-labelledby="headingExperience" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="experience_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addExperience">Add Experience</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSkills">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSkills" aria-expanded="false" aria-controls="collapseSkills">
                                        Skills
                                    </button>
                                </h2>
                                <div id="collapseSkills" class="accordion-collapse collapse" aria-labelledby="headingSkills" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="skills_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addSkill">Add Skill</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingProjects">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjects" aria-expanded="false" aria-controls="collapseProjects">
                                        Projects
                                    </button>
                                </h2>
                                <div id="collapseProjects" class="accordion-collapse collapse" aria-labelledby="headingProjects" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="projects_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addProject">Add Project</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCertifications">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCertifications" aria-expanded="false" aria-controls="collapseCertifications">
                                        Certifications
                                    </button>
                                </h2>
                                <div id="collapseCertifications" class="accordion-collapse collapse" aria-labelledby="headingCertifications" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="certifications_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addCertification">Add Certification</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLanguages">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLanguages" aria-expanded="false" aria-controls="collapseLanguages">
                                        Languages
                                    </button>
                                </h2>
                                <div id="collapseLanguages" class="accordion-collapse collapse" aria-labelledby="headingLanguages" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="languages_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addLanguage">Add Language</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingReferences">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReferences" aria-expanded="false" aria-controls="collapseReferences">
                                        References
                                    </button>
                                </h2>
                                <div id="collapseReferences" class="accordion-collapse collapse" aria-labelledby="headingReferences" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div id="references_items"></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="addReference">Add Reference</button>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSocial">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSocial" aria-expanded="false" aria-controls="collapseSocial">
                                        Social Links
                                    </button>
                                </h2>
                                <div id="collapseSocial" class="accordion-collapse collapse" aria-labelledby="headingSocial" data-bs-parent="#resumeSectionsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">LinkedIn</label>
                                                <input type="url" id="social_linkedin" class="form-control" maxlength="255" placeholder="LinkedIn URL">
                                                <div class="invalid-feedback" id="social_linkedin_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">GitHub</label>
                                                <input type="url" id="social_github" class="form-control" maxlength="255" placeholder="GitHub URL">
                                                <div class="invalid-feedback" id="social_github_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Portfolio</label>
                                                <input type="url" id="social_portfolio" class="form-control" maxlength="255" placeholder="Portfolio URL">
                                                <div class="invalid-feedback" id="social_portfolio_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Website</label>
                                                <input type="url" id="social_website" class="form-control" maxlength="255" placeholder="Website URL">
                                                <div class="invalid-feedback" id="social_website_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted" id="autosave-status">Draft saved automatically every 30 seconds.</div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('job-seeker.resume-builders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="button" class="btn btn-secondary" id="save-draft">Save Draft</button>
                                <button type="submit" class="btn btn-primary">Update Resume Builder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="position-sticky" style="top: 90px;">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h2 class="h5">Live CV Preview</h2>
                        <p class="text-muted">Preview updates instantly while you edit.</p>
                        <div class="row gx-3 gy-3 mt-3">
                            <div class="col-sm-6">
                                <label class="form-label small mb-1" for="preview_template">Template</label>
                                <select id="preview_template" class="form-select form-select-sm">
                                    <option value="modern">Modern</option>
                                    <option value="professional">Professional</option>
                                    <option value="minimal">Minimal</option>
                                    <option value="creative">Creative</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small mb-1" for="preview_spacing">Spacing</label>
                                <select id="preview_spacing" class="form-select form-select-sm">
                                    <option value="compact">Compact</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="spacious">Spacious</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <a id="preview_resume_link" class="btn btn-outline-secondary btn-sm disabled" target="_blank" rel="noopener">Full Preview</a>
                            <a id="download_pdf_link" class="btn btn-outline-primary btn-sm disabled" target="_blank" rel="noopener">Download PDF</a>
                            <a id="print_resume_link" class="btn btn-outline-success btn-sm disabled" target="_blank" rel="noopener">Print Resume</a>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body" id="resume-preview">
                        <div id="preview-content">
                            <div class="border-bottom mb-3">
                                <h3 id="preview-name" class="h4 mb-1">Your Name</h3>
                                <p id="preview-title" class="text-muted mb-1">Professional Title</p>
                                <p id="preview-contact" class="small text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <h4 class="h6">Professional Summary</h4>
                                <p id="preview-summary" class="small text-muted">A concise summary of your experience, goals, and standout skills.</p>
                            </div>
                            <div id="preview-sections"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@php
    $profile = auth()->user()?->jobSeekerProfile;
    $userEmail = auth()->user()?->email;
    $initialTemplate = old('template', $resumeBuilder->template) ?: 'modern';
    $currentBuilderData = [
        'id' => $resumeBuilder->id,
        'title' => $resumeBuilder->title,
        'professional_summary' => $resumeBuilder->professional_summary,
        'personal_information' => $resumeBuilder->personal_information ?? [],
        'education' => $resumeBuilder->education ?? [],
        'experience' => $resumeBuilder->experience ?? [],
        'skills' => $resumeBuilder->skills ?? [],
        'projects' => $resumeBuilder->projects ?? [],
        'certifications' => $resumeBuilder->certifications ?? [],
        'languages' => $resumeBuilder->languages ?? [],
        'references' => $resumeBuilder->references ?? [],
        'social_links' => $resumeBuilder->social_links ?? [],
    ];
    $profileData = $profile ? [
        'first_name' => $profile->first_name,
        'last_name' => $profile->last_name,
        'phone' => $profile->phone,
        'linkedin_url' => $profile->linkedin_url,
        'github_url' => $profile->github_url,
        'portfolio_url' => $profile->portfolio_url,
        'website_url' => $profile->website_url,
    ] : [];

    $profileEducations = $profile ? $profile->educations->map(function ($item) {
        return [
            'school' => $item->school,
            'degree' => $item->degree,
            'field_of_study' => $item->field_of_study,
            'start_date' => optional($item->start_date)->format('Y-m'),
            'end_date' => optional($item->end_date)->format('Y-m'),
            'notes' => $item->notes,
        ];
    })->toArray() : [];

    $profileExperiences = $profile ? $profile->experiences->map(function ($item) {
        return [
            'company' => $item->company,
            'title' => $item->title,
            'start_date' => optional($item->start_date)->format('Y-m'),
            'end_date' => optional($item->end_date)->format('Y-m'),
            'description' => $item->description,
        ];
    })->toArray() : [];

    $profileSkills = $profile ? $profile->skills->pluck('name')->toArray() : [];
@endphp
<script>
    const profileData = @json($profileData);
    const profileEducations = @json($profileEducations);
    const profileExperiences = @json($profileExperiences);
    const profileSkills = @json($profileSkills);
    const userEmail = @json($userEmail);
    const initialTemplate = @json($initialTemplate);
    const currentBuilder = @json($currentBuilderData);

    const initialSections = {
        personal_information: {
            ...currentBuilder.personal_information,
            first_name: currentBuilder.personal_information.first_name || profileData.first_name || '',
            last_name: currentBuilder.personal_information.last_name || profileData.last_name || '',
            email: currentBuilder.personal_information.email || userEmail || '',
            phone: currentBuilder.personal_information.phone || profileData.phone || '',
            linkedin_url: currentBuilder.personal_information.linkedin_url || profileData.linkedin_url || '',
            github_url: currentBuilder.personal_information.github_url || profileData.github_url || '',
            portfolio_url: currentBuilder.personal_information.portfolio_url || profileData.portfolio_url || '',
            website_url: currentBuilder.personal_information.website_url || profileData.website_url || '',
        },
        professional_summary: currentBuilder.professional_summary || '',
        education: currentBuilder.education.length ? currentBuilder.education : profileEducations,
        experience: currentBuilder.experience.length ? currentBuilder.experience : profileExperiences,
        skills: currentBuilder.skills.length ? currentBuilder.skills : profileSkills,
        projects: currentBuilder.projects || [],
        certifications: currentBuilder.certifications || [],
        languages: currentBuilder.languages || [],
        references: currentBuilder.references || [],
        social_links: {
            linkedin: currentBuilder.social_links.linkedin || profileData.linkedin_url || '',
            github: currentBuilder.social_links.github || profileData.github_url || '',
            portfolio: currentBuilder.social_links.portfolio || profileData.portfolio_url || '',
            website: currentBuilder.social_links.website || profileData.website_url || '',
        },
    };

    const state = {
        personal_information: {...initialSections.personal_information},
        professional_summary: initialSections.professional_summary,
        education: [...initialSections.education],
        experience: [...initialSections.experience],
        skills: [...initialSections.skills],
        projects: [...initialSections.projects],
        certifications: [...initialSections.certifications],
        languages: [...initialSections.languages],
        references: [...initialSections.references],
        social_links: {...initialSections.social_links},
        title: currentBuilder.title || '',
        template: initialTemplate,
        previewTemplate: initialTemplate,
        previewSpacing: currentBuilder.personal_information.spacing || 'normal',
    };

    state.personal_information.theme = state.personal_information.theme || state.template;
    state.personal_information.spacing = state.personal_information.spacing || state.previewSpacing;

    const timedSave = { interval: null, dirty: false, builderId: currentBuilder.id };

    const setInputValue = (selector, value) => {
        const el = document.querySelector(selector);
        if (el) el.value = value;
    };

    const normalizeState = () => {
        document.getElementById('personal_information_input').value = JSON.stringify(state.personal_information);
        document.getElementById('education_input').value = JSON.stringify(state.education);
        document.getElementById('experience_input').value = JSON.stringify(state.experience);
        document.getElementById('skills_input').value = JSON.stringify(state.skills);
        document.getElementById('projects_input').value = JSON.stringify(state.projects);
        document.getElementById('certifications_input').value = JSON.stringify(state.certifications);
        document.getElementById('languages_input').value = JSON.stringify(state.languages);
        document.getElementById('references_input').value = JSON.stringify(state.references);
        document.getElementById('social_links_input').value = JSON.stringify(state.social_links);
        document.getElementById('title').value = state.title;
        document.getElementById('template_input').value = state.template;
        renderPreview();
        updateExportLinks();
        timedSave.dirty = true;
    };

    const getBuilderId = () => document.getElementById('resume_builder_id')?.value || timedSave.builderId;

    const updateExportLinks = () => {
        const builderId = getBuilderId();
        const previewLink = document.getElementById('preview_resume_link');
        const downloadLink = document.getElementById('download_pdf_link');
        const printLink = document.getElementById('print_resume_link');
        const template = state.template || 'modern';
        const spacing = state.previewSpacing || 'normal';

        if (!builderId) {
            [previewLink, downloadLink, printLink].forEach(link => {
                if (!link) return;
                link.classList.add('disabled');
                link.removeAttribute('href');
            });
            return;
        }

        const query = `?template=${encodeURIComponent(template)}&spacing=${encodeURIComponent(spacing)}`;
        const previewUrl = `/job-seeker/resume-builders/${builderId}/preview${query}`;
        const downloadUrl = `/job-seeker/resume-builders/${builderId}/download?template=${encodeURIComponent(template)}`;
        const printUrl = `/job-seeker/resume-builders/${builderId}/print?template=${encodeURIComponent(template)}`;

        previewLink.href = previewUrl;
        downloadLink.href = downloadUrl;
        printLink.href = printUrl;

        [previewLink, downloadLink, printLink].forEach(link => link.classList.remove('disabled'));
    };

    const moveItem = (collection, index, delta) => {
        const target = index + delta;
        if (target < 0 || target >= collection.length) return;
        [collection[index], collection[target]] = [collection[target], collection[index]];
        timedSave.dirty = true;
    };

    const renderArrayField = (collection, containerId, itemRenderer) => {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = collection.map(itemRenderer).join('');
    };

    const renderEducationItems = () => {
        renderArrayField(state.education, 'education_items', (item, index) => `
            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Education ${index + 1}</strong>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveEducation(${index}, -1)">↑</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveEducation(${index}, 1)">↓</button>
                        <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeEducation(${index})">Remove</button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="School" value="${item.school || ''}" onchange="window.resumeBuilder.updateEducation(${index}, 'school', this.value)"></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Degree" value="${item.degree || ''}" onchange="window.resumeBuilder.updateEducation(${index}, 'degree', this.value)"></div>
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Field Of Study" value="${item.field_of_study || ''}" onchange="window.resumeBuilder.updateEducation(${index}, 'field_of_study', this.value)"></div>
                    <div class="col-md-4"><input type="month" class="form-control" value="${item.start_date || ''}" onchange="window.resumeBuilder.updateEducation(${index}, 'start_date', this.value)"></div>
                    <div class="col-md-4"><input type="month" class="form-control" value="${item.end_date || ''}" onchange="window.resumeBuilder.updateEducation(${index}, 'end_date', this.value)"></div>
                    <div class="col-12"><textarea class="form-control" rows="2" placeholder="Notes" onchange="window.resumeBuilder.updateEducation(${index}, 'notes', this.value)">${item.notes || ''}</textarea></div>
                </div>
            </div>
        `);
    };

    const renderExperienceItems = () => {
        renderArrayField(state.experience, 'experience_items', (item, index) => `
            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Experience ${index + 1}</strong>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveExperience(${index}, -1)">↑</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveExperience(${index}, 1)">↓</button>
                        <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeExperience(${index})">Remove</button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Company" value="${item.company || ''}" onchange="window.resumeBuilder.updateExperience(${index}, 'company', this.value)"></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Title" value="${item.title || ''}" onchange="window.resumeBuilder.updateExperience(${index}, 'title', this.value)"></div>
                    <div class="col-md-3"><input type="month" class="form-control" value="${item.start_date || ''}" onchange="window.resumeBuilder.updateExperience(${index}, 'start_date', this.value)"></div>
                    <div class="col-md-3"><input type="month" class="form-control" value="${item.end_date || ''}" onchange="window.resumeBuilder.updateExperience(${index}, 'end_date', this.value)"></div>
                    <div class="col-12"><textarea class="form-control" rows="2" placeholder="Description" onchange="window.resumeBuilder.updateExperience(${index}, 'description', this.value)">${item.description || ''}</textarea></div>
                </div>
            </div>
        `);
    };

    const renderSkillItems = () => {
        renderArrayField(state.skills, 'skills_items', (item, index) => `
            <div class="input-group mb-2">
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveSkill(${index}, -1)">↑</button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveSkill(${index}, 1)">↓</button>
                <input type="text" class="form-control" placeholder="Skill" value="${item || ''}" onchange="window.resumeBuilder.updateSkill(${index}, this.value)">
                <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeSkill(${index})">Remove</button>
            </div>
        `);
    };

    const renderProjectItems = () => {
        renderArrayField(state.projects, 'projects_items', (item, index) => `
            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Project ${index + 1}</strong>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveProject(${index}, -1)">↑</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveProject(${index}, 1)">↓</button>
                        <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeProject(${index})">Remove</button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Project Name" value="${item.name || ''}" onchange="window.resumeBuilder.updateProject(${index}, 'name', this.value)"></div>
                    <div class="col-md-6"><input type="url" class="form-control" placeholder="Project Link" value="${item.link || ''}" onchange="window.resumeBuilder.updateProject(${index}, 'link', this.value)"></div>
                    <div class="col-12"><textarea class="form-control" rows="2" placeholder="Description" onchange="window.resumeBuilder.updateProject(${index}, 'description', this.value)">${item.description || ''}</textarea></div>
                </div>
            </div>
        `);
    };

    const renderCertificationItems = () => {
        renderArrayField(state.certifications, 'certifications_items', (item, index) => `
            <div class="input-group mb-2">
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveCertification(${index}, -1)">↑</button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveCertification(${index}, 1)">↓</button>
                <input type="text" class="form-control" placeholder="Certification" value="${item.name || ''}" onchange="window.resumeBuilder.updateCertification(${index}, 'name', this.value)">
                <input type="text" class="form-control" placeholder="Issuer" value="${item.issuer || ''}" onchange="window.resumeBuilder.updateCertification(${index}, 'issuer', this.value)">
                <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeCertification(${index})">Remove</button>
            </div>
        `);
    };

    const renderLanguageItems = () => {
        renderArrayField(state.languages, 'languages_items', (item, index) => `
            <div class="input-group mb-2">
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveLanguage(${index}, -1)">↑</button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveLanguage(${index}, 1)">↓</button>
                <input type="text" class="form-control" placeholder="Language" value="${item.name || ''}" onchange="window.resumeBuilder.updateLanguage(${index}, 'name', this.value)">
                <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeLanguage(${index})">Remove</button>
            </div>
        `);
    };

    const renderReferenceItems = () => {
        renderArrayField(state.references, 'references_items', (item, index) => `
            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Reference ${index + 1}</strong>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveReference(${index}, -1)">↑</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.resumeBuilder.moveReference(${index}, 1)">↓</button>
                        <button type="button" class="btn btn-outline-danger" onclick="window.resumeBuilder.removeReference(${index})">Remove</button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Name" value="${item.name || ''}" onchange="window.resumeBuilder.updateReference(${index}, 'name', this.value)"></div>
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Position" value="${item.position || ''}" onchange="window.resumeBuilder.updateReference(${index}, 'position', this.value)"></div>
                    <div class="col-md-4"><input type="text" class="form-control" placeholder="Contact" value="${item.contact || ''}" onchange="window.resumeBuilder.updateReference(${index}, 'contact', this.value)"></div>
                </div>
            </div>
        `);
    };

    const renderPreview = () => {
        document.getElementById('preview-name').textContent = `${state.personal_information.first_name || 'Your'} ${state.personal_information.last_name || 'Name'}`.trim();
        document.getElementById('preview-title').textContent = state.title || 'Professional Title';
        document.getElementById('preview-contact').innerHTML = [
            state.personal_information.email,
            state.personal_information.phone,
            state.social_links.linkedin,
            state.social_links.github,
            state.social_links.portfolio,
            state.social_links.website,
        ].filter(Boolean).map(value => `<span>${value}</span>`).join(' • ');
        document.getElementById('preview-summary').textContent = state.professional_summary || 'A concise summary of your experience, goals, and standout skills.';

        const sections = [];

        if (state.education.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Education</h4>${state.education.map(item => `<div class="mb-2"><strong>${item.degree || item.school || ''}</strong><div class="small text-muted">${item.school || ''} ${item.start_date || ''} - ${item.end_date || 'Present'}</div><p class="small mb-0">${item.notes || ''}</p></div>`).join('')}</div>`);
        }

        if (state.experience.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Experience</h4>${state.experience.map(item => `<div class="mb-2"><strong>${item.title || item.company || ''}</strong><div class="small text-muted">${item.company || ''} ${item.start_date || ''} - ${item.end_date || 'Present'}</div><p class="small mb-0">${item.description || ''}</p></div>`).join('')}</div>`);
        }

        if (state.skills.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Skills</h4><div class="small text-muted">${state.skills.filter(Boolean).join(', ')}</div></div>`);
        }

        if (state.projects.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Projects</h4>${state.projects.map(item => `<div class="mb-2"><strong>${item.name || ''}</strong><div class="small text-muted">${item.link || ''}</div><p class="small mb-0">${item.description || ''}</p></div>`).join('')}</div>`);
        }

        if (state.certifications.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Certifications</h4>${state.certifications.map(item => `<div class="mb-2"><strong>${item.name || ''}</strong><div class="small text-muted">${item.issuer || ''} ${item.date || ''}</div></div>`).join('')}</div>`);
        }

        if (state.languages.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">Languages</h4><div class="small text-muted">${state.languages.map(item => item.name || '').filter(Boolean).join(', ')}</div></div>`);
        }

        if (state.references.length) {
            sections.push(`<div class="mb-3"><h4 class="h6">References</h4>${state.references.map(item => `<div class="mb-2"><strong>${item.name || ''}</strong><div class="small text-muted">${item.position || ''}</div><p class="small mb-0">${item.contact || ''}</p></div>`).join('')}</div>`);
        }

        if ([state.social_links.linkedin, state.social_links.github, state.social_links.portfolio, state.social_links.website].some(Boolean)) {
            sections.push(`<div class="mb-3"><h4 class="h6">Social Links</h4><div class="small text-muted">${['linkedin','github','portfolio','website'].map(key => state.social_links[key] ? `<div>${key.charAt(0).toUpperCase() + key.slice(1)}: ${state.social_links[key]}</div>` : '').join('')}</div></div>`);
        }

        document.getElementById('preview-sections').innerHTML = sections.join('');
    };

    const syncPersonalInformation = () => {
        ['first_name','last_name','email','phone','linkedin_url','github_url','portfolio_url','website_url'].forEach(key => {
            const input = document.getElementById(key);
            if (!input) return;
            input.value = state.personal_information[key] || '';
            input.addEventListener('input', () => {
                state.personal_information[key] = input.value;
                normalizeState();
            });
        });
    };

    const syncProfessionalSummary = () => {
        const textarea = document.getElementById('professional_summary');
        textarea.value = state.professional_summary || '';
        textarea.addEventListener('input', () => {
            state.professional_summary = textarea.value;
            normalizeState();
        });
    };

    const bindSocialLinks = () => {
        ['linkedin','github','portfolio','website'].forEach(key => {
            const input = document.getElementById(`social_${key}`);
            if (!input) return;
            input.value = state.social_links[key] || '';
            input.addEventListener('input', () => {
                state.social_links[key] = input.value;
                normalizeState();
            });
        });
    };

    const syncInputs = () => {
        ['first_name','last_name','email','phone','linkedin_url','github_url','portfolio_url','website_url'].forEach(key => {
            const input = document.getElementById(key);
            if (!input) return;
            input.value = state.personal_information[key] || '';
        });
        const summaryInput = document.getElementById('professional_summary');
        if (summaryInput) {
            summaryInput.value = state.professional_summary || '';
        }
        ['linkedin','github','portfolio','website'].forEach(key => {
            const input = document.getElementById(`social_${key}`);
            if (!input) return;
            input.value = state.social_links[key] || '';
        });
        const titleInput = document.getElementById('title');
        if (titleInput) {
            titleInput.value = state.title || '';
        }
    };

    const bindTitle = () => {
        const titleInput = document.getElementById('title');
        if (!titleInput) return;
        titleInput.value = state.title;
        titleInput.addEventListener('input', () => {
            state.title = titleInput.value;
            timedSave.dirty = true;
        });
    };

    const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const saveDraft = async () => {
        const statusElement = document.getElementById('autosave-status');
        statusElement.textContent = 'Saving draft...';
        normalizeState();

        const builderId = timedSave.builderId;
        const url = builderId ? `/job-seeker/resume-builders/${builderId}` : '{{ route('job-seeker.resume-builders.store') }}';
        const method = builderId ? 'PATCH' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({
                title: state.title,
                professional_summary: state.professional_summary,
                template: state.template,
                status: document.getElementById('status').value,
                is_default: document.getElementById('is_default').value,
                personal_information: state.personal_information,
                education: state.education,
                experience: state.experience,
                skills: state.skills,
                projects: state.projects,
                certifications: state.certifications,
                languages: state.languages,
                references: state.references,
                social_links: state.social_links,
            }),
        });

        if (!response.ok) {
            statusElement.textContent = 'Auto save error. Check your fields.';
            return;
        }

        const payload = await response.json();
        timedSave.builderId = payload.id || timedSave.builderId;
        document.getElementById('resume_builder_id').value = timedSave.builderId;
        updateExportLinks();
        statusElement.textContent = 'Draft saved automatically every 30 seconds.';
        timedSave.dirty = false;
    };

    const validateUrl = (value) => {
        if (!value) return true;
        try { new URL(value); return true; } catch { return false; }
    };

    const validatePhone = (value) => /^[0-9+\-()\s]{6,25}$/.test(value);
    const validateEmail = (value) => /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value);

    const setValidationState = (id, valid, message = '') => {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = message;
        el.classList.toggle('d-block', !valid);
        el.classList.toggle('d-none', valid);
    };

    const refreshFromProfile = () => {
        if (profileEducations.length) {
            state.education = [...state.education, ...profileEducations.filter(item => !state.education.some(existing => existing.school === item.school && existing.degree === item.degree))];
        }
        if (profileExperiences.length) {
            state.experience = [...state.experience, ...profileExperiences.filter(item => !state.experience.some(existing => existing.company === item.company && existing.title === item.title))];
        }
        if (profileSkills.length) {
            state.skills = [...new Set([...state.skills, ...profileSkills])];
        }
        state.personal_information.first_name ||= profileData.first_name || '';
        state.personal_information.last_name ||= profileData.last_name || '';
        state.personal_information.phone ||= profileData.phone || '';
        state.social_links.linkedin ||= profileData.linkedin_url || '';
        state.social_links.github ||= profileData.github_url || '';
        state.social_links.portfolio ||= profileData.portfolio_url || '';
        state.social_links.website ||= profileData.website_url || '';
        syncInputs();
        normalizeState();
    };

    document.addEventListener('DOMContentLoaded', () => {
        syncPersonalInformation();
        syncProfessionalSummary();
        bindTitle();
        bindSocialLinks();
        renderEducationItems();
        renderExperienceItems();
        renderSkillItems();
        renderProjectItems();
        renderCertificationItems();
        renderLanguageItems();
        renderReferenceItems();
        renderPreview();
        normalizeState();

        document.getElementById('addEducation').addEventListener('click', () => { state.education.push({ school: '', degree: '', field_of_study: '', start_date: '', end_date: '', notes: '' }); renderEducationItems(); normalizeState(); });
        document.getElementById('addExperience').addEventListener('click', () => { state.experience.push({ company: '', title: '', start_date: '', end_date: '', description: '' }); renderExperienceItems(); normalizeState(); });
        document.getElementById('addSkill').addEventListener('click', () => { state.skills.push(''); renderSkillItems(); normalizeState(); });
        document.getElementById('addProject').addEventListener('click', () => { state.projects.push({ name: '', link: '', description: '' }); renderProjectItems(); normalizeState(); });
        document.getElementById('addCertification').addEventListener('click', () => { state.certifications.push({ name: '', issuer: '', date: '' }); renderCertificationItems(); normalizeState(); });
        document.getElementById('addLanguage').addEventListener('click', () => { state.languages.push({ name: '' }); renderLanguageItems(); normalizeState(); });
        document.getElementById('addReference').addEventListener('click', () => { state.references.push({ name: '', position: '', contact: '' }); renderReferenceItems(); normalizeState(); });

        document.getElementById('preview_template').value = state.template || 'modern';
        document.getElementById('preview_spacing').value = state.previewSpacing;
        document.getElementById('preview_template').addEventListener('change', (event) => {
            state.previewTemplate = event.target.value;
            state.template = event.target.value;
            state.personal_information.theme = state.template;
            document.getElementById('template_input').value = state.template;
            normalizeState();
        });

        document.getElementById('preview_spacing').addEventListener('change', (event) => {
            state.previewSpacing = event.target.value;
            state.personal_information.spacing = state.previewSpacing;
            normalizeState();
        });

        document.getElementById('refresh-profile').addEventListener('click', refreshFromProfile);
        document.getElementById('save-draft').addEventListener('click', saveDraft);

        setInterval(() => {
            if (!timedSave.dirty) return;
            saveDraft();
        }, 30000);

        window.addEventListener('beforeunload', (event) => {
            if (!timedSave.dirty) return;
            event.preventDefault();
            event.returnValue = '';
        });

        document.getElementById('resume-builder-form').addEventListener('submit', (event) => {
            const emailValid = validateEmail(state.personal_information.email);
            const phoneValid = validatePhone(state.personal_information.phone);
            const socialValid = ['linkedin','github','portfolio','website'].every(key => validateUrl(state.social_links[key]));
            setValidationState('email_error', emailValid, 'Enter a valid email address.');
            setValidationState('phone_error', phoneValid, 'Enter a valid phone number.');
            setValidationState('social_linkedin_error', validateUrl(state.social_links.linkedin), 'Enter a valid LinkedIn URL.');
            setValidationState('social_github_error', validateUrl(state.social_links.github), 'Enter a valid GitHub URL.');
            setValidationState('social_portfolio_error', validateUrl(state.social_links.portfolio), 'Enter a valid portfolio URL.');
            setValidationState('social_website_error', validateUrl(state.social_links.website), 'Enter a valid website URL.');

            if (!emailValid || !phoneValid || !socialValid) {
                event.preventDefault();
                return false;
            }

            normalizeState();
        });
    });

    window.resumeBuilder = {
        updateEducation(index, field, value) { state.education[index][field] = value; normalizeState(); },
        removeEducation(index) { state.education.splice(index, 1); renderEducationItems(); normalizeState(); },
        moveEducation(index, delta) { moveItem(state.education, index, delta); renderEducationItems(); normalizeState(); },
        updateExperience(index, field, value) { state.experience[index][field] = value; normalizeState(); },
        removeExperience(index) { state.experience.splice(index, 1); renderExperienceItems(); normalizeState(); },
        moveExperience(index, delta) { moveItem(state.experience, index, delta); renderExperienceItems(); normalizeState(); },
        updateSkill(index, value) { state.skills[index] = value; normalizeState(); },
        removeSkill(index) { state.skills.splice(index, 1); renderSkillItems(); normalizeState(); },
        moveSkill(index, delta) { moveItem(state.skills, index, delta); renderSkillItems(); normalizeState(); },
        updateProject(index, field, value) { state.projects[index][field] = value; normalizeState(); },
        removeProject(index) { state.projects.splice(index, 1); renderProjectItems(); normalizeState(); },
        moveProject(index, delta) { moveItem(state.projects, index, delta); renderProjectItems(); normalizeState(); },
        updateCertification(index, field, value) { state.certifications[index][field] = value; normalizeState(); },
        removeCertification(index) { state.certifications.splice(index, 1); renderCertificationItems(); normalizeState(); },
        moveCertification(index, delta) { moveItem(state.certifications, index, delta); renderCertificationItems(); normalizeState(); },
        updateLanguage(index, field, value) { state.languages[index][field] = value; normalizeState(); },
        removeLanguage(index) { state.languages.splice(index, 1); renderLanguageItems(); normalizeState(); },
        moveLanguage(index, delta) { moveItem(state.languages, index, delta); renderLanguageItems(); normalizeState(); },
        updateReference(index, field, value) { state.references[index][field] = value; normalizeState(); },
        removeReference(index) { state.references.splice(index, 1); renderReferenceItems(); normalizeState(); },
        moveReference(index, delta) { moveItem(state.references, index, delta); renderReferenceItems(); normalizeState(); },
    };
</script>
@endsection
