<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use Carbon\Carbon;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'registration_open_date', 'value' => '2026-07-03'],
            ['key' => 'registration_close_date', 'value' => '2026-07-10'],
            ['key' => 'upload_open_date', 'value' => '2026-07-11'],
            ['key' => 'upload_close_date', 'value' => '2026-07-24'],
            ['key' => 'presentation_start_date', 'value' => '2026-07-27'],
            ['key' => 'students_per_day', 'value' => '5'],
            ['key' => 'presentation_duration', 'value' => '30'],
            ['key' => 'presentation_start_time', 'value' => '09:00:00'],
            ['key' => 'break_duration', 'value' => '0'],
            ['key' => 'venue', 'value' => 'ACETEL Main Hall'],
            ['key' => 'institution_name', 'value' => 'Africa Centre of Excellence on Technology Enhanced Learning (ACETEL)'],
            ['key' => 'academic_session', 'value' => '2025/2026'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
