<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $about = 'Quantum Dev Team فريق تطوير تقني مستقل يعمل بأسلوب العمل الحر، ويضم مجموعة من المطورين والمتخصصين من مناطق مختلفة، يجمعهم هدف واحد وهو تقديم حلول تقنية احترافية وفق أعلى معايير الجودة. نعمل على تحويل الأفكار إلى أنظمة احترافية قابلة للتطوير مع الالتزام بأفضل ممارسات هندسة البرمجيات والأداء والأمان وتجربة المستخدم.';
        $message = 'نؤمن بأن نجاح أي مشروع يبدأ بفهم احتياجات العميل، ثم تحويلها إلى حلول تقنية مستقرة وقابلة للتوسع، مع الالتزام بأعلى معايير الجودة والاحترافية.';

        $defaults = [
            // Contact information
            ['site_email', 'info@quantum-dev.team', 'general'],
            ['site_phone', '+249 91 200 0000', 'general'],
            ['site_whatsapp', '+249912000000', 'general'],
            ['site_address_ar', 'الخرطوم، السودان — فريق موزّع', 'general'],
            ['site_address_en', 'Khartoum, Sudan — Distributed Team', 'general'],

            // Hero
            ['hero_title_ar', 'نحوّل أفكارك إلى أنظمة احترافية قابلة للتطوير', 'hero'],
            ['hero_title_en', 'We Turn Your Ideas Into Scalable, Professional Systems', 'hero'],
            ['hero_subtitle_ar', 'فريق تطوير تقني مستقل يقدّم حلولاً احترافية وفق أعلى معايير الجودة والأداء والأمان.', 'hero'],
            ['hero_subtitle_en', 'An independent tech team delivering professional solutions with the highest standards of quality, performance and security.', 'hero'],

            // Statistics
            ['stat_projects', '30', 'stats'],
            ['stat_clients', '22', 'stats'],
            ['stat_years', '5', 'stats'],

            // Company profile (data available for any about/team section)
            ['about_ar', $about, 'company'],
            ['about_en', 'Quantum Dev Team is an independent tech team working in a freelance model, bringing together developers and specialists from different regions with one goal: delivering professional technical solutions to the highest quality standards.', 'company'],
            ['team_message_ar', $message, 'company'],
            ['team_message_en', 'We believe every successful project starts by understanding the client\'s needs, then turning them into stable, scalable technical solutions with the highest standards of quality and professionalism.', 'company'],
        ];

        foreach ($defaults as [$key, $value, $group]) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group, 'is_public' => true]
            );
        }
    }
}
