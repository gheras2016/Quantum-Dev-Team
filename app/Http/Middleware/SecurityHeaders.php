<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds a conservative set of security response headers to every response.
 * Deliberately excludes a strict Content-Security-Policy (which needs careful
 * per-app tuning to avoid breaking inline Alpine/Vite) — that can be layered
 * on later in report-only mode first.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'browsing-topics=(), camera=(), microphone=(), geolocation=()',
            // Modern guidance: disable the legacy, buggy XSS auditor rather than enable it.
            'X-XSS-Protection' => '0',
        ];

        // Only advertise HSTS over genuine HTTPS (Railway terminates TLS at the edge;
        // TrustProxies makes $request->secure() reflect the original scheme).
        if ($request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=15552000';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
