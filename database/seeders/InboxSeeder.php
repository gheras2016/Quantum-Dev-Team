<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds the admin inbox with realistic Arabic data: contact messages,
 * project requests and an activity log — so the dashboard counters, tables
 * and the "recent activity" feed are all populated.
 */
class InboxSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedContacts();
        $this->seedProjectRequests();
        $this->seedActivityLog();
    }

    private function seedContacts(): void
    {
        // [name, email, phone, subject, message, status, is_read]
        $contacts = [
            ['محمد عبد الرحمن', 'mohammed.a@gmail.com', '+249911234567', 'استفسار عن نظام إدارة المخزون', 'السلام عليكم، أرغب في معرفة تفاصيل نظام إدارة المخزون وإمكانية تخصيصه لشركتنا.', 'new', false],
            ['فاطمة الطاهر', 'fatima.t@outlook.com', '+249912345678', 'طلب عرض سعر', 'نحتاج عرض سعر لتطوير نظام حضور وانصراف لعدد 80 موظفاً. شكراً لكم.', 'new', false],
            ['أحمد الأمين', 'ahmed.amin@gmail.com', '+966501112233', 'التعاون في مشروع', 'مرحباً، لدينا فكرة تطبيق جوال ونرغب في مناقشتها معكم لبدء التعاون.', 'new', false],
            ['سلمى إبراهيم', 'salma.i@yahoo.com', '+249900998877', 'دعم فني', 'أواجه مشكلة بسيطة في لوحة التحكم وأحتاج مساعدة الفريق الفني.', 'new', false],
            ['خالد عثمان', 'khalid.o@gmail.com', '+249123456789', 'شكر وتقدير', 'أشكر الفريق على الاحترافية العالية في تنفيذ مشروعنا وتسليمه في الوقت المحدد.', 'replied', true],
            ['نورا حسن', 'noura.h@gmail.com', '+971501234567', 'استفسار عن الأسعار', 'ما هي باقات الصيانة والدعم الشهرية المتوفرة لديكم؟', 'read', true],
            ['عمر يوسف', 'omar.y@company.com', '+249915556677', 'طلب اجتماع', 'نرغب في ترتيب اجتماع عبر الإنترنت لمناقشة متطلبات نظام إدارة العقود.', 'read', true],
            ['هبة مصطفى', 'heba.m@gmail.com', '+249918887766', 'مشروع متجر إلكتروني', 'أحتاج متجراً إلكترونياً متكاملاً مع بوابة دفع. هل هذا ضمن خدماتكم؟', 'closed', true],
        ];

        foreach ($contacts as $data) {
            [$name, $email, $phone, $subject, $message, $status, $isRead] = $data;

            Contact::firstOrCreate(['email' => $email], [
                'name' => $name,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'status' => $status,
                'is_read' => $isRead,
            ]);
        }
    }

    private function seedProjectRequests(): void
    {
        // [name, email, whatsapp, project_type, timeline, description, status]
        $requests = [
            ['شركة الوفاق التجارية', 'info@alwifaq.com', '+249911000111', 'system', '3_6_months', 'نحتاج نظام إدارة مخزون ومبيعات متكامل يخدم ثلاثة فروع مع تقارير موحّدة.', 'pending'],
            ['مؤسسة إعمار للمقاولات', 'projects@iamar.com', '+249912000222', 'system', '3_6_months', 'مطلوب نظام لإدارة المشاريع والمقاولات ومتابعة تنفيذ العقود والمستخلصات.', 'pending'],
            ['متجر لمسة للأزياء', 'sales@lamsa.store', '+249913000333', 'web_app', '1_3_months', 'نرغب في تطوير متجر إلكتروني عصري مع لوحة تحكم وبوابة دفع محلية.', 'pending'],
            ['عيادة الشفاء التخصصية', 'clinic@alshifa.sd', '+249914000444', 'system', '1_3_months', 'نظام لإدارة المواعيد والملفات الطبية والفواتير للعيادة.', 'in_review'],
            ['أكاديمية النخبة التعليمية', 'admin@elite-academy.sd', '+249915000555', 'web_app', '3_6_months', 'منصة تعليمية للحصص المباشرة وإدارة الطلاب والدرجات.', 'approved'],
            ['مطعم الذواقة', 'contact@aldawaqa.com', '+249916000666', 'mobile_app', 'urgent', 'تطبيق جوال لاستقبال الطلبات وتوصيلها مع نظام نقاط ولاء.', 'in_progress'],
            ['فندق النيل الأزرق', 'it@bluenile-hotel.sd', '+249917000777', 'system', 'flexible', 'نظام متكامل لإدارة الفندق والحجوزات والخدمات والتقارير.', 'completed'],
            ['شخص - رائد أعمال', 'startup.idea@gmail.com', '+249918000888', 'other', 'flexible', 'لدي فكرة مبدئية وأحتاج استشارة تقنية لتحديد الخطوات القادمة.', 'rejected'],
        ];

        foreach ($requests as $i => $data) {
            [$name, $email, $whatsapp, $type, $timeline, $description, $status] = $data;

            ProjectRequest::firstOrCreate(['email' => $email], [
                'name' => $name,
                'whatsapp' => $whatsapp,
                'project_type' => $type,
                'timeline' => $timeline,
                'description' => $description,
                'status' => $status,
                'is_read' => $i >= 3,
            ]);
        }
    }

    private function seedActivityLog(): void
    {
        // Only seed once, to avoid duplicate feed entries on re-seed.
        if (ActivityLog::query()->exists()) {
            return;
        }

        $admin = User::query()->orderBy('id')->first();
        $projectIds = Project::query()->pluck('id')->all();

        $entries = [
            'أنشأ مشروعاً جديداً: نظام الحركة والنقل',
            'حدّث بيانات مشروع: نظام الموارد البشرية',
            'أضاف خدمة جديدة: الأمن السيبراني',
            'ردّ على رسالة تواصل من أحد العملاء',
            'حدّث حالة طلب مشروع إلى «قيد التنفيذ»',
            'أضاف عضواً جديداً إلى الفريق',
            'نشر مقالاً جديداً في المدونة',
            'حدّث إعدادات الموقع',
            'أضاف شهادة عميل جديدة',
            'سجّل الدخول إلى لوحة التحكم',
        ];

        foreach ($entries as $i => $description) {
            $log = new ActivityLog([
                'log_name' => 'admin',
                'description' => $description,
                'subject_type' => Project::class,
                'subject_id' => $projectIds[$i % max(count($projectIds), 1)] ?? null,
                'causer_type' => $admin ? User::class : null,
                'causer_id' => $admin?->id,
                'ip_address' => '197.148.'.random_int(1, 254).'.'.random_int(1, 254),
                'user_agent' => 'Mozilla/5.0',
                'device_type' => $i % 3 === 0 ? 'mobile' : 'desktop',
            ]);
            $log->created_at = now()->subHours($i * 6 + 1);
            $log->updated_at = $log->created_at;
            $log->save();
        }
    }
}
