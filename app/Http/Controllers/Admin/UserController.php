<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\Admin\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(private readonly UserService $users)
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        return view('admin.users.index', ['users' => $this->users->paginate($request)]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->users->create($request->validated());

        return redirect()->route('admin.users.index')->with('success', __('messages.created_successfully'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->users->update($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', __('messages.updated_successfully'));
    }

    /**
     * Clear a user's two-factor setup — an account-recovery action for when
     * they lose both their authenticator device and their recovery codes.
     */
    public function resetTwoFactor(User $user, ActivityService $activity): RedirectResponse
    {
        $this->authorize('update', $user);

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $activity->log('Reset two-factor authentication for '.$user->email, Auth::user());

        return redirect()->route('admin.users.edit', $user)->with('success', __('two-factor.admin_reset_done'));
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->users->delete($user, Auth::user());
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', __('messages.deleted_successfully'));
    }
}
