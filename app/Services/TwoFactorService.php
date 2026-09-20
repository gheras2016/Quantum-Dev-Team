<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * Time-based one-time-password (TOTP) helper for admin two-factor auth.
 * Wraps the vetted google2fa engine (same as Laravel Fortify) and renders
 * the enrolment QR as an inline SVG (pure PHP — no imagick/gd needed).
 */
class TwoFactorService
{
    private Google2FA $engine;

    public function __construct()
    {
        $this->engine = new Google2FA();
    }

    /** A fresh base32 secret to bind an authenticator app to. */
    public function generateSecret(): string
    {
        return $this->engine->generateSecretKey();
    }

    /** Verify a 6-digit code (spaces tolerated) against the secret. */
    public function verify(string $secret, string $code): bool
    {
        return (bool) $this->engine->verifyKey($secret, str_replace(' ', '', $code));
    }

    /** The otpauth:// URI encoded into the QR / offered for manual entry. */
    public function otpauthUri(User $user, string $secret): string
    {
        return $this->engine->getQRCodeUrl(
            (string) config('app.name'),
            $user->email,
            $secret
        );
    }

    /** Inline SVG QR code for the given user's secret. */
    public function qrCodeSvg(User $user, string $secret): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200, 1),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($this->otpauthUri($user, $secret));
    }

    /**
     * One-time backup codes for when the authenticator device is unavailable.
     *
     * @return string[]
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        return collect(range(1, $count))
            ->map(fn () => Str::random(10).'-'.Str::random(10))
            ->all();
    }
}
