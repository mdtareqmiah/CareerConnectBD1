<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'name' => 'PHP',
                'skill_name' => 'PHP',
                'proficiency_level' => 'Beginner',
                'slug' => 'php',
                'category' => 'Backend',
                'is_active' => true,
            ],
            [
                'name' => 'Laravel',
                'skill_name' => 'Laravel',
                'proficiency_level' => 'Beginner',
                'slug' => 'laravel',
                'category' => 'Backend',
                'is_active' => true,
            ],
            [
                'name' => 'JavaScript',
                'skill_name' => 'JavaScript',
                'proficiency_level' => 'Beginner',
                'slug' => 'javascript',
                'category' => 'Frontend',
                'is_active' => true,
            ],
            [
                'name' => 'MySQL',
                'skill_name' => 'MySQL',
                'proficiency_level' => 'Beginner',
                'slug' => 'mysql',
                'category' => 'Database',
                'is_active' => true,
            ],
            [
                'name' => 'HTML',
                'skill_name' => 'HTML',
                'proficiency_level' => 'Beginner',
                'slug' => 'html',
                'category' => 'Frontend',
                'is_active' => true,
            ],
            [
                'name' => 'CSS',
                'skill_name' => 'CSS',
                'proficiency_level' => 'Beginner',
                'slug' => 'css',
                'category' => 'Frontend',
                'is_active' => true,
            ],
            [
                'name' => 'Bootstrap',
                'skill_name' => 'Bootstrap',
                'proficiency_level' => 'Beginner',
                'slug' => 'bootstrap',
                'category' => 'Frontend',
                'is_active' => true,
            ],
            [
                'name' => 'Git',
                'skill_name' => 'Git',
                'proficiency_level' => 'Beginner',
                'slug' => 'git',
                'category' => 'Tools',
                'is_active' => true,
            ],
            [
                'name' => 'GitHub',
                'skill_name' => 'GitHub',
                'proficiency_level' => 'Beginner',
                'slug' => 'github',
                'category' => 'Tools',
                'is_active' => true,
            ],
            [
                'name' => 'REST API',
                'skill_name' => 'REST API',
                'proficiency_level' => 'Beginner',
                'slug' => 'rest-api',
                'category' => 'Backend',
                'is_active' => true,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['slug' => $skill['slug']],
                $skill
            );
        }
    }
}