<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #1f2937; font-size: 11.5pt; line-height: 1.7; }
        h1, h2, h3 { color: #1e3a8a; }
        .cover { text-align: center; padding-top: 120px; }
        .cover .logo { width: 90px; height: 90px; background-color: #2563eb; color: #fff; font-size: 44pt; font-weight: bold; border-radius: 20px; display: inline-block; line-height: 90px; }
        .cover h1 { font-size: 30pt; margin: 24px 0 6px; color: #111827; }
        .cover .brand { font-size: 15pt; color: #2563eb; font-weight: bold; }
        .cover .sub { font-size: 13pt; color: #6b7280; margin-top: 8px; }
        .cover .meta { margin-top: 60px; font-size: 11pt; color: #6b7280; }
        .section-title { background-color: #eff6ff; color: #1e3a8a; padding: 8px 12px; border-right: 4px solid #2563eb; font-size: 15pt; font-weight: bold; margin: 22px 0 10px; }
        .lead { color: #374151; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0 14px; }
        th { background-color: #2563eb; color: #fff; padding: 7px 9px; text-align: right; font-size: 11pt; }
        td { border-bottom: 1px solid #e5e7eb; padding: 7px 9px; vertical-align: top; }
        td.name { font-weight: bold; color: #1e3a8a; width: 32%; }
        .phase { border: 1px solid #e5e7eb; border-right: 4px solid #2563eb; border-radius: 6px; padding: 8px 12px; margin-bottom: 8px; }
        .phase .n { color: #2563eb; font-weight: bold; }
        .badge { background-color: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 10px; font-size: 9pt; }
        ul { margin: 4px 0; }
        li { margin-bottom: 3px; }
        .footer-note { color: #9ca3af; font-size: 9pt; text-align: center; margin-top: 20px; }
    </style>
</head>
<body dir="rtl">

    {{-- Cover --}}
    <div class="cover">
        <div class="logo">Q</div>
        <h1>دليل النظام</h1>
        <div class="brand">Quantum Dev Team</div>
        <div class="sub">منصة الشركة الإلكترونية ونظام الإدارة</div>
        <div class="meta">
            الإصدار {{ $version }} &nbsp;•&nbsp; {{ $date }}<br>
            وثيقة موجّهة للعملاء
        </div>
    </div>

    <pagebreak />

    {{-- 1. Overview --}}
    <div class="section-title">١. نبذة عن النظام</div>
    <p class="lead">
        نظام Quantum Dev Team هو منصة إلكترونية متكاملة ثنائية اللغة (العربية والإنجليزية) تتكوّن من جزأين:
        <b>موقع تعريفي عام</b> يعرض خدمات الفريق ومشاريعه وأعضاءه، و<b>لوحة تحكم إدارية</b> متكاملة
        تتيح إدارة كامل محتوى الموقع دون الحاجة إلى أي تدخّل برمجي.
    </p>
    <p>
        صُمّم النظام وفق أحدث معايير هندسة البرمجيات، مع التركيز على الأداء، والأمان، وتجربة المستخدم،
        وقابلية التطوير المستقبلي.
    </p>

    {{-- 2. System components --}}
    <div class="section-title">٢. أقسام النظام</div>

    <h3>أ. الموقع العام (واجهة الزوّار)</h3>
    <table>
        <tr><th>القسم</th><th>الوصف</th></tr>
        <tr><td class="name">الصفحة الرئيسية</td><td>واجهة جذّابة تعرض نبذة عن الفريق، الخدمات، أبرز المشاريع، آراء العملاء، وإحصائيات متحركة.</td></tr>
        <tr><td class="name">من نحن</td><td>تعريف بالفريق ورؤيته ومجالات عمله ورسالته وفريق الإدارة.</td></tr>
        <tr><td class="name">الخدمات</td><td>عرض تفصيلي لمجالات عمل الفريق والخدمات التقنية المقدّمة.</td></tr>
        <tr><td class="name">المشاريع</td><td>معرض الأعمال مع إمكانية التصفية حسب التصنيف، وصفحة تفاصيل لكل مشروع.</td></tr>
        <tr><td class="name">المدوّنة</td><td>مقالات تقنية تعرّف العملاء بخبرات الفريق وترفع ظهور الموقع في محركات البحث.</td></tr>
        <tr><td class="name">الفريق</td><td>عرض أعضاء الفريق بأدوارهم ومهاراتهم وروابطهم المهنية.</td></tr>
        <tr><td class="name">تواصل معنا</td><td>نموذج تواصل مباشر تصل رسائله للوحة التحكم، مع زر واتساب عائم.</td></tr>
        <tr><td class="name">اطلب مشروعك</td><td>نموذج ذكي لاستقبال طلبات المشاريع (النوع، الجدول الزمني، الوصف).</td></tr>
    </table>

    <h3>ب. لوحة التحكم (نظام الإدارة)</h3>
    <table>
        <tr><th>القسم</th><th>الوصف</th></tr>
        <tr><td class="name">لوحة المعلومات</td><td>نظرة عامة بإحصائيات النظام، رسم بياني للمحتوى، وأحدث النشاطات والرسائل.</td></tr>
        <tr><td class="name">إدارة المشاريع</td><td>إضافة وتعديل المشاريع مع الصور والمعرض والتقنيات والتصنيفات والوسوم.</td></tr>
        <tr><td class="name">إدارة الخدمات</td><td>التحكم الكامل في الخدمات المعروضة وترتيبها وحالتها.</td></tr>
        <tr><td class="name">إدارة الفريق</td><td>إضافة أعضاء الفريق وأدوارهم ومهاراتهم وصورهم.</td></tr>
        <tr><td class="name">المدوّنة والمحتوى</td><td>إدارة المقالات، آراء العملاء، والأسئلة الشائعة.</td></tr>
        <tr><td class="name">التقنيات والتصنيفات والوسوم</td><td>قوائم مرجعية تُستخدم في تصنيف المشاريع والمقالات.</td></tr>
        <tr><td class="name">الرسائل والطلبات</td><td>استقبال ومتابعة رسائل التواصل وطلبات المشاريع مع تصديرها (Excel / PDF).</td></tr>
        <tr><td class="name">المستخدمون والصلاحيات</td><td>إدارة حسابات الإدارة وأدوارهم (مدير أعلى، مدير، مدير محتوى، محرّر).</td></tr>
        <tr><td class="name">الإعدادات</td><td>التحكم في اللوجو، معلومات التواصل، نصوص الواجهة، والإحصائيات.</td></tr>
        <tr><td class="name">سلة المحذوفات</td><td>استعادة أو حذف نهائي للعناصر المحذوفة.</td></tr>
    </table>

    <pagebreak />

    {{-- 3. Features --}}
    <div class="section-title">٣. المميزات الرئيسية</div>
    <ul>
        <li><b>ثنائية اللغة:</b> دعم كامل للعربية والإنجليزية مع تبديل فوري واتجاه صحيح للنص (RTL/LTR).</li>
        <li><b>تصميم متجاوب:</b> يعمل بكفاءة على الجوال والحاسوب واللوحي.</li>
        <li><b>الوضع الليلي:</b> واجهة مريحة للعين نهاراً وليلاً.</li>
        <li><b>تحسين محركات البحث (SEO):</b> خريطة موقع، روابط أنيقة، وبيانات مشاركة اجتماعية.</li>
        <li><b>الأمان:</b> نظام صلاحيات متعدّد المستويات، حماية النماذج من السبام، وتشفير البيانات.</li>
        <li><b>سجل النشاط:</b> تتبّع كل العمليات الإدارية (من قام بماذا ومتى).</li>
        <li><b>الأداء:</b> ضغط الصور تلقائياً وتخزين سحابي دائم، وتحميل سريع.</li>
        <li><b>إشعارات:</b> تنبيه فوري عبر البريد عند وصول رسالة أو طلب مشروع.</li>
    </ul>

    {{-- 4. Development phases --}}
    <div class="section-title">٤. مراحل التطوير</div>
    <div class="phase"><span class="n">المرحلة 1 — التحليل والتخطيط:</span> فهم المتطلبات، تحديد أقسام النظام، وتصميم قاعدة البيانات. <span class="badge">مكتملة</span></div>
    <div class="phase"><span class="n">المرحلة 2 — التصميم:</span> تصميم واجهات المستخدم والهوية البصرية وتجربة الاستخدام. <span class="badge">مكتملة</span></div>
    <div class="phase"><span class="n">المرحلة 3 — التطوير:</span> بناء الموقع العام ولوحة التحكم بكامل الوظائف. <span class="badge">مكتملة</span></div>
    <div class="phase"><span class="n">المرحلة 4 — الاختبار وضمان الجودة:</span> اختبارات آلية شاملة ومراجعة الأداء والأمان. <span class="badge">مكتملة</span></div>
    <div class="phase"><span class="n">المرحلة 5 — النشر (Deployment):</span> رفع النظام على خادم سحابي (Render) مع قاعدة بيانات PostgreSQL. <span class="badge">مكتملة</span></div>
    <div class="phase"><span class="n">المرحلة 6 — الدعم والتحسين المستمر:</span> إضافة تحسينات جديدة ومتابعة الأداء بناءً على الملاحظات. <span class="badge">جارية</span></div>

    {{-- 5. Tech --}}
    <div class="section-title">٥. البنية التقنية (لمحة)</div>
    <table>
        <tr><td class="name">إطار العمل</td><td>Laravel 10 (PHP)</td></tr>
        <tr><td class="name">قاعدة البيانات</td><td>PostgreSQL / MySQL</td></tr>
        <tr><td class="name">الواجهات</td><td>Blade + Tailwind CSS + Alpine.js</td></tr>
        <tr><td class="name">الاستضافة</td><td>Render (Docker) + تخزين صور عبر Cloudinary</td></tr>
        <tr><td class="name">الأمان</td><td>Spatie Permissions + حماية CSRF والنماذج</td></tr>
    </table>

    {{-- 6. Changelog --}}
    <div class="section-title">٦. سجل التحديثات</div>
    <table>
        <tr><th>الإصدار</th><th>التاريخ</th><th>التحديثات</th></tr>
        @foreach ($changelog as $entry)
            <tr>
                <td class="name">{{ $entry['version'] }}</td>
                <td>{{ $entry['date'] }}</td>
                <td>{{ $entry['notes'] }}</td>
            </tr>
        @endforeach
    </table>

    <p class="footer-note">© {{ date('Y') }} Quantum Dev Team — جميع الحقوق محفوظة. هذه الوثيقة تُحدَّث مع كل تطوير جديد على النظام.</p>

</body>
</html>
