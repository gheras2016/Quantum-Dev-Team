<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    use FiltersResources;

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = User::with('roles')->latest();

        $this->applySearch($query, $request, ['name', 'email']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        $user->syncRoles($data['roles'] ?? []);

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function delete(User $user, User $actor): void
    {
        if ($user->is($actor)) {
            throw ValidationException::withMessages(['user' => __('messages.cannot_delete_self')]);
        }

        if ($user->hasRole('super_admin') && User::role('super_admin')->count() <= 1) {
            throw ValidationException::withMessages(['user' => __('messages.cannot_delete_last_admin')]);
        }

        $user->delete();
    }
}
