<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Generates the Arabic client system manual as a PDF.
 *
 * To record a future update, add a new entry at the TOP of $changelog and
 * bump $version, then run:  php artisan docs:generate
 */
class GenerateDocs extends Command
{
    protected $signature = 'docs:generate';

    protected $description = 'Generate the Arabic client system manual (PDF)';

    /** Current document/system version. */
    private string $version = '1.0';

    /**
     * Update history — newest first. Append a new row for every release.
     *
     * @var array<int, array{version: string, date: string, notes: string}>
     */
    private array $changelog = [
        ['version' => '1.0', 'date' => '2026-07-15', 'notes' => 'الإصدار الأول: الموقع العام الكامل، لوحة التحكم، صفحة «من نحن»، زر واتساب، سؤال الجدول الزمني، وتخزين الصور عبر Cloudinary.'],
    ];

    public function handle(): int
    {
        $tempDir = storage_path('app/mpdf-tmp');
        @mkdir($tempDir, 0775, true);

        $html = View::make('docs.client-manual', [
            'version' => $this->version,
            'date' => now()->format('Y-m-d'),
            'changelog' => $this->changelog,
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

        $mpdf->SetDirectionality('rtl');
        $mpdf->SetTitle('Quantum Dev Team — دليل النظام');
        $mpdf->SetAuthor('Quantum Dev Team');
        $mpdf->WriteHTML($html);

        $dir = base_path('docs');
        @mkdir($dir, 0775, true);
        $path = $dir.DIRECTORY_SEPARATOR.'Quantum-System-Guide-AR.pdf';
        $mpdf->Output($path, Destination::FILE);

        $this->info('Client manual generated: '.$path);

        return self::SUCCESS;
    }
}
