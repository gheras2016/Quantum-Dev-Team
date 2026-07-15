<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    private const GUIDES = [
        'ar' => 'Quantum-System-Guide-AR.pdf',
        'en' => 'Quantum-System-Guide-EN.pdf',
    ];

    public function download(string $locale): BinaryFileResponse
    {
        abort_unless(isset(self::GUIDES[$locale]), 404);

        $path = base_path('docs/'.self::GUIDES[$locale]);

        abort_unless(is_file($path), 404);

        return response()->download($path);
    }
}
