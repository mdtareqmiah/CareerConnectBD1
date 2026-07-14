<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@careerconnectbd.com')->first();

        $companies = [
            [
                'employer_email' => 'hr@abctech.com',
                'company_name' => 'ABC Technologies Ltd.',
                'industry' => 'Software Development',
                'company_size' => '51-200',
                'founded_year' => 2012,
                'website' => 'https://abctech.demo',
                'email' => 'info@abctech.com',
                'phone' => '+8801711000001',
                'address' => 'House 12, Road 7, Banani',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'company_description' => 'A fast-growing software development company focused on web and mobile products.',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'employer_email' => 'hr@techsoft.com',
                'company_name' => 'TechSoft Solutions',
                'industry' => 'IT Services',
                'company_size' => '201-500',
                'founded_year' => 2018,
                'website' => 'https://techsoft.demo',
                'email' => 'contact@techsoft.com',
                'phone' => '+8801711000002',
                'address' => 'Level 5, Mirpur Road',
                'city' => 'Sylhet',
                'country' => 'Bangladesh',
                'company_description' => 'Specializes in enterprise IT services, cloud support, and digital transformation.',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $adminUser?->id,
                'is_active' => true,
            ],
        ];

        foreach ($companies as $companyData) {
            $employer = User::where('email', $companyData['employer_email'])->first();

            if (! $employer) {
                continue;
            }

            $company = Company::updateOrCreate(
                ['employer_id' => $employer->id],
                [
                    'company_name' => $companyData['company_name'],
                    'company_logo' => null,
                    'industry' => $companyData['industry'],
                    'company_size' => $companyData['company_size'],
                    'founded_year' => $companyData['founded_year'],
                    'website' => $companyData['website'],
                    'email' => $companyData['email'],
                    'phone' => $companyData['phone'],
                    'address' => $companyData['address'],
                    'city' => $companyData['city'],
                    'country' => $companyData['country'],
                    'company_description' => $companyData['company_description'],
                    'verification_status' => $companyData['verification_status'],
                    'verified_at' => $companyData['verified_at'],
                    'verified_by' => $companyData['verified_by'],
                    'is_active' => $companyData['is_active'],
                ]
            );

            $employer->forceFill(['company_id' => $company->id])->save();
        }
    }
}
