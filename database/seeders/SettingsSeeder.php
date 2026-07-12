<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Contact information
            ['site_email', 'info@quantumdevteam.com', 'general'],
            ['site_phone', '+966 50 000 0000', 'general'],
            ['site_whatsapp', '+966500000000', 'general'],
            ['site_address_ar', 'المملكة العربية السعودية', 'general'],
            ['site_address_en', 'Saudi Arabia', 'general'],

            // Hero
            ['hero_title_ar', 'نطوّر حلولاً رقمية تنمو مع أعمالك', 'hero'],
            ['hero_title_en', 'We Build Digital Solutions That Grow With Your Business', 'hero'],
            ['hero_subtitle_ar', 'فريق تطوير مستقل متخصص في بناء حلول تقنية مبتكرة.', 'hero'],
            ['hero_subtitle_en', 'An independent development team specialized in building innovative technical solutions.', 'hero'],

            // Stats
            ['stat_projects', '50', 'stats'],
            ['stat_clients', '30', 'stats'],
            ['stat_years', '5', 'stats'],
        ];

        foreach ($defaults as [$key, $value, $group]) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group, 'is_public' => true]
            );
        }
    }
}
