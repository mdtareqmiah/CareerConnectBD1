<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\ResumeBuilder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeBuilderCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeekerUserWithProfile(): array
    {
        $role = Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]
        );

        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
        ]);

        return [$user, $profile];
    }

    public function test_job_seeker_can_create_resume_builder(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/resume-builders', [
            'title' => 'Career Summary',
            'professional_summary' => 'Experienced software engineer.',
            'template' => 'modern',
            'status' => 'draft',
            'is_default' => '1',
        ]);

        $response->assertRedirect('/job-seeker/resume-builders');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('resume_builders', [
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Career Summary',
            'status' => 'draft',
            'is_default' => true,
        ]);
    }

    public function test_resume_builder_requires_valid_data(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/resume-builders', [
            'title' => '',
            'status' => 'invalid-status',
        ]);

        $response->assertSessionHasErrors(['title', 'status']);
    }

    public function test_job_seeker_can_create_resume_builder_via_json(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->postJson('/job-seeker/resume-builders', [
            'title' => 'JSON Draft',
            'professional_summary' => 'Draft from editor.',
            'template' => 'default',
            'status' => 'draft',
            'is_default' => false,
            'personal_information' => [
                'first_name' => 'Ayesha',
                'last_name' => 'Rahman',
                'email' => 'ayesha@example.com',
            ],
            'skills' => ['PHP', 'Laravel'],
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['id']);
        $this->assertDatabaseHas('resume_builders', [
            'job_seeker_profile_id' => $profile->id,
            'title' => 'JSON Draft',
            'status' => 'draft',
        ]);
    }

    public function test_job_seeker_can_create_resume_builder_from_form_with_json_hidden_fields(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/resume-builders', [
            'title' => 'Form Json Draft',
            'professional_summary' => 'Draft from editor form.',
            'template' => 'default',
            'status' => 'draft',
            'is_default' => '0',
            'personal_information' => json_encode([
                'first_name' => 'Ayesha',
                'last_name' => 'Rahman',
                'email' => 'ayesha@example.com',
            ]),
            'skills' => json_encode(['PHP', 'Laravel']),
        ]);

        $response->assertRedirect('/job-seeker/resume-builders');
        $this->assertDatabaseHas('resume_builders', [
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Form Json Draft',
            'status' => 'draft',
        ]);
    }

    public function test_job_seeker_can_update_resume_builder_and_set_default(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $builder = ResumeBuilder::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Old Builder',
            'professional_summary' => 'First draft.',
            'template' => 'classic',
            'status' => 'draft',
            'is_default' => false,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/resume-builders/'.$builder->id, [
            'title' => 'Updated Builder',
            'professional_summary' => 'Updated draft.',
            'template' => 'modern',
            'status' => 'published',
            'is_default' => '1',
        ]);

        $response->assertRedirect('/job-seeker/resume-builders');
        $this->assertDatabaseHas('resume_builders', [
            'id' => $builder->id,
            'title' => 'Updated Builder',
            'status' => 'published',
            'is_default' => true,
        ]);
    }

    public function test_job_seeker_can_delete_resume_builder(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $builder = ResumeBuilder::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Delete Builder',
            'professional_summary' => 'Remove this.',
            'template' => 'classic',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->delete('/job-seeker/resume-builders/'.$builder->id);

        $response->assertRedirect('/job-seeker/resume-builders');
        $this->assertDatabaseMissing('resume_builders', ['id' => $builder->id]);
    }

    public function test_edit_page_loads_saved_resume_builder_data(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $builder = ResumeBuilder::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Saved Builder',
            'professional_summary' => 'Saved summary text.',
            'template' => 'modern',
            'status' => 'draft',
            'personal_information' => [
                'first_name' => 'Ayesha',
                'last_name' => 'Rahman',
                'email' => 'ayesha@example.com',
                'theme' => 'modern',
                'spacing' => 'compact',
                'visibility_options' => ['show_email' => true],
            ],
            'education' => [['school' => 'University of Dhaka', 'degree' => 'BSc']],
            'experience' => [['company' => 'CareerConnect', 'title' => 'Developer']],
            'skills' => ['PHP', 'Laravel'],
            'projects' => [['name' => 'Portfolio Site', 'link' => 'https://example.com']],
            'social_links' => ['linkedin' => 'https://linkedin.com/in/ayesha'],
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/resume-builders/'.$builder->id.'/edit');

        $response->assertOk();
        $response->assertSee('Saved Builder');
        $response->assertSee('Saved summary text.');
        $response->assertSee('Portfolio Site');
        $response->assertSee('visibility_options');
        $response->assertSee('compact');
    }

    public function test_job_seeker_can_update_resume_builder_with_visibility_and_spacing_options(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $builder = ResumeBuilder::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Old Options',
            'professional_summary' => 'Old summary.',
            'template' => 'modern',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/resume-builders/'.$builder->id, [
            'title' => 'Updated Options',
            'professional_summary' => 'Updated summary.',
            'template' => 'professional',
            'status' => 'published',
            'is_default' => '0',
            'personal_information' => json_encode([
                'first_name' => 'Ayesha',
                'last_name' => 'Rahman',
                'email' => 'ayesha@example.com',
                'theme' => 'professional',
                'spacing' => 'spacious',
                'visibility_options' => ['show_email' => true, 'show_phone' => false],
            ]),
            'education' => json_encode([['school' => 'University of Dhaka', 'degree' => 'BSc']]),
            'experience' => json_encode([['company' => 'CareerConnect', 'title' => 'Engineer']]),
            'skills' => json_encode(['PHP', 'Laravel']),
            'projects' => json_encode([['name' => 'Portfolio Project', 'link' => 'https://example.com']]),
            'social_links' => json_encode(['linkedin' => 'https://linkedin.com/in/ayesha']),
        ]);

        $response->assertRedirect('/job-seeker/resume-builders');

        $builder->refresh();
        $this->assertSame('Updated Options', $builder->title);
        $this->assertSame('professional', $builder->template);
        $this->assertSame('published', $builder->status);
        $this->assertSame('professional', $builder->personal_information['theme']);
        $this->assertSame('spacious', $builder->personal_information['spacing']);
        $this->assertSame(true, $builder->personal_information['visibility_options']['show_email']);
        $this->assertSame(false, $builder->personal_information['visibility_options']['show_phone']);
    }

    public function test_job_seeker_can_preview_download_and_print_resume_builder(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $builder = ResumeBuilder::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Export Builder',
            'professional_summary' => 'Export summary.',
            'template' => 'modern',
            'status' => 'draft',
            'personal_information' => ['first_name' => 'Ayesha', 'last_name' => 'Rahman'],
        ]);

        $previewResponse = $this->actingAs($user)->get('/job-seeker/resume-builders/'.$builder->id.'/preview?template=modern');
        $previewResponse->assertOk();
        $previewResponse->assertSee('Resume Preview');

        $downloadResponse = $this->actingAs($user)->get('/job-seeker/resume-builders/'.$builder->id.'/download?template=modern');
        $downloadResponse->assertOk();
        $downloadResponse->assertHeader('content-type', 'application/pdf');

        $printResponse = $this->actingAs($user)->get('/job-seeker/resume-builders/'.$builder->id.'/print?template=modern');
        $printResponse->assertOk();
        $printResponse->assertHeader('content-type', 'text/html; charset=UTF-8');
        $printResponse->assertSee('Export summary.');
    }

    public function test_non_job_seeker_cannot_access_resume_builder_routes(): void
    {
        $role = Role::firstOrCreate(
            ['slug' => 'employer'],
            ['name' => 'Employer', 'description' => 'Employer', 'is_active' => true]
        );
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/job-seeker/resume-builders');

        $response->assertStatus(403);
    }
}
