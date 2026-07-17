# Vue.js Components Guide

Your CareerConnectBD project now includes Vue.js with three ready-to-use components!

## Installation

Vue.js and @vitejs/plugin-vue have been installed. The configuration is already set up in `vite.config.js`.

## Setup

To use Vue components in your Blade templates:

```blade
<div id="app">
    <!-- Vue components go here -->
</div>

@vite('resources/js/app.js')
```

## Available Components

### 1. JobSearchFilter Component

A reusable component for filtering jobs by keyword, location, category, and experience level.

**Usage:**
```vue
<job-search-filter @filter="handleFilter"></job-search-filter>
```

**Props:**
- `onFilter` - Function callback when filters change

**Events:**
- `@filter` - Emitted when user applies filters

**Example:**
```vue
<job-search-filter @filter="(filters) => searchJobs(filters)"></job-search-filter>
```

---

### 2. JobCard Component

Displays individual job listings with company info, job details, skills, and action buttons.

**Usage:**
```vue
<job-card 
  :job="jobData" 
  @save="handleSave" 
  @apply="handleApply"
></job-card>
```

**Props:**
- `job` (Object, required) - Job data object with:
  - `id` - Job ID
  - `title` - Job title
  - `company_name` - Company name
  - `company_logo` - Company logo URL
  - `location` - Job location
  - `job_type` - Full-time, Part-time, etc.
  - `salary` - Salary information
  - `description` - Job description
  - `skills` - Array of required skills

**Events:**
- `@save` - Emitted when user clicks save button
- `@apply` - Emitted when user clicks apply button

**Example:**
```vue
<div v-for="job in jobs" :key="job.id">
  <job-card 
    :job="job" 
    @save="saveJob" 
    @apply="applyJob"
  ></job-card>
</div>
```

---

### 3. UserProfile Component

Displays user profile with avatar, bio, skills, experience, and education.

**Usage:**
```vue
<user-profile 
  :user-id="userId" 
  @edit="editUserProfile" 
  @view-resume="viewResume"
></user-profile>
```

**Props:**
- `userId` (Number, required) - The user ID

**Events:**
- `@edit` - Emitted when edit profile button is clicked
- `@view-resume` - Emitted when view resume button is clicked

**Example:**
```vue
<user-profile 
  :user-id="currentUser.id" 
  @edit="openEditModal" 
  @view-resume="downloadResume"
></user-profile>
```

---

## Integration with Blade Templates

### Example 1: Jobs Listing Page

Create a Blade view file: `resources/views/jobs/index.blade.php`

```blade
<div id="app" class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <job-search-filter @filter="handleFilter"></job-search-filter>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div v-for="job in jobs" :key="job.id" class="col-md-6 mb-3">
                    <job-card 
                        :job="job" 
                        @save="saveJob" 
                        @apply="applyJob"
                    ></job-card>
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/app.js')
```

### Example 2: User Profile Page

Create a Blade view file: `resources/views/profile/show.blade.php`

```blade
<div id="app" class="container py-5">
    <user-profile 
        :user-id="{{ $user->id }}" 
        @edit="editProfile" 
        @view-resume="viewResume"
    ></user-profile>
</div>

@vite('resources/js/app.js')
```

---

## Development Workflow

1. **Run development server:**
   ```bash
   npm run dev
   ```

2. **Build for production:**
   ```bash
   npm run build
   ```

3. **Start Laravel server:**
   ```bash
   php artisan serve
   ```

---

## Component File Structure

```
resources/js/
├── app.js                    # Main Vue app entry point
├── bootstrap.js              # Laravel bootstrap
└── components/
    ├── JobSearchFilter.vue   # Job filter component
    ├── JobCard.vue           # Job card component
    └── UserProfile.vue       # User profile component
```

---

## Creating New Components

To create a new Vue component:

1. Create a file in `resources/js/components/MyComponent.vue`

2. Add to `resources/js/app.js`:
   ```javascript
   import MyComponent from './components/MyComponent.vue';
   app.component('MyComponent', MyComponent);
   ```

3. Use in your Blade template:
   ```blade
   <my-component></my-component>
   ```

---

## Tips & Best Practices

✅ **Do:**
- Use kebab-case for component names in templates (`<job-card></job-card>`)
- Use PascalCase in imports (`JobCard`)
- Keep components small and focused
- Pass data through props, emit events back up
- Use scoped styles to avoid CSS conflicts

❌ **Don't:**
- Directly modify props
- Use too many inline styles
- Create large monolithic components
- Mix Alpine.js and Vue.js for same elements

---

## Troubleshooting

**Components not rendering?**
- Make sure you have `<div id="app">` in your Blade template
- Check that @vite('resources/js/app.js') is included
- Run `npm run dev` for development

**Styles not working?**
- Use `scoped` attribute in component `<style>` tags
- Check Bootstrap and Tailwind CSS are imported

**Events not firing?**
- Verify you're using `@event-name` in the template
- Check camelCase event names are correct

---

For more information, visit the [Vue 3 Documentation](https://vuejs.org)
