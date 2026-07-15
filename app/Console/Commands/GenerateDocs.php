<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Generates the client system manual as a PDF, in both Arabic and English.
 *
 * To record a future update, add a new entry at the TOP of $changelog and
 * bump $version, then run:  php artisan docs:generate
 */
class GenerateDocs extends Command
{
    protected $signature = 'docs:generate';

    protected $description = 'Generate the client system manual (Arabic + English PDFs)';

    /** Current document/system version. */
    private string $version = '1.1';

    /**
     * Update history — newest first. Append a new row for every release.
     *
     * @var array<int, array{version: string, date: string, ar: string, en: string}>
     */
    private array $changelog = [
        ['version' => '1.1', 'date' => '2026-07-15', 'ar' => 'أغلفة تحمل أسماء المشاريع والمقالات، ونسخة إنجليزية من الدليل، وإتاحة تحميله من لوحة التحكم.', 'en' => 'Branded covers with project/article names, an English guide, and in-dashboard downloads.'],
        ['version' => '1.0', 'date' => '2026-07-15', 'ar' => 'الإصدار الأول: الموقع العام الكامل، لوحة التحكم، صفحة «من نحن»، زر واتساب، سؤال الجدول الزمني، وتخزين الصور عبر Cloudinary.', 'en' => 'Initial release: full public site, admin dashboard, About page, WhatsApp button, timeline field and Cloudinary image storage.'],
    ];

    public function handle(): int
    {
        $tempDir = storage_path('app/mpdf-tmp');
        @mkdir($tempDir, 0775, true);

        $dir = base_path('docs');
        @mkdir($dir, 0775, true);

        $variants = [
            ['view' => 'docs.client-manual', 'dir' => 'rtl', 'notes' => 'ar', 'file' => 'Quantum-System-Guide-AR.pdf'],
            ['view' => 'docs.client-manual-en', 'dir' => 'ltr', 'notes' => 'en', 'file' => 'Quantum-System-Guide-EN.pdf'],
        ];

        foreach ($variants as $variant) {
            $changelog = array_map(fn ($e) => [
                'version' => $e['version'],
                'date' => $e['date'],
                'notes' => $e[$variant['notes']],
            ], $this->changelog);

            $html = View::make($variant['view'], [
                'version' => $this->version,
                'date' => now()->format('Y-m-d'),
                'changelog' => $changelog,
            ])->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 18,
                'margin_bottom' => 18,
                'margin_left' => 15,
                'margin_right' => 15,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'tempDir' => $tempDir,
            ]);

            $mpdf->SetDirectionality($variant['dir']);
            $mpdf->SetTitle('Quantum Dev Team — System Guide');
            $mpdf->SetAuthor('Quantum Dev Team');
            $mpdf->WriteHTML($html);
            $mpdf->Output($dir.DIRECTORY_SEPARATOR.$variant['file'], Destination::FILE);

            $this->info('Generated: docs/'.$variant['file']);
        }

        return self::SUCCESS;
    }
}
