<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds realistic Arabic content for Quantum Dev Team: technologies,
 * categories, tags, services (fields of work), projects, the management team
 * and social links — keeping every relationship (pivots) intact.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTechnologies();
        $categories = $this->seedCategories();
        $tags = $this->seedTags();
        $this->seedServices();
        $this->seedProjects($categories, $tags);
        $this->seedTeam();
        $this->seedSocialLinks();
    }

    private function seedTechnologies(): void
    {
        $items = [
            'Laravel', 'PHP', 'Livewire', 'Vue.js', 'React', 'Inertia.js',
            'Tailwind CSS', 'Bootstrap', 'Alpine.js', 'MySQL', 'PostgreSQL',
            'Redis', 'Flutter', 'Dart', 'Node.js', 'REST API', 'Docker', 'Nginx', 'Git',
        ];

        foreach ($items as $name) {
            Technology::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }

    /**
     * @return array<string, \App\Models\Category>
     */
    private function seedCategories(): array
    {
        $data = [
            'الأنظمة الإدارية' => 'management',
            'تطبيقات الويب' => 'web',
            'تطبيقات الجوال' => 'mobile',
            'النقل واللوجستيات' => 'logistics',
            'الموارد البشرية' => 'hr',
            'التجارة الإلكترونية' => 'ecommerce',
            'الأنظمة التعليمية' => 'education',
            'الأنظمة الصحية' => 'health',
        ];

        $categories = [];
        foreach ($data as $name => $type) {
            $categories[$type] = Category::firstOrCreate(
                ['slug' => Str::slug($type)],
                ['name' => $name, 'type' => 'projects', 'description' => 'مشاريع في مجال '.$name.'.']
            );
        }

        return $categories;
    }

    /**
     * @return array<int, \App\Models\Tag>
     */
    private function seedTags(): array
    {
        $names = [
            'تطوير ويب', 'أنظمة إدارية', 'تطبيقات جوال', 'قواعد بيانات',
            'واجهات مستخدم', 'الأمن السيبراني', 'الأداء', 'الاستضافة السحابية',
            'التكامل البرمجي', 'إدارة المشاريع',
        ];

        $tags = [];
        foreach ($names as $name) {
            $tags[] = Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        return $tags;
    }

    private function seedServices(): void
    {
        // The team's twelve fields of work.
        $services = [
            ['laravel-php-development', 'تطوير تطبيقات Laravel و PHP', 'Laravel & PHP Development', 'نبني أنظمة ويب قوية وقابلة للتوسع باستخدام إطار Laravel وأحدث ممارسات PHP.', 'We build robust, scalable web systems with Laravel and modern PHP practices.'],
            ['management-systems', 'تطوير الأنظمة الإدارية', 'Management Systems', 'أنظمة إدارية متكاملة تؤتمت عمليات مؤسستك وترفع كفاءتها.', 'Integrated management systems that automate and streamline your operations.'],
            ['ui-ux-design', 'تصميم واجهات المستخدم', 'UI/UX Design', 'واجهات عصرية تتمحور حول المستخدم وتجربة استخدام سلسة واحترافية.', 'Modern, user-centered interfaces with a smooth, professional experience.'],
            ['mobile-development', 'تطوير تطبيقات الجوال', 'Mobile App Development', 'تطبيقات جوال أصلية ومتعددة المنصات لنظامي iOS و Android بأداء عالٍ.', 'High-performance native and cross-platform apps for iOS and Android.'],
            ['systems-analysis', 'تحليل الأنظمة', 'Systems Analysis', 'تحليل دقيق للمتطلبات وتحويلها إلى حلول تقنية واضحة قابلة للتنفيذ.', 'Precise requirements analysis turned into clear, actionable solutions.'],
            ['database-design', 'تصميم قواعد البيانات', 'Database Design', 'تصميم قواعد بيانات محكمة ومحسّنة تضمن سلامة البيانات وسرعتها.', 'Well-structured, optimized databases ensuring data integrity and speed.'],
            ['api-integration', 'التكامل مع الأنظمة والـ APIs', 'API Integration', 'ربط أنظمتك بخدمات وواجهات برمجية خارجية بسلاسة وأمان.', 'Seamless, secure integration with external services and APIs.'],
            ['cloud-hosting', 'الحوسبة السحابية والاستضافة', 'Cloud & Hosting', 'حلول استضافة سحابية موثوقة مع إعداد ونشر وصيانة مستمرة.', 'Reliable cloud hosting with setup, deployment and ongoing maintenance.'],
            ['cybersecurity', 'الأمن السيبراني', 'Cybersecurity', 'حماية أنظمتك وبياناتك وفق أفضل معايير الأمان العالمية.', 'Protecting your systems and data with global security best practices.'],
            ['performance-optimization', 'تحسين الأداء', 'Performance Optimization', 'رفع سرعة وكفاءة أنظمتك عبر تحليل وتحسين الأداء بعمق.', 'Boosting speed and efficiency through deep performance tuning.'],
            ['technical-project-management', 'إدارة المشاريع التقنية', 'Technical Project Management', 'إدارة احترافية للمشاريع من التخطيط حتى التسليم بجودة والتزام.', 'Professional project management from planning to delivery.'],
            ['technical-consulting', 'الاستشارات التقنية', 'Technical Consulting', 'استشارات متخصصة تساعدك على اتخاذ القرارات التقنية الصحيحة.', 'Specialized consulting to help you make the right technical decisions.'],
        ];

        $icons = [
            'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
            'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
            'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
            'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
            'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4',
            'M13 10V3L4 14h7v7l9-11h-7z',
            'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
            'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ];

        foreach ($services as $i => [$slug, $titleAr, $titleEn, $descAr, $descEn]) {
            Service::firstOrCreate(['slug' => $slug], [
                'title' => ['ar' => $titleAr, 'en' => $titleEn],
                'description' => ['ar' => $descAr, 'en' => $descEn],
                'icon' => $icons[$i] ?? null,
                'order' => $i,
                'is_active' => true,
                'status' => 'published',
            ]);
        }
    }

    private function seedProjects(array $categories, array $tags): void
    {
        // [slug, titleAr, titleEn, descAr, descEn, categoryType, client, months]
        $projects = [
            ['transport-system', 'نظام الحركة والنقل', 'Transport & Movement System', 'نظام متكامل لإدارة حركة المركبات والرحلات وتتبّعها وجدولة الصيانة وتقارير الاستهلاك.', 'Integrated system for managing vehicle movement, trips, tracking and maintenance scheduling.', 'logistics', 'الشركة السودانية للنقل', 6],
            ['attendance-system', 'نظام الحضور والغياب', 'Attendance System', 'نظام لإدارة حضور وانصراف الموظفين بالبصمة والمواقع مع تقارير يومية وشهرية دقيقة.', 'Employee attendance system with biometric and geolocation check-in and detailed reports.', 'hr', 'مجموعة النيل للخدمات', 3],
            ['hr-system', 'نظام الموارد البشرية', 'Human Resources System', 'نظام شامل لإدارة شؤون الموظفين والرواتب والإجازات والتقييم والهيكل الوظيفي.', 'Comprehensive HR system for staff, payroll, leaves, evaluations and org structure.', 'hr', 'شركة الأمل القابضة', 8],
            ['project-management', 'نظام إدارة المشاريع', 'Project Management System', 'منصة لإدارة المشاريع والمهام والفرق والجداول الزمنية ومتابعة نسب الإنجاز.', 'A platform to manage projects, tasks, teams, timelines and progress tracking.', 'management', 'مؤسسة إعمار للمقاولات', 5],
            ['ticketing-system', 'نظام التذاكر والبلاغات', 'Ticketing & Support System', 'نظام لاستقبال بلاغات وشكاوى العملاء وتوجيهها ومتابعتها حتى الإغلاق مع تقييم الخدمة.', 'A system to receive, route and resolve customer tickets with service rating.', 'management', 'شركة الاتصالات المتقدمة', 4],
            ['task-management', 'نظام إدارة المهام', 'Task Management System', 'نظام لتنظيم المهام اليومية وتوزيعها على الفرق ومتابعة الأولويات والمواعيد النهائية.', 'A system to organize, assign and track daily tasks, priorities and deadlines.', 'management', 'مجموعة الرؤية الرقمية', 3],
            ['reports-system', 'نظام إدارة التقارير', 'Reports Management System', 'نظام لإنشاء وإدارة التقارير التحليلية والرسوم البيانية وتصديرها بصيغ متعددة.', 'A system to build, manage and export analytical reports and charts.', 'management', 'بنك التنمية الصناعية', 4],
            ['inventory-system', 'نظام إدارة المخزون', 'Inventory Management System', 'نظام لإدارة المخزون والأصناف وحركات الدخول والخروج والجرد وتنبيهات النفاد.', 'Inventory system for items, stock movements, counts and low-stock alerts.', 'management', 'شركة الوفاق التجارية', 5],
            ['crm-system', 'نظام إدارة العملاء', 'Customer Relationship System (CRM)', 'نظام لإدارة علاقات العملاء ومسارات المبيعات والفرص والمتابعات والحملات.', 'CRM for managing customers, sales pipelines, opportunities and follow-ups.', 'management', 'مؤسسة آفاق للتسويق', 6],
            ['maintenance-system', 'نظام إدارة الصيانة', 'Maintenance Management System', 'نظام لإدارة طلبات الصيانة الوقائية والطارئة وجدولة الفنيين وقطع الغيار.', 'System for preventive and emergency maintenance requests and technician scheduling.', 'management', 'شركة الخدمات الفنية المتحدة', 4],
            ['contracts-system', 'نظام إدارة العقود', 'Contracts Management System', 'نظام لإدارة العقود والاتفاقيات وتواريخ التجديد والتنبيهات والمرفقات القانونية.', 'System for managing contracts, renewals, reminders and legal attachments.', 'management', 'شركة الصفوة للاستثمار', 5],
            ['assets-system', 'نظام إدارة الأصول', 'Assets Management System', 'نظام لتسجيل الأصول وتتبّع مواقعها وقيمتها وإهلاكها وعمليات الصيانة عليها.', 'System to register assets, track locations, value, depreciation and maintenance.', 'management', 'الهيئة العامة للمرافق', 6],
            ['warehouse-system', 'نظام إدارة المستودعات', 'Warehouse Management System', 'نظام لإدارة المستودعات والمواقع والاستلام والصرف والشحن والجرد الدوري.', 'Warehouse system for locations, receiving, dispatch, shipping and stocktaking.', 'logistics', 'شركة البركة للتوريدات', 5],
            ['school-system', 'نظام إدارة المدارس', 'School Management System', 'نظام لإدارة الطلاب والمعلمين والصفوف والدرجات والرسوم والتواصل مع أولياء الأمور.', 'School system for students, teachers, classes, grades, fees and parent communication.', 'education', 'مدارس المستقبل الأهلية', 7],
            ['clinic-system', 'نظام إدارة العيادات', 'Clinic Management System', 'نظام لإدارة المواعيد والملفات الطبية والوصفات والفواتير والتقارير الصحية.', 'Clinic system for appointments, medical records, prescriptions and billing.', 'health', 'مجمع الشفاء الطبي', 6],
            ['restaurant-system', 'نظام إدارة المطاعم', 'Restaurant Management System', 'نظام لإدارة الطلبات والطاولات والمنيو والمخزون والكاشير وتقارير المبيعات.', 'Restaurant system for orders, tables, menu, inventory, POS and sales reports.', 'management', 'سلسلة مطاعم الذواقة', 4],
            ['hotel-system', 'نظام إدارة الفنادق', 'Hotel Management System', 'نظام لإدارة الغرف والحجوزات والنزلاء والخدمات والفواتير والتقارير التشغيلية.', 'Hotel system for rooms, reservations, guests, services and operational reports.', 'management', 'فندق النيل الأزرق', 8],
            ['booking-system', 'نظام إدارة الحجوزات', 'Booking Management System', 'نظام حجوزات مرن للمواعيد والخدمات مع الدفع الإلكتروني والتذكير التلقائي.', 'Flexible booking system for appointments and services with online payment.', 'web', 'منصة موعد الرقمية', 3],
        ];

        $technologies = Technology::pluck('id')->all();

        foreach ($projects as $i => [$slug, $titleAr, $titleEn, $descAr, $descEn, $catType, $client, $months]) {
            $isFeatured = $i < 6;
            $project = Project::firstOrCreate(['slug' => $slug], [
                'title' => ['ar' => $titleAr, 'en' => $titleEn],
                'description' => ['ar' => $descAr, 'en' => $descEn],
                'client_name' => $client,
                'duration' => $months.' أشهر',
                'progress' => 100,
                'status' => 'published',
                'featured' => $isFeatured,
                'views_count' => random_int(120, 1800),
                'case_study' => $isFeatured
                    ? 'المشكلة: احتاج العميل إلى أتمتة عملياته اليدوية. الحل: صمّمنا وطوّرنا '.$titleAr.' بواجهات سهلة وتقارير دقيقة. النتيجة: تقليل الوقت والأخطاء ورفع الكفاءة التشغيلية بشكل ملحوظ.'
                    : null,
                'published_at' => now()->subDays(($i + 1) * 6),
            ]);

            // Relationships (pivots).
            $project->technologies()->syncWithoutDetaching(
                collect($technologies)->shuffle()->take(random_int(3, 5))->all()
            );
            $project->categories()->syncWithoutDetaching([
                $categories[$catType]->id,
                $categories['management']->id ?? $categories[$catType]->id,
            ]);
            $project->tags()->syncWithoutDetaching(
                collect($tags)->shuffle()->take(3)->pluck('id')->all()
            );
        }
    }

    private function seedTeam(): void
    {
        // Quantum Dev Team — management team (real roles and bios).
        $members = [
            ['عمر محمد صالح', 'قائد الفريق', 'الإشراف العام على الفريق، ووضع الرؤية التقنية، وضمان جودة تنفيذ المشاريع.', ['القيادة التقنية', 'إدارة الفرق', 'هندسة البرمجيات'], 'omar@quantum-dev.team', '+249912000001'],
            ['عثمان محمد أحمد كابتوت', 'مدير المشاريع', 'إدارة المشاريع، التخطيط، توزيع المهام، ومتابعة التنفيذ حتى التسليم.', ['إدارة المشاريع', 'التخطيط', 'أجايل'], 'othman@quantum-dev.team', '+249912000002'],
            ['مصطفى سعيد', 'مدير التطوير', 'قيادة فريق التطوير، مراجعة جودة الكود، الإشراف على تنفيذ المعايير البرمجية، وتحسين جودة المنتجات البرمجية.', ['Laravel', 'مراجعة الكود', 'معمارية البرمجيات'], 'mustafa@quantum-dev.team', '+249912000003'],
            ['أبو بكر حسن محمد نصر', 'محلل الأنظمة وقواعد البيانات', 'تحليل المتطلبات، تصميم قواعد البيانات، إعداد البنية الفنية، وتحويل احتياجات العملاء إلى حلول تقنية.', ['تحليل الأنظمة', 'تصميم قواعد البيانات', 'MySQL', 'PostgreSQL'], 'abubaker@quantum-dev.team', '+249912000004'],
            ['أبوبكر الأمين أبو الحسن', 'مدير العلاقات العامة', 'التواصل مع العملاء، إدارة العلاقات، والتنسيق الخارجي.', ['العلاقات العامة', 'إدارة العملاء', 'التنسيق'], 'alamin@quantum-dev.team', '+249912000005'],
            ['أيمن محمد', 'مطور برمجيات', 'تطوير الأنظمة وتنفيذ المتطلبات البرمجية وضمان جودة التطبيق.', ['Laravel', 'PHP', 'Vue.js', 'تطوير الويب'], 'ayman@quantum-dev.team', '+249912000006'],
        ];

        foreach ($members as $i => [$name, $role, $bio, $skills, $email, $phone]) {
            TeamMember::firstOrCreate(['name' => $name], [
                'role' => $role,
                'bio' => $bio,
                'email' => $email,
                'phone' => $phone,
                'linkedin_url' => 'https://linkedin.com/company/quantum-dev-team',
                'github_url' => 'https://github.com/quantum-dev-team',
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
            ['linkedin', 'https://linkedin.com/company/quantum-dev-team'],
            ['github', 'https://github.com/quantum-dev-team'],
            ['twitter', 'https://twitter.com/quantumdevteam'],
            ['facebook', 'https://facebook.com/quantumdevteam'],
            ['whatsapp', 'https://wa.me/249912000000'],
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
