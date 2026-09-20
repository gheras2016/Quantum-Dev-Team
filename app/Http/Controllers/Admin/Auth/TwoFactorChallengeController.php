<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * The second step of admin login: after the password is accepted, a user with
 * 2FA enabled is held here (unauthenticated) until they prove the second
 * factor — a TOTP code or a one-time recovery code.
 */
class TwoFactorChallengeController extends Controller
{
    public function __construct(private readonly TwoFactorService $twoFactor)
    {
    }

    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth.2fa.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor-challenge');
    }

    public function store(Request $request, ActivityService $activity): RedirectResponse
    {
        $id = $request->session()->get('auth.2fa.id');

        if (! $id) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $user = User::find($id);

        if (! $user || ! $user->hasTwoFactorEnabled()) {
            $request->session()->forget(['auth.2fa.id', 'auth.2fa.remember']);

            return redirect()->route('admin.login');
        }

        $verified = match (true) {
            filled($request->input('code')) => $this->twoFactor->verify($user->two_factor_secret, $request->input('code')),
            filled($request->input('recovery_code')) => $this->consumeRecoveryCode($user, trim($request->input('recovery_code'))),
            default => false,
        };

        if (! $verified) {
            return back()->withErrors(['code' => __('two-factor.invalid_code')]);
        }

        $remember = (bool) $request->session()->get('auth.2fa.remember', false);
        $request->session()->forget(['auth.2fa.id', 'auth.2fa.remember']);

        Auth::loginUsingId($user->id, $remember);
        $request->session()->regenerate();
        $activity->log('Admin login (2FA)', $user);

        return redirect()->intended(route('admin.dashboard'));
    }

    /** Match and consume (remove) a one-time recovery code. */
    private function consumeRecoveryCode(User $user, string $code): bool
    {
        $codes = $user->two_factor_recovery_codes ?? [];

        if (! in_array($code, $codes, true)) {
            return false;
        }

        $user->forceFill([
            'two_factor_recovery_codes' => array_values(array_filter($codes, fn ($c) => $c !== $code)),
        ])->save();

        return true;
    }
}
