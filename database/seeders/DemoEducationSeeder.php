<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\JobSeekerProfile;
use Illuminate\Database\Seeder;

class DemoEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = JobSeekerProfile::all();

        $educationSets = [
            [
                [
                    'degree' => 'SSC',
                    'field_of_study' => 'Science',
                    'institution_name' => 'Dhaka College',
                    'board_or_university' => 'Dhaka Board',
                    'education_level' => 'Secondary',
                    'result' => '5.00',
                    'grading_system' => 'GPA',
                    'passing_year' => 2015,
                    'is_current' => false,
                    'description' => 'Completed secondary school with strong performance in science and mathematics.',
                ],
                [
                    'degree' => 'BSc in CSE',
                    'field_of_study' => 'Computer Science and Engineering',
                    'institution_name' => 'Sylhet International University',
                    'board_or_university' => 'Sylhet International University',
                    'education_level' => 'Bachelor',
                    'result' => '3.60',
                    'grading_system' => 'CGPA',
                    'passing_year' => 2022,
                    'is_current' => false,
                    'description' => 'Studied software development, data structures, databases, and web engineering.',
                ],
            ],
            [
                [
                    'degree' => 'HSC',
                    'field_of_study' => 'Science',
                    'institution_name' => 'Govt. Science College',
                    'board_or_university' => 'Dhaka Board',
                    'education_level' => 'Higher Secondary',
                    'result' => '5.00',
                    'grading_system' => 'GPA',
                    'passing_year' => 2017,
                    'is_current' => false,
                    'description' => 'Completed higher secondary education with focus on mathematics and physics.',
                ],
                [
                    'degree' => 'BSc in CSE',
                    'field_of_study' => 'Computer Science and Engineering',
                    'institution_name' => 'Shahjalal University of Science and Technology',
                    'board_or_university' => 'Shahjalal University of Science and Technology',
                    'education_level' => 'Bachelor',
                    'result' => '3.45',
                    'grading_system' => 'CGPA',
                    'passing_year' => 2021,
                    'is_current' => false,
                    'description' => 'Built a strong foundation in algorithms, operating systems, and software design.',
                ],
            ],
            [
                [
                    'degree' => 'SSC',
                    'field_of_study' => 'Science',
                    'institution_name' => 'Cantonment Public School',
                    'board_or_university' => 'Chattogram Board',
                    'education_level' => 'Secondary',
                    'result' => '4.89',
                    'grading_system' => 'GPA',
                    'passing_year' => 2016,
                    'is_current' => false,
                    'description' => 'Completed secondary school with thorough preparation for higher studies.',
                ],
                [
                    'degree' => 'BSc in CSE',
                    'field_of_study' => 'Computer Science and Engineering',
                    'institution_name' => 'Leading University',
                    'board_or_university' => 'Leading University',
                    'education_level' => 'Bachelor',
                    'result' => '3.80',
                    'grading_system' => 'CGPA',
                    'passing_year' => 2023,
                    'is_current' => false,
                    'description' => 'Focused on web technologies, software engineering, and problem solving.',
                ],
            ],
        ];

        foreach ($profiles as $index => $profile) {
            $educationData = $educationSets[$index % count($educationSets)] ?? $educationSets[0];

            foreach ($educationData as $education) {
                Education::updateOrCreate(
                    [
                        'job_seeker_profile_id' => $profile->id,
                        'degree' => $education['degree'],
                        'institution_name' => $education['institution_name'],
                    ],
                    [
                        'field_of_study' => $education['field_of_study'],
                        'board_or_university' => $education['board_or_university'],
                        'education_level' => $education['education_level'],
                        'result' => $education['result'],
                        'grading_system' => $education['grading_system'],
                        'passing_year' => $education['passing_year'],
                        'is_current' => $education['is_current'],
                        'description' => $education['description'],
                    ]
                );
            }
        }
    }
}
