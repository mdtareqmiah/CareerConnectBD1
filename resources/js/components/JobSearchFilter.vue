<template>
    <aside class="filter-panel" aria-label="Job filters">
        <div class="filter-panel-header">
            <div class="d-flex align-items-center gap-2">
                <span class="filter-panel-icon">
                    <SlidersHorizontal :size="18" />
                </span>
                <h2 class="filter-panel-title">Filters</h2>
            </div>
            <button
                v-if="hasActiveFilters"
                type="button"
                class="filter-reset-link"
                @click="resetAll"
            >
                <RotateCcw :size="14" />
                <span>Reset</span>
            </button>
        </div>

        <div class="filter-panel-body">
            <!-- Keyword -->
            <section class="filter-group" :class="{ open: openGroups.keyword }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.keyword"
                    @click="toggle('keyword')"
                >
                    <Search :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Keyword</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.keyword" class="filter-group-content">
                        <div class="filter-input-wrap">
                            <Search :size="15" class="filter-input-icon" />
                            <input
                                v-model="local.keyword"
                                type="text"
                                class="filter-control"
                                placeholder="Job title, company, skill..."
                                @input="emitChange"
                            >
                        </div>
                    </div>
                </transition>
            </section>

            <!-- Category / Job Type -->
            <section class="filter-group" :class="{ open: openGroups.type }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.type"
                    @click="toggle('type')"
                >
                    <BriefcaseBusiness :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Job Type</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.type" class="filter-group-content">
                        <label
                            v-for="type in jobTypes"
                            :key="type"
                            class="filter-check"
                        >
                            <input
                                type="checkbox"
                                :value="type"
                                :checked="local.types.includes(type)"
                                @change="toggleValue('types', type)"
                            >
                            <span class="filter-check-box"><Check :size="12" /></span>
                            <span class="filter-check-label">{{ type }}</span>
                        </label>
                    </div>
                </transition>
            </section>

            <!-- Location -->
            <section class="filter-group" :class="{ open: openGroups.location }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.location"
                    @click="toggle('location')"
                >
                    <MapPin :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Location</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.location" class="filter-group-content">
                        <div class="filter-input-wrap">
                            <MapPin :size="15" class="filter-input-icon" />
                            <input
                                v-model="local.location"
                                type="text"
                                class="filter-control"
                                placeholder="City or region..."
                                @input="emitChange"
                            >
                        </div>
                    </div>
                </transition>
            </section>

            <!-- Workplace -->
            <section class="filter-group" :class="{ open: openGroups.workplace }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.workplace"
                    @click="toggle('workplace')"
                >
                    <Globe :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Workplace</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.workplace" class="filter-group-content">
                        <label
                            v-for="opt in workplaceOptions"
                            :key="opt.value"
                            class="filter-check"
                        >
                            <input
                                type="checkbox"
                                :value="opt.value"
                                :checked="local.workplaces.includes(opt.value)"
                                @change="toggleValue('workplaces', opt.value)"
                            >
                            <span class="filter-check-box"><Check :size="12" /></span>
                            <span class="filter-check-label">{{ opt.label }}</span>
                        </label>
                    </div>
                </transition>
            </section>

            <!-- Experience -->
            <section class="filter-group" :class="{ open: openGroups.experience }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.experience"
                    @click="toggle('experience')"
                >
                    <GraduationCap :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Experience</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.experience" class="filter-group-content">
                        <label
                            v-for="opt in experienceOptions"
                            :key="opt.value"
                            class="filter-check"
                        >
                            <input
                                type="checkbox"
                                :value="opt.value"
                                :checked="local.experience.includes(opt.value)"
                                @change="toggleValue('experience', opt.value)"
                            >
                            <span class="filter-check-box"><Check :size="12" /></span>
                            <span class="filter-check-label">{{ opt.label }}</span>
                        </label>
                    </div>
                </transition>
            </section>

            <!-- Employment Status -->
            <section class="filter-group" :class="{ open: openGroups.status }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.status"
                    @click="toggle('status')"
                >
                    <CalendarClock :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Employment</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.status" class="filter-group-content">
                        <label
                            v-for="opt in statusOptions"
                            :key="opt.value"
                            class="filter-check"
                        >
                            <input
                                type="checkbox"
                                :value="opt.value"
                                :checked="local.statuses.includes(opt.value)"
                                @change="toggleValue('statuses', opt.value)"
                            >
                            <span class="filter-check-box"><Check :size="12" /></span>
                            <span class="filter-check-label">{{ opt.label }}</span>
                        </label>
                    </div>
                </transition>
            </section>

            <!-- Salary Range -->
            <section class="filter-group" :class="{ open: openGroups.salary }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.salary"
                    @click="toggle('salary')"
                >
                    <DollarSign :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Salary Range</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.salary" class="filter-group-content">
                        <div class="filter-range-values">
                            <span>{{ formatMoney(local.salaryMin) }}</span>
                            <span>{{ formatMoney(local.salaryMax) }}+</span>
                        </div>
                        <div class="filter-range">
                            <input
                                type="range"
                                min="0"
                                max="200000"
                                step="5000"
                                :value="local.salaryMin"
                                class="filter-range-input"
                                aria-label="Minimum salary"
                                @input="onMinRange($event)"
                            >
                            <input
                                type="range"
                                min="0"
                                max="200000"
                                step="5000"
                                :value="local.salaryMax"
                                class="filter-range-input"
                                aria-label="Maximum salary"
                                @input="onMaxRange($event)"
                            >
                        </div>
                        <div class="filter-range-labels">
                            <span>0</span>
                            <span>200k+</span>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <div class="filter-input-wrap">
                                    <span class="filter-input-prefix">৳</span>
                                    <input
                                        type="number"
                                        min="0"
                                        :value="local.salaryMin"
                                        class="filter-control"
                                        placeholder="Min"
                                        @input="onMinInput($event)"
                                    >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="filter-input-wrap">
                                    <span class="filter-input-prefix">৳</span>
                                    <input
                                        type="number"
                                        min="0"
                                        :value="local.salaryMax"
                                        class="filter-control"
                                        placeholder="Max"
                                        @input="onMaxInput($event)"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </section>

            <!-- Posted Date -->
            <section class="filter-group" :class="{ open: openGroups.posted }">
                <button
                    type="button"
                    class="filter-group-toggle"
                    :aria-expanded="openGroups.posted"
                    @click="toggle('posted')"
                >
                    <Clock :size="16" class="filter-group-icon" />
                    <span class="filter-group-label">Posted</span>
                    <ChevronDown :size="16" class="filter-group-chevron" />
                </button>
                <transition name="filter-slide">
                    <div v-show="openGroups.posted" class="filter-group-content">
                        <label
                            v-for="opt in postedOptions"
                            :key="opt.value"
                            class="filter-check"
                        >
                            <input
                                type="radio"
                                name="posted"
                                :value="opt.value"
                                :checked="local.posted === opt.value"
                                @change="local.posted = opt.value; emitChange()"
                            >
                            <span class="filter-check-box filter-check-radio"><Check :size="12" /></span>
                            <span class="filter-check-label">{{ opt.label }}</span>
                        </label>
                    </div>
                </transition>
            </section>
        </div>

        <div class="filter-panel-footer">
            <button type="button" class="btn-filter-apply" @click="emitChange">
                <Filter :size="16" />
                <span>Apply Filters</span>
            </button>
        </div>
    </aside>
</template>

<script>
import {
    Search,
    MapPin,
    BriefcaseBusiness,
    Globe,
    GraduationCap,
    CalendarClock,
    DollarSign,
    Clock,
    ChevronDown,
    Check,
    SlidersHorizontal,
    RotateCcw,
    Filter,
} from '@lucide/vue';

export default {
    name: 'JobSearchFilter',
    components: {
        Search,
        MapPin,
        BriefcaseBusiness,
        Globe,
        GraduationCap,
        CalendarClock,
        DollarSign,
        Clock,
        ChevronDown,
        Check,
        SlidersHorizontal,
        RotateCcw,
        Filter,
    },
    props: {
        jobTypes: {
            type: Array,
            default: () => [],
        },
        value: {
            type: Object,
            default: () => ({}),
        },
    },
    emits: ['filter', 'reset'],
    data() {
        return {
            openGroups: {
                keyword: true,
                type: true,
                location: true,
                workplace: true,
                experience: true,
                status: true,
                salary: true,
                posted: false,
            },
            local: {
                keyword: '',
                location: '',
                types: [],
                workplaces: [],
                experience: [],
                statuses: [],
                salaryMin: 0,
                salaryMax: 200000,
                posted: '',
            },
            workplaceOptions: [
                { value: 'remote', label: 'Remote' },
                { value: 'on-site', label: 'On-site' },
                { value: 'hybrid', label: 'Hybrid' },
            ],
            experienceOptions: [
                { value: 'entry', label: 'Entry Level' },
                { value: 'mid', label: 'Mid Level' },
                { value: 'senior', label: 'Senior Level' },
            ],
            statusOptions: [
                { value: 'full-time', label: 'Full Time' },
                { value: 'part-time', label: 'Part Time' },
                { value: 'contract', label: 'Contract' },
                { value: 'intern', label: 'Internship' },
                { value: 'freelance', label: 'Freelance' },
            ],
            postedOptions: [
                { value: '1', label: 'Last 24 hours' },
                { value: '7', label: 'Last 7 days' },
                { value: '30', label: 'Last 30 days' },
            ],
        };
    },
    computed: {
        hasActiveFilters() {
            const l = this.local;
            return (
                l.keyword !== '' ||
                l.location !== '' ||
                l.types.length > 0 ||
                l.workplaces.length > 0 ||
                l.experience.length > 0 ||
                l.statuses.length > 0 ||
                l.posted !== '' ||
                l.salaryMin > 0 ||
                l.salaryMax < 200000
            );
        },
    },
    watch: {
        value: {
            immediate: true,
            deep: true,
            handler(next) {
                if (!next) return;
                this.local = {
                    keyword: next.keyword ?? '',
                    location: next.location ?? '',
                    types: next.types ? [...next.types] : [],
                    workplaces: next.workplaces ? [...next.workplaces] : [],
                    experience: next.experience ? [...next.experience] : [],
                    statuses: next.statuses ? [...next.statuses] : [],
                    salaryMin: next.salaryMin ?? 0,
                    salaryMax: next.salaryMax ?? 200000,
                    posted: next.posted ?? '',
                };
            },
        },
    },
    methods: {
        toggle(name) {
            this.openGroups[name] = !this.openGroups[name];
        },
        toggleValue(key, val) {
            const arr = this.local[key];
            const idx = arr.indexOf(val);
            if (idx === -1) arr.push(val);
            else arr.splice(idx, 1);
            this.emitChange();
        },
        onMinRange(e) {
            const v = Number(e.target.value);
            this.local.salaryMin = Math.min(v, this.local.salaryMax);
            this.emitChange();
        },
        onMaxRange(e) {
            const v = Number(e.target.value);
            this.local.salaryMax = Math.max(v, this.local.salaryMin);
            this.emitChange();
        },
        onMinInput(e) {
            const v = Number(e.target.value) || 0;
            this.local.salaryMin = Math.min(v, this.local.salaryMax);
            this.emitChange();
        },
        onMaxInput(e) {
            const v = Number(e.target.value) || 200000;
            this.local.salaryMax = Math.max(v, this.local.salaryMin);
            this.emitChange();
        },
        formatMoney(v) {
            if (!v) return '0';
            if (v >= 1000) return `${Math.round(v / 1000)}k`;
            return `${v}`;
        },
        emitChange() {
            this.$emit('filter', { ...this.local });
        },
        resetAll() {
            this.local = {
                keyword: '',
                location: '',
                types: [],
                workplaces: [],
                experience: [],
                statuses: [],
                salaryMin: 0,
                salaryMax: 200000,
                posted: '',
            };
            this.$emit('reset');
            this.$emit('filter', { ...this.local });
        },
    },
};
</script>

<style scoped>
.filter-panel {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 1.5rem;
    box-shadow: 0 10px 35px rgba(15, 23, 42, 0.07);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.filter-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

.filter-panel-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
}

.filter-panel-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.filter-reset-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.7rem;
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 0.6rem;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

.filter-reset-link:hover {
    background: rgba(245, 158, 11, 0.18);
}

.filter-panel-body {
    padding: 0.5rem 0;
    flex: 1;
    overflow-y: auto;
    max-height: calc(100vh - 220px);
}

.filter-group {
    border-bottom: 1px solid rgba(15, 23, 42, 0.05);
}

.filter-group:last-child {
    border-bottom: none;
}

.filter-group-toggle {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    width: 100%;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
}

.filter-group-toggle:hover {
    background: rgba(37, 99, 235, 0.04);
}

.filter-group-icon {
    color: #2563eb;
    flex-shrink: 0;
}

.filter-group-label {
    font-size: 0.92rem;
    font-weight: 600;
    color: #334155;
    flex: 1;
    text-align: left;
}

.filter-group-chevron {
    color: #94a3b8;
    transition: transform 0.3s ease;
}

.filter-group.open .filter-group-chevron {
    transform: rotate(180deg);
}

.filter-group-content {
    padding: 0 1.5rem 1.25rem;
}

.filter-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.filter-input-icon {
    position: absolute;
    left: 0.9rem;
    color: #94a3b8;
    pointer-events: none;
}

.filter-input-prefix {
    position: absolute;
    left: 0.9rem;
    color: #94a3b8;
    font-weight: 600;
    pointer-events: none;
}

.filter-control {
    width: 100%;
    padding: 0.6rem 0.9rem 0.6rem 2.5rem;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 0.75rem;
    font-size: 0.88rem;
    color: #334155;
    background: #fff;
    transition: all 0.25s ease;
}

.filter-input-prefix ~ .filter-control {
    padding-left: 2rem;
}

.filter-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.filter-check {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.45rem 0;
    cursor: pointer;
    font-size: 0.88rem;
    color: #475569;
}

.filter-check input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.filter-check-box {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 0.45rem;
    border: 2px solid #cbd5e1;
    background: #fff;
    color: #fff;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.filter-check-box :deep(svg) {
    opacity: 0;
    transition: opacity 0.15s ease;
}

.filter-check-radio {
    border-radius: 50%;
}

.filter-check input:checked + .filter-check-box {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border-color: #2563eb;
}

.filter-check input:checked + .filter-check-box :deep(svg) {
    opacity: 1;
}

.filter-check input:focus-visible + .filter-check-box {
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
}

.filter-check-label {
    user-select: none;
}

.filter-range-values {
    display: flex;
    justify-content: space-between;
    font-size: 0.82rem;
    font-weight: 600;
    color: #2563eb;
    margin-bottom: 0.5rem;
}

.filter-range {
    position: relative;
    height: 26px;
}

.filter-range-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 26px;
    margin: 0;
    background: none;
    pointer-events: none;
    -webkit-appearance: none;
    appearance: none;
}

.filter-range-input::-webkit-slider-runnable-track {
    height: 6px;
    border-radius: 999px;
    background: linear-gradient(90deg, #2563eb var(--fill, 0%), #e2e8f0 var(--fill, 0%));
}

.filter-range-input::-moz-range-track {
    height: 6px;
    border-radius: 999px;
    background: #e2e8f0;
}

.filter-range-input::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    pointer-events: all;
    width: 18px;
    height: 18px;
    margin-top: -6px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #2563eb;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
    cursor: pointer;
    transition: transform 0.15s ease;
}

.filter-range-input::-webkit-slider-thumb:hover {
    transform: scale(1.15);
}

.filter-range-input::-moz-range-thumb {
    pointer-events: all;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #2563eb;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
    cursor: pointer;
}

.filter-range-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.72rem;
    color: #94a3b8;
    margin-top: 0.35rem;
}

.filter-panel-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
}

.btn-filter-apply {
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.8rem 1.25rem;
    border: none;
    border-radius: 0.85rem;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    font-weight: 600;
    font-size: 0.92rem;
    cursor: pointer;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.3);
    transition: all 0.3s ease;
}

.btn-filter-apply:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 30px rgba(37, 99, 235, 0.4);
}

.filter-slide-enter-active,
.filter-slide-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.filter-slide-enter-from,
.filter-slide-leave-to {
    opacity: 0;
    max-height: 0;
    padding-top: 0;
    padding-bottom: 0;
}

.filter-slide-enter-to,
.filter-slide-leave-from {
    opacity: 1;
    max-height: 320px;
}

@media (max-width: 991.98px) {
    .filter-panel-body {
        max-height: none;
    }
}
</style>
