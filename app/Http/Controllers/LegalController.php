<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    /** Bump when the legal documents are revised. */
    private const UPDATED_AT = '2026-09-20';

    public function privacy(): View
    {
        return $this->render('privacy');
    }

    public function terms(): View
    {
        return $this->render('terms');
    }

    private function render(string $doc): View
    {
        return view('legal.show', [
            'page' => __('legal.'.$doc),
            'updatedAt' => self::UPDATED_AT,
            'siteName' => __('messages.site_name'),
            'email' => setting('site_email', config('mail.from.address')),
        ]);
    }
}
