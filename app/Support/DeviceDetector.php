<?php

namespace App\Support;

/**
 * Detects the coarse device type from a User-Agent string.
 *
 * This centralises logic that was previously copy-pasted into every
 * controller and service in the legacy code base.
 */
final class DeviceDetector
{
    public const DESKTOP = 'desktop';
    public const MOBILE = 'mobile';
    public const TABLET = 'tablet';
    public const UNKNOWN = 'unknown';

    public static function detect(?string $userAgent): string
    {
        if (blank($userAgent)) {
            return self::UNKNOWN;
        }

        return match (true) {
            str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet') => self::TABLET,
            str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') => self::MOBILE,
            default => self::DESKTOP,
        };
    }
}
