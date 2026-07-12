<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the locale stored in the session on every request.
 *
 * The legacy app stored the chosen locale in the session but never read it
 * back, so language switching only affected a single redirect.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        if (in_array($locale, config('app.available_locales', ['ar', 'en']), true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
