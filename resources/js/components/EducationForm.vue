<template>
    <div class="hf-page">
        <section class="hf-header">
            <div class="hf-header-inner">
                <div class="hf-header-content">
                    <nav aria-label="breadcrumb" class="hf-breadcrumb">
                        <ol class="hf-breadcrumb-list">
                            <li class="hf-breadcrumb-item">
                                <a :href="routes.dashboard"><Home :size="13" /> Dashboard</a>
                            </li>
                            <li class="hf-breadcrumb-item">
                                <a :href="routes.educations"><GraduationCap :size="13" /> Education</a>
                            </li>
                            <li class="hf-breadcrumb-item hf-breadcrumb-item--active" aria-current="page">
                                {{ isEdit ? 'Edit' : 'Add Education' }}
                            </li>
                        </ol>
                    </nav>
                    <span class="hf-pill"><GraduationCap :size="13" /> {{ isEdit ? 'Education update' : 'Education entry' }}</span>
                    <h1 class="hf-title">{{ isEdit ? 'Edit education' : 'Add education' }}</h1>
                    <p class="hf-sub">{{ isEdit ? 'Update your academic records.' : 'Add your academic qualifications to strengthen your professional profile.' }}</p>
                </div>
                <div class="hf-header-ico">
                    <img src="/images/education.png" alt="Education illustration" class="hf-header-img">
                </div>
            </div>
        </section>

        <div class="hf-card">
            <div class="hf-card-body">
                <div v-if="hasErrors" class="hf-alert hf-alert--danger" role="alert">
                    <div class="hf-alert-head"><AlertCircle :size="16" /> Please fix the following errors</div>
                    <ul class="hf-alert-list">
                        <li v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</li>
                    </ul>
                </div>

                <form :action="actionUrl" :method="method" class="hf-form">
                    <input v-if="method !== 'GET'" type="hidden" name="_token" :value="csrfToken">
                    <input v-if="isEdit" type="hidden" name="_method" value="PATCH">

                    <div class="hf-grid">
                        <div class="hf-field" :class="{ 'hf-field--error': errors.degree }">
                            <label class="hf-label" for="degree"><GraduationCap :size="13" /> Degree <span class="hf-req">*</span></label>
                            <input id="degree" name="degree" type="text" class="hf-input" :value="fields.degree" required placeholder="e.g. Bachelor of Science">
                            <div v-if="errors.degree" class="hf-error">{{ errors.degree[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.field_of_study }">
                            <label class="hf-label" for="field_of_study"><BookOpen :size="13" /> Field of Study <span class="hf-req">*</span></label>
                            <input id="field_of_study" name="field_of_study" type="text" class="hf-input" :value="fields.field_of_study" required placeholder="e.g. Computer Science">
                            <div v-if="errors.field_of_study" class="hf-error">{{ errors.field_of_study[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.institution_name }">
                            <label class="hf-label" for="institution_name"><School :size="13" /> Institution <span class="hf-req">*</span></label>
                            <input id="institution_name" name="institution_name" type="text" class="hf-input" :value="fields.institution_name" required placeholder="e.g. University of Dhaka">
                            <div v-if="errors.institution_name" class="hf-error">{{ errors.institution_name[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.board_or_university }">
                            <label class="hf-label" for="board_or_university"><Award :size="13" /> Board or University</label>
                            <input id="board_or_university" name="board_or_university" type="text" class="hf-input" :value="fields.board_or_university" placeholder="e.g. Dhaka Education Board">
                            <div v-if="errors.board_or_university" class="hf-error">{{ errors.board_or_university[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.education_level }">
                            <label class="hf-label" for="education_level"><CircleCheck :size="13" /> Education Level</label>
                            <input id="education_level" name="education_level" type="text" class="hf-input" :value="fields.education_level" placeholder="e.g. Undergraduate, Masters">
                            <div v-if="errors.education_level" class="hf-error">{{ errors.education_level[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.result }">
                            <label class="hf-label" for="result"><Star :size="13" /> Result / CGPA</label>
                            <input id="result" name="result" type="text" class="hf-input" :value="fields.result" placeholder="e.g. 3.8 or First Class">
                            <div v-if="errors.result" class="hf-error">{{ errors.result[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.grading_system }">
                            <label class="hf-label" for="grading_system"><Hash :size="13" /> Grading System</label>
                            <input id="grading_system" name="grading_system" type="text" class="hf-input" :value="fields.grading_system" placeholder="e.g. CGPA, Percentage">
                            <div v-if="errors.grading_system" class="hf-error">{{ errors.grading_system[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.passing_year }">
                            <label class="hf-label" for="passing_year"><Calendar :size="13" /> Passing Year</label>
                            <input id="passing_year" name="passing_year" type="number" min="1900" max="2100" class="hf-input" :value="fields.passing_year" placeholder="e.g. 2024">
                            <div v-if="errors.passing_year" class="hf-error">{{ errors.passing_year[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.start_date }">
                            <label class="hf-label" for="start_date"><CalendarDays :size="13" /> Start Date</label>
                            <input id="start_date" name="start_date" type="date" class="hf-input" :value="fields.start_date">
                            <div v-if="errors.start_date" class="hf-error">{{ errors.start_date[0] }}</div>
                        </div>

                        <div class="hf-field" :class="{ 'hf-field--error': errors.end_date }">
                            <label class="hf-label" for="end_date"><CalendarDays :size="13" /> End Date</label>
                            <input id="end_date" name="end_date" type="date" class="hf-input" :value="fields.end_date">
                            <div v-if="errors.end_date" class="hf-error">{{ errors.end_date[0] }}</div>
                        </div>

                        <div class="hf-field hf-field--full">
                            <label class="hf-check">
                                <input type="checkbox" name="is_current" value="1" :checked="fields.is_current">
                                <span class="hf-check-box"><CircleCheck :size="14" /></span>
                                <span>Currently Studying</span>
                            </label>
                        </div>

                        <div class="hf-field hf-field--full" :class="{ 'hf-field--error': errors.description }">
                            <label class="hf-label" for="description"><FileText :size="13" /> Description</label>
                            <textarea id="description" name="description" rows="4" class="hf-input hf-textarea" placeholder="Brief description about your education...">{{ fields.description }}</textarea>
                            <div v-if="errors.description" class="hf-error">{{ errors.description[0] }}</div>
                        </div>
                    </div>

                    <div class="hf-actions">
                        <a :href="cancelRoute" class="hf-btn hf-btn-ghost"><ArrowLeft :size="15" /> Cancel</a>
                        <button type="submit" class="hf-btn hf-btn-primary"><Save :size="15" /> {{ isEdit ? 'Update Education' : 'Save Education' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {
    GraduationCap, BookOpen, School, Award, CircleCheck, Star, Hash, Calendar, CalendarDays,
    FileText, Home, ArrowLeft, Save, AlertCircle,
} from '@lucide/vue';

export default {
    name: 'EducationForm',
    components: {
        GraduationCap, BookOpen, School, Award, CircleCheck, Star, Hash, Calendar, CalendarDays,
        FileText, Home, ArrowLeft, Save, AlertCircle,
    },
    props: {
        actionUrl: { type: String, default: '' },
        method: { type: String, default: 'POST' },
        isEdit: { type: Boolean, default: false },
        csrfToken: { type: String, default: '' },
        cancelRoute: { type: String, default: '#' },
        routes: { type: Object, default: () => ({}) },
        fields: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: {
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    },
};
</script>

<style scoped>
.hf-page {
    max-width: 1320px;
    margin: 0 auto;
    padding: clamp(1.25rem, 3vw, 2.25rem);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #1f2937;
    animation: hf-fade 0.5s ease both;
}
@keyframes hf-fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.hf-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    border: 1px solid rgba(37,99,235,0.25);
    border-radius: 20px;
    padding: clamp(1.5rem, 3vw, 2.25rem);
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5);
}
.hf-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}
.hf-breadcrumb {
    margin-bottom: 0.75rem;
}
.hf-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}
.hf-breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.2s;
}
.hf-breadcrumb-item a:hover { color: #fff; }
.hf-breadcrumb-item--active { color: rgba(255,255,255,0.5); }
.hf-breadcrumb-item + .hf-breadcrumb-item::before {
    content: '/';
    margin-right: 0.5rem;
    color: rgba(255,255,255,0.4);
}
.hf-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    background: rgba(59,130,246,0.2);
    border: 1px solid rgba(59,130,246,0.35);
    color: #dbeafe;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    margin-bottom: 0.75rem;
}
.hf-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.4rem;
}
.hf-sub {
    color: rgba(255,255,255,0.75);
    margin: 0;
    font-size: 0.92rem;
}
.hf-header-ico {
    flex-shrink: 0;
}
.hf-header-img {
    width: 320px;
    height: 320px;
    object-fit: contain;
    border-radius: 24px;
    filter: drop-shadow(0 20px 40px rgba(37,99,235,0.4));
}

.hf-card {
    background: #fff;
    border: 1px solid rgba(37,99,235,0.1);
    border-radius: 18px;
    box-shadow: 0 8px 28px -16px rgba(37,99,235,0.1);
    overflow: hidden;
}
.hf-card-body {
    padding: clamp(1.5rem, 3vw, 2.25rem);
}

.hf-alert {
    padding: 0.9rem 1.1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.hf-alert--danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.hf-alert-head {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    font-size: 0.88rem;
}
.hf-alert-list {
    margin: 0;
    padding-left: 1.25rem;
    font-size: 0.84rem;
}
.hf-alert-list li + li { margin-top: 0.2rem; }

.hf-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem 1.25rem;
}
.hf-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.hf-field--full { grid-column: 1 / -1; }
.hf-field--error .hf-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
}
.hf-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #44403c;
}
.hf-label svg { color: #2563eb; }
.hf-req { color: #ef4444; }
.hf-input {
    width: 100%;
    padding: 0.7rem 0.9rem;
    border: 1px solid #e7e5e4;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #1c1917;
    background: #fff;
    transition: all 0.2s ease;
    outline: none;
}
.hf-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    transform: translateY(-1px);
}
.hf-textarea {
    resize: vertical;
    min-height: 96px;
}
.hf-error {
    font-size: 0.76rem;
    color: #ef4444;
    font-weight: 500;
}

.hf-check {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    font-size: 0.86rem;
    font-weight: 500;
    color: #44403c;
    user-select: none;
}
.hf-check input { display: none; }
.hf-check-box {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid #d6d3d1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.2s;
    background: #fff;
}
.hf-check input:checked + .hf-check-box {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}

.hf-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #f5f5f4;
}
.hf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1.4rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    border: 0;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    white-space: nowrap;
}
.hf-btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 8px 20px rgba(37,99,235,0.25);
}
.hf-btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 12px 28px rgba(37,99,235,0.35);
}
.hf-btn-ghost {
    background: #fff;
    color: #57534e;
    border: 1px solid #e7e5e4;
}
.hf-btn-ghost:hover {
    background: #fafaf9;
    color: #1c1917;
    border-color: #d6d3d1;
    transform: translateY(-1px);
}

@media (max-width: 767.98px) {
    .hf-grid { grid-template-columns: 1fr; }
    .hf-header-inner { flex-direction: column; text-align: center; }
    .hf-actions { flex-direction: column; }
    .hf-btn { width: 100%; justify-content: center; }
}
</style>
