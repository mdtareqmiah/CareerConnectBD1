<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class DemoJobSeekerSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = JobSeekerProfile::all();

        $skillSets = [
            ['Laravel', 'PHP', 'Bootstrap', 'MySQL', 'Git', 'GitHub', 'REST API', 'JavaScript'],
            ['Laravel', 'PHP', 'HTML', 'CSS', 'JavaScript', 'Bootstrap', 'Git', 'REST API'],
            ['PHP', 'Laravel', 'MySQL', 'REST API', 'Git', 'GitHub', 'Problem Solving', 'AJAX'],
            ['PHP', 'JavaScript', 'HTML', 'CSS', 'Bootstrap', 'MySQL', 'Git', 'Problem Solving'],
            ['Laravel', 'PHP', 'JavaScript', 'MySQL', 'REST API', 'Git', 'GitHub', 'Problem Solving'],
            ['PHP', 'Bootstrap', 'HTML', 'CSS', 'JavaScript', 'Git', 'MySQL', 'AJAX'],
        ];

        foreach ($profiles as $index => $profile) {
            $skillNames = $skillSets[$index % count($skillSets)] ?? $skillSets[0];

            foreach ($skillNames as $skillName) {
                $profile->skills()->updateOrCreate(
                    [
                        'job_seeker_profile_id' => $profile->id,
                        'skill_name' => $skillName,
                    ],
                    [
                        'proficiency_level' => 'Intermediate',
                        'years_of_experience' => 2,
                        'name' => $skillName,
                        'slug' => str($skillName)->slug()->toString(),
                        'category' => 'Demo',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
