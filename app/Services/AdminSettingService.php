<?php

namespace App\Services;

use App\Models\SystemSetting;

class AdminSettingService
{
    public function getSettings(): array
    {
        $stored = SystemSetting::query()->pluck('value', 'key')->all();

        return array_merge($this->defaults(), $stored);
    }

    public function updateSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            SystemSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
            );
        }
    }

    public function defaults(): array
    {
        return [
            'site_name' => config('app.name', 'CareerConnectBD'),
            'site_email' => 'info@careerconnectbd.com',
            'contact_email' => 'contact@careerconnectbd.com',
            'support_email' => 'support@careerconnectbd.com',
            'default_timezone' => config('app.timezone', 'UTC'),
            'pagination_size' => '15',
            'maintenance_mode' => '0',
        ];
    }
}
