<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">Job Seeker Dashboard</h1>
            <p class="text-muted">Welcome, {{ $user->name ?? $user->email }}</p>
        </div>
    </div>

    @if ($profile)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">Profile Overview</h2>
                            <span class="badge bg-success">{{ $completionDetails['percentage'] }}% Complete</span>
                        </div>

                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $completionDetails['percentage'] }}%"></div>
                        </div>

                        <p class="mb-0 text-muted">Completed sections: {{ implode(', ', $completionDetails['completed_sections']) ?: 'None' }}</p>
                        <p class="mb-0 text-muted">Missing sections: {{ implode(', ', $completionDetails['missing_sections']) ?: 'None' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h6">Profile Summary</h2>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Name:</strong> {{ $profile->first_name }} {{ $profile->last_name }}</li>
                            <li><strong>Status:</strong> {{ $profileStatus }}</li>
                            <li><strong>Availability:</strong> {{ $availabilityStatus }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h6">Education</h3>
                        <p class="display-6 mb-0">{{ $educationCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h6">Experience</h3>
                        <p class="display-6 mb-0">{{ $experienceCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h6">Skills</h3>
                        <p class="display-6 mb-0">{{ $skillsCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h6">Resumes</h3>
                        <p class="display-6 mb-0">{{ $resumeCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            You do not have a job seeker profile yet. Please complete your profile setup to access the dashboard.
        </div>
    @endif
</div>
</body>
</html>
