<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Experience;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerProfileRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_has_related_education_experience_skills_and_resume_records(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => 'Experienced backend developer.',
        ]);

        $education = Education::create([
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'BUET',
            'passing_year' => 2020,
        ]);

        $experience = Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'job_title' => 'Backend Developer',
            'company_name' => 'Tech Ltd',
            'employment_type' => 'Full-time',
            'start_date' => '2020-02-01',
            'currently_working' => true,
            'job_description' => 'Built APIs.',
        ]);

        $skill = Skill::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'category' => 'Backend',
            'is_active' => true,
        ]);

        $profile->skills()->attach($skill->id, [
            'proficiency_level' => 'Advanced',
            'years_of_experience' => 3,
        ]);

        $resume = Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Main Resume',
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
            'file_type' => 'pdf',
            'file_size' => 2048,
            'is_default' => true,
            'is_active' => true,
            'uploaded_at' => now(),
        ]);

        $this->assertTrue($profile->educations()->whereKey($education->id)->exists());
        $this->assertTrue($profile->experiences()->whereKey($experience->id)->exists());
        $this->assertTrue($profile->skills()->whereKey($skill->id)->exists());
        $this->assertTrue($profile->resumes()->whereKey($resume->id)->exists());
    }
}
