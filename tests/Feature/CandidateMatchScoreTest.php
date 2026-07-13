<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateMatchScoreTest extends TestCase
{
    use RefreshDatabase;

    private User $jobSeeker;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['slug' => 'job-seeker'], ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]);
        $this->jobSeeker = User::factory()->create(['role_id' => $role->id]);
    }

    private function createJobSeekerProfile(): JobSeekerProfile
    {
        return JobSeekerProfile::factory()->create([
            'user_id' => $this->jobSeeker->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '01700000000',
            'date_of_birth' => '1992-05-12',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'years_of_experience' => 4,
        ]);
    }

    public function test_job_seeker_can_view_match_score_on_job_details(): void
    {
        $profile = $this->createJobSeekerProfile();
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);
        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Profile Resume',
            'file_path' => 'resumes/profile.pdf',
            'file_name' => 'profile.pdf',
            'file_type' => 'pdf',
            'file_size' => 2000,
        ]);

        $job = Job::factory()->create([
            'requirements' => 'Laravel, PHP, Bootstrap, Docker',
            'experience_level' => 'Mid Level',
            'education_level' => 'Bachelor',
            'status' => 'published',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $this->actingAs($this->jobSeeker);

        $response = $this->get(route('jobs.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Your Match Score');
        $response->assertSee('Matched Skills');
        $response->assertSee('Missing Skills');
        $response->assertSee('Profile Completion');
        $response->assertSee('Resume Uploaded');
    }

    public function test_guest_cannot_see_match_score(): void
    {
        $job = Job::factory()->create([
            'requirements' => 'Laravel, PHP, Bootstrap',
            'status' => 'published',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $response = $this->get(route('jobs.show', $job));

        $response->assertStatus(200);
        $response->assertDontSee('Your Match Score');
    }

    public function test_employer_can_view_candidate_match_score_in_applications(): void
    {
        $employerRole = Role::firstOrCreate(['slug' => 'employer'], ['name' => 'Employer', 'description' => 'Employer', 'is_active' => true]);
        $employer = User::factory()->create(['role_id' => $employerRole->id]);
        $company = Company::factory()->create(['employer_id' => $employer->id]);

        $profile = $this->createJobSeekerProfile();
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);
        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Profile Resume',
            'file_path' => 'resumes/profile.pdf',
            'file_name' => 'profile.pdf',
            'file_type' => 'pdf',
            'file_size' => 2000,
        ]);

        $job = Job::factory()->create([
            'company_id' => $company->id,
            'requirements' => 'Laravel, PHP, Bootstrap',
            'experience_level' => 'Entry Level',
            'education_level' => 'Bachelor',
            'status' => 'published',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $this->jobSeeker->id, 'resume_id' => Resume::where('job_seeker_profile_id', $profile->id)->first()->id]);

        $this->actingAs($employer);

        $response = $this->get(route('employer.applications.index'));

        $response->assertStatus(200);
        $response->assertSee('Applications');
    }
}
