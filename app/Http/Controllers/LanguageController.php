<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, config('app.available_locales'), true)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
