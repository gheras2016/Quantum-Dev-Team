<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(TwoFactorService $twoFactor): View
    {
        $user = Auth::user();
        $pending = $user->two_factor_secret && ! $user->two_factor_confirmed_at;

        return view('admin.profile.edit', [
            'user' => $user,
            'twoFactorEnabled' => $user->hasTwoFactorEnabled(),
            'twoFactorPending' => (bool) $pending,
            'twoFactorQr' => $pending ? $twoFactor->qrCodeSvg($user, $user->two_factor_secret) : null,
            'twoFactorSecret' => $pending ? $user->two_factor_secret : null,
            'recoveryCodes' => session('recovery_codes', $pending ? $user->two_factor_recovery_codes : null),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('success', __('messages.profile_updated'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', __('messages.password_updated'));
    }
}
