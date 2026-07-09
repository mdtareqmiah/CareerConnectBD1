# Milestone 3A.7 — Resume CRUD

## Files Created
- app/Http/Controllers/ResumeController.php
- app/Http/Requests/StoreResumeRequest.php
- app/Http/Requests/UpdateResumeRequest.php
- resources/views/job-seeker/resumes/index.blade.php
- resources/views/job-seeker/resumes/create.blade.php
- resources/views/job-seeker/resumes/edit.blade.php
- resources/views/job-seeker/resumes/partials/form.blade.php
- tests/Feature/ResumeCrudTest.php

## Files Modified
- app/Models/Resume.php
- app/Http/Controllers/JobSeekerDashboardController.php
- app/Services/ProfileCompletionService.php
- resources/views/job-seeker/dashboard.blade.php
- resources/views/layouts/navigation.blade.php
- routes/web.php
- database/migrations/2026_07_09_114000_create_resumes_table.php

## Storage Changes
- Resume uploads are stored in storage/app/public/resumes.
- A public storage symlink is available at public/storage.
- Replacing a file deletes the previous physical file from storage.
- Deleting a resume removes the stored file.

## Browser Verification
- Verified the new resume list, create, edit, delete, and download flows through the Laravel routes and Blade views.
- Verified the dashboard quick action and resume count/default summary.

## Tests
- Added feature tests for upload, validation, edit/defaulting, delete, download, authorization, dashboard count, and completion updates.

## Verification Results
- php artisan storage:link
- php artisan optimize:clear
- composer dump-autoload
- php artisan migrate
- php artisan test
- npm run build
