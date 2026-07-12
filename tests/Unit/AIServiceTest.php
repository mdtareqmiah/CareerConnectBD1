<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Services\AI\AIManager;
use App\Services\AI\AIServiceInterface;
use App\Services\AI\GeminiService;
use App\Services\AI\OpenAIService;
use App\Services\AI\RuleBasedAIService;
use App\Services\CandidateMatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_manager_returns_rule_based_ai_service(): void
    {
        config(['ai.default' => 'rule']);

        $manager = $this->app->make(AIManager::class);

        $this->assertInstanceOf(RuleBasedAIService::class, $manager->getService());
    }

    public function test_ai_manager_returns_openai_service(): void
    {
        config(['ai.default' => 'openai']);

        $manager = $this->app->make(AIManager::class);

        $this->assertInstanceOf(OpenAIService::class, $manager->getService());
    }

    public function test_ai_manager_returns_gemini_service(): void
    {
        config(['ai.default' => 'gemini']);

        $manager = $this->app->make(AIManager::class);

        $this->assertInstanceOf(GeminiService::class, $manager->getService());
    }

    public function test_rule_based_ai_service_delegates_candidate_match_service(): void
    {
        $service = $this->app->make(RuleBasedAIService::class);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(10)->format('Y-m-d')]);
        $profile = JobSeekerProfile::factory()->create();

        $result = $service->calculateCandidateMatch($job, $profile);

        $this->assertIsInt($result);
    }

    public function test_openai_placeholder_throws_logic_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('OpenAI provider is not configured.');

        $service = $this->app->make(OpenAIService::class);
        $service->calculateCandidateMatch(Job::factory()->create(), JobSeekerProfile::factory()->create());
    }

    public function test_gemini_placeholder_throws_logic_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Gemini provider is not configured.');

        $service = $this->app->make(GeminiService::class);
        $service->calculateCandidateMatch(Job::factory()->create(), JobSeekerProfile::factory()->create());
    }
}
