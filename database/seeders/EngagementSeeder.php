<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Post;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPosts();
        $this->seedTestimonials();
        $this->seedFaqs();
    }

    private function seedPosts(): void
    {
        $posts = [
            ['Why Laravel Is Our Framework of Choice', 'لماذا نختار Laravel كإطار عمل أساسي'],
            ['5 Signs Your Business Needs a Custom System', '5 علامات تدل على حاجة عملك لنظام مخصص'],
            ['A Practical Guide to Mobile App Development', 'دليل عملي لتطوير تطبيقات الجوال'],
        ];

        foreach ($posts as $i => [$en, $ar]) {
            Post::firstOrCreate(['slug' => Str::slug($en)], [
                'title' => ['en' => $en, 'ar' => $ar],
                'excerpt' => ['en' => 'A short overview of the topic and why it matters for your project.', 'ar' => 'نظرة موجزة على الموضوع وأهميته لمشروعك.'],
                'body' => [
                    'en' => str_repeat('This is a demo article body paragraph explaining the topic in depth. ', 20),
                    'ar' => str_repeat('هذه فقرة تجريبية لمحتوى المقال تشرح الموضوع بالتفصيل. ', 20),
                ],
                'status' => 'published',
                'featured' => $i === 0,
                'published_at' => now()->subDays($i * 3),
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        $items = [
            ['Khalid Al-Otaibi', 'CEO', 'Nova Retail', 5, 'The team delivered our platform ahead of schedule with outstanding quality.', 'سلّم الفريق منصتنا قبل الموعد وبجودة استثنائية.'],
            ['Mona Al-Harbi', 'Product Manager', 'BrightApps', 5, 'Professional, responsive and truly invested in our success.', 'احترافية وسرعة استجابة واهتمام حقيقي بنجاحنا.'],
            ['Yousef Karim', 'Founder', 'TechNest', 4, 'Great communication throughout the project. Highly recommended.', 'تواصل ممتاز طوال المشروع. أنصح بهم بشدة.'],
        ];

        foreach ($items as $i => [$name, $title, $company, $rating, $en, $ar]) {
            Testimonial::firstOrCreate(['author_name' => $name], [
                'author_title' => $title,
                'author_company' => $company,
                'rating' => $rating,
                'content' => ['en' => $en, 'ar' => $ar],
                'order' => $i,
                'is_active' => true,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $items = [
            ['How long does a typical project take?', 'كم يستغرق المشروع عادةً؟', 'It depends on scope, but most projects take between 4 and 12 weeks.', 'يعتمد على حجم المشروع، لكن معظم المشاريع تستغرق بين 4 و12 أسبوعاً.'],
            ['Do you provide support after delivery?', 'هل تقدمون دعماً بعد التسليم؟', 'Yes, we offer ongoing maintenance and technical support packages.', 'نعم، نوفر باقات صيانة ودعم فني مستمر.'],
            ['Can you work with our existing system?', 'هل يمكنكم العمل مع نظامنا الحالي؟', 'Absolutely. We can integrate with or extend your current systems.', 'بالتأكيد، يمكننا التكامل مع أنظمتكم الحالية أو تطويرها.'],
        ];

        foreach ($items as $i => [$qEn, $qAr, $aEn, $aAr]) {
            Faq::firstOrCreate(
                ['order' => $i],
                [
                    'question' => ['en' => $qEn, 'ar' => $qAr],
                    'answer' => ['en' => $aEn, 'ar' => $aAr],
                    'is_active' => true,
                ]
            );
        }
    }
}
