<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class DemoSystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'CareerConnectBD'],
            ['key' => 'support_email', 'value' => 'support@careerconnectbd.com'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'default_language', 'value' => 'English'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
