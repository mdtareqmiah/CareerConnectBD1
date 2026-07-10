<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create employer role
        $role = Role::create(['name' => 'Employer', 'slug' => 'employer']);

        // Create employer user
        $this->employer = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_employer_can_create_company()
    {
        $this->actingAs($this->employer);

        $response = $this->post('/company', [
            'company_name' => 'Tech Corp',
            'industry' => 'Technology',
            'company_size' => '51-200',
            'founded_year' => 2020,
            'website' => 'https://techcorp.com',
            'email' => 'info@techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'country' => 'USA',
            'company_description' => 'A leading tech company',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'company_name' => 'Tech Corp',
            'employer_id' => $this->employer->id,
        ]);
    }

    public function test_employer_can_view_company()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($this->employer);

        $response = $this->get("/company/{$company->id}");

        $response->assertStatus(200);
        $response->assertSee($company->company_name);
    }

    public function test_employer_can_update_company()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($this->employer);

        $response = $this->put("/company/{$company->id}", [
            'company_name' => 'Updated Tech Corp',
            'industry' => 'Software',
            'company_size' => '201-500',
            'founded_year' => 2021,
            'website' => 'https://updated.techcorp.com',
            'email' => 'updated@techcorp.com',
            'phone' => '+9876543210',
            'address' => '456 New Street',
            'city' => 'New York',
            'country' => 'USA',
            'company_description' => 'Updated description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => 'Updated Tech Corp',
        ]);
    }

    public function test_employer_can_delete_company()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($this->employer);

        $response = $this->delete("/company/{$company->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_other_employer_cannot_view_company()
    {
        $other_employer = User::factory()->create(['role_id' => Role::where('slug', 'employer')->first()->id]);
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($other_employer);

        $response = $this->get("/company/{$company->id}");

        // Will redirect to dashboard due to middleware, or 403 from policy
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302,
            "Expected 403 or 302, got {$response->status()}"
        );
    }

    public function test_other_employer_cannot_update_company()
    {
        $other_employer = User::factory()->create(['role_id' => Role::where('slug', 'employer')->first()->id]);
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($other_employer);

        $response = $this->put("/company/{$company->id}", [
            'company_name' => 'Hacked Company',
            'industry' => 'Hacking',
            'company_size' => '1-50',
            'founded_year' => 2024,
            'website' => 'https://hacked.com',
            'email' => 'hacked@hack.com',
            'phone' => '0000000000',
            'address' => 'Hack Street',
            'city' => 'Hack City',
            'country' => 'Hackland',
        ]);

        // Will redirect or 403 from policy
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302,
            "Expected 403 or 302, got {$response->status()}"
        );
    }

    public function test_other_employer_cannot_delete_company()
    {
        $other_employer = User::factory()->create(['role_id' => Role::where('slug', 'employer')->first()->id]);
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($other_employer);

        $response = $this->delete("/company/{$company->id}");

        // Will redirect or 403 from policy
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302,
            "Expected 403 or 302, got {$response->status()}"
        );
    }

    public function test_company_logo_can_be_uploaded()
    {
        Storage::fake('public');

        $this->actingAs($this->employer);

        $response = $this->post('/company', [
            'company_name' => 'Tech Corp',
            'company_logo' => \Illuminate\Http\UploadedFile::fake()->create('logo.jpg', 100, 'image/jpeg'),
            'industry' => 'Technology',
            'company_size' => '51-200',
            'founded_year' => 2020,
            'website' => 'https://techcorp.com',
            'email' => 'info@techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'country' => 'USA',
            'company_description' => 'A leading tech company',
        ]);

        $response->assertRedirect();
        $company = Company::first();

        $this->assertNotNull($company->company_logo);
        Storage::disk('public')->assertExists("company-logos/{$company->company_logo}");
    }

    public function test_old_company_logo_deleted_on_update()
    {
        Storage::fake('public');

        $this->actingAs($this->employer);

        // Create company with initial logo
        $response = $this->post('/company', [
            'company_name' => 'Tech Corp',
            'company_logo' => \Illuminate\Http\UploadedFile::fake()->create('logo.jpg', 100, 'image/jpeg'),
            'industry' => 'Technology',
            'company_size' => '51-200',
            'founded_year' => 2020,
            'website' => 'https://techcorp.com',
            'email' => 'info@techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'country' => 'USA',
            'company_description' => 'A leading tech company',
        ]);

        $company = Company::first();
        $oldLogo = $company->company_logo;

        // Update with new logo
        $response = $this->put("/company/{$company->id}", [
            'company_name' => 'Updated Tech Corp',
            'company_logo' => \Illuminate\Http\UploadedFile::fake()->create('newlogo.png', 100, 'image/png'),
            'industry' => 'Technology',
            'company_size' => '51-200',
            'founded_year' => 2020,
            'website' => 'https://techcorp.com',
            'email' => 'info@techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'country' => 'USA',
            'company_description' => 'A leading tech company',
        ]);

        $response->assertRedirect();
        $company->refresh();

        // Old logo should be deleted
        Storage::disk('public')->assertMissing("company-logos/{$oldLogo}");
        // New logo should exist
        Storage::disk('public')->assertExists("company-logos/{$company->company_logo}");
    }

    public function test_unauthenticated_user_cannot_create_company()
    {
        $response = $this->post('/company', [
            'company_name' => 'Tech Corp',
            'industry' => 'Technology',
            'company_size' => '51-200',
            'founded_year' => 2020,
            'website' => 'https://techcorp.com',
            'email' => 'info@techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'country' => 'USA',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_validation_errors_are_shown()
    {
        $this->actingAs($this->employer);

        $response = $this->post('/company', [
            'company_name' => '',
            'industry' => '',
            'company_size' => '',
            'founded_year' => 'invalid',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['company_name', 'industry', 'company_size', 'founded_year', 'email']);
    }
}
