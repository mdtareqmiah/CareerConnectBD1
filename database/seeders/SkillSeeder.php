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
            ['name' => 'PHP', 'slug' => 'php', 'category' => 'Backend', 'is_active' => true],
            ['name' => 'Laravel', 'slug' => 'laravel', 'category' => 'Backend', 'is_active' => true],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'category' => 'Frontend', 'is_active' => true],
            ['name' => 'MySQL', 'slug' => 'mysql', 'category' => 'Database', 'is_active' => true],
            ['name' => 'HTML', 'slug' => 'html', 'category' => 'Frontend', 'is_active' => true],
            ['name' => 'CSS', 'slug' => 'css', 'category' => 'Frontend', 'is_active' => true],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'category' => 'Frontend', 'is_active' => true],
            ['name' => 'Git', 'slug' => 'git', 'category' => 'Tools', 'is_active' => true],
            ['name' => 'GitHub', 'slug' => 'github', 'category' => 'Tools', 'is_active' => true],
            ['name' => 'REST API', 'slug' => 'rest-api', 'category' => 'Backend', 'is_active' => true],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['slug' => $skill['slug']],
                $skill
            );
        }
    }
}
