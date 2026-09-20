<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Lets an admin manage their own two-factor authentication from the profile
 * page: enrol (enable → confirm), regenerate recovery codes, and disable.
 */
class TwoFactorController extends Controller
{
    public function __construct(
        private readonly TwoFactorService $twoFactor,
        private readonly ActivityService $activity,
    ) {
    }

    /** Begin enrolment: create a secret + recovery codes (not yet confirmed). */
    public function enable(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            $user->forceFill([
                'two_factor_secret' => $this->twoFactor->generateSecret(),
                'two_factor_recovery_codes' => $this->twoFactor->generateRecoveryCodes(),
                'two_factor_confirmed_at' => null,
            ])->save();
        }

        return redirect()->route('admin.profile.edit')->withFragment('two-factor');
    }

    /** Confirm enrolment by verifying a first code from the authenticator app. */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $user = $request->user();

        if (! $user->two_factor_secret || $user->two_factor_confirmed_at) {
            return redirect()->route('admin.profile.edit');
        }

        if (! $this->twoFactor->verify($user->two_factor_secret, $request->input('code'))) {
            return back()->withErrors(['code' => __('two-factor.invalid_code')])->withFragment('two-factor');
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();
        $this->activity->log('Enabled two-factor authentication', $user);

        return redirect()->route('admin.profile.edit')->with('success', __('two-factor.enabled'))->withFragment('two-factor');
    }

    /** Regenerate the one-time recovery codes (only while enabled). */
    public function regenerate(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            $codes = $this->twoFactor->generateRecoveryCodes();
            $user->forceFill(['two_factor_recovery_codes' => $codes])->save();

            return redirect()->route('admin.profile.edit')->with('recovery_codes', $codes)->withFragment('two-factor');
        }

        return redirect()->route('admin.profile.edit');
    }

    /** Disable 2FA (requires the current password). */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->activity->log('Disabled two-factor authentication', $user);

        return redirect()->route('admin.profile.edit')->with('success', __('two-factor.disabled'))->withFragment('two-factor');
    }
}
