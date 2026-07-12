<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTechnologies();
        $categories = $this->seedCategories();
        $this->seedServices();
        $this->seedProjects($categories);
        $this->seedTeam();
        $this->seedSocialLinks();
    }

    private function seedTechnologies(): void
    {
        foreach (['Laravel', 'Vue.js', 'React', 'Tailwind CSS', 'Flutter', 'PHP', 'MySQL', 'Node.js'] as $name) {
            Technology::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }

    private function seedCategories(): array
    {
        $data = [
            'Web Applications' => 'projects',
            'Mobile Apps' => 'projects',
            'Management Systems' => 'projects',
            'E-Commerce' => 'projects',
        ];

        $categories = [];
        foreach ($data as $name => $type) {
            $categories[] = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => $type]
            );
        }

        return $categories;
    }

    private function seedServices(): void
    {
        $services = [
            ['web-development', 'Web Development', 'تطوير الويب', 'Building professional, high-performance websites using the latest technologies.', 'نبني مواقع احترافية عالية الأداء باستخدام أحدث التقنيات.'],
            ['mobile-development', 'Mobile Development', 'تطوير تطبيقات الجوال', 'Native and cross-platform mobile apps for iOS and Android.', 'تطبيقات جوال أصلية ومتعددة المنصات لنظامي iOS و Android.'],
            ['system-development', 'System Development', 'تطوير الأنظمة', 'Integrated management systems and custom software solutions.', 'أنظمة إدارية متكاملة وحلول برمجية مخصصة.'],
            ['ui-ux-design', 'UI/UX Design', 'تصميم واجهات المستخدم', 'User-centered interfaces with a distinctive experience.', 'واجهات تتمحور حول المستخدم بتجربة مميزة.'],
            ['technical-consulting', 'Technical Consulting', 'الاستشارات التقنية', 'Specialized consulting to help you make the right technical decisions.', 'استشارات متخصصة تساعدك على اتخاذ القرارات التقنية الصحيحة.'],
            ['maintenance-support', 'Maintenance & Support', 'الصيانة والدعم', 'Continuous maintenance and round-the-clock technical support.', 'صيانة مستمرة ودعم فني على مدار الساعة.'],
        ];

        $icons = [
            'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
            'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
            'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z',
            'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
            'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
            'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        ];

        foreach ($services as $i => [$slug, $titleEn, $titleAr, $descEn, $descAr]) {
            Service::firstOrCreate(['slug' => $slug], [
                'title' => ['en' => $titleEn, 'ar' => $titleAr],
                'description' => ['en' => $descEn, 'ar' => $descAr],
                'icon' => $icons[$i] ?? null,
                'order' => $i,
                'is_active' => true,
                'status' => 'published',
            ]);
        }
    }

    private function seedProjects(array $categories): void
    {
        $projects = [
            ['smart-store', 'Smart Store Platform', 'منصة المتجر الذكي', 'A full e-commerce platform with inventory and analytics.', 'منصة تجارة إلكترونية متكاملة مع إدارة المخزون والتحليلات.'],
            ['fleet-manager', 'Fleet Management System', 'نظام إدارة الأسطول', 'Real-time fleet tracking and maintenance scheduling.', 'تتبّع الأسطول في الوقت الحقيقي وجدولة الصيانة.'],
            ['health-app', 'Health Companion App', 'تطبيق الرفيق الصحي', 'A mobile app that helps users track their health goals.', 'تطبيق جوال يساعد المستخدمين على متابعة أهدافهم الصحية.'],
            ['edu-portal', 'Education Portal', 'بوابة التعليم', 'An online learning platform with live classes and grading.', 'منصة تعليم إلكتروني تدعم الحصص المباشرة والتقييم.'],
            ['booking-system', 'Booking System', 'نظام الحجوزات', 'A reservation system for clinics and service providers.', 'نظام حجوزات للعيادات ومزوّدي الخدمات.'],
            ['crm-suite', 'CRM Suite', 'حزمة إدارة العملاء', 'Customer relationship management with sales pipelines.', 'إدارة علاقات العملاء مع مسارات المبيعات.'],
        ];

        $technologies = Technology::pluck('id')->all();

        foreach ($projects as $i => [$slug, $titleEn, $titleAr, $descEn, $descAr]) {
            $project = Project::firstOrCreate(['slug' => $slug], [
                'title' => ['en' => $titleEn, 'ar' => $titleAr],
                'description' => ['en' => $descEn, 'ar' => $descAr],
                'client_name' => 'Client '.($i + 1),
                'duration' => (2 + $i).' months',
                'progress' => 100,
                'status' => 'published',
                'featured' => $i < 3,
                'published_at' => now()->subDays($i * 5),
            ]);

            $project->technologies()->syncWithoutDetaching(
                collect($technologies)->shuffle()->take(3)->all()
            );
            $project->categories()->syncWithoutDetaching([$categories[$i % count($categories)]->id]);
        }
    }

    private function seedTeam(): void
    {
        $members = [
            ['Ahmed Ali', 'Full-Stack Developer', ['Laravel', 'Vue.js', 'MySQL']],
            ['Sara Mohammed', 'UI/UX Designer', ['Figma', 'Tailwind CSS', 'Design Systems']],
            ['Omar Khaled', 'Mobile Developer', ['Flutter', 'Dart', 'Firebase']],
            ['Layla Hassan', 'Backend Engineer', ['PHP', 'Node.js', 'PostgreSQL']],
        ];

        foreach ($members as $i => [$name, $role, $skills]) {
            TeamMember::firstOrCreate(['name' => $name], [
                'role' => $role,
                'bio' => 'A passionate professional dedicated to building great products.',
                'skills' => $skills,
                'order' => $i,
                'is_active' => true,
                'status' => 'published',
            ]);
        }
    }

    private function seedSocialLinks(): void
    {
        $links = [
            ['linkedin', 'https://linkedin.com'],
            ['github', 'https://github.com'],
            ['twitter', 'https://twitter.com'],
        ];

        foreach ($links as $i => [$platform, $url]) {
            SocialLink::firstOrCreate(['platform' => $platform], [
                'url' => $url,
                'order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
