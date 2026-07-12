<?php

namespace App\Policies;

use App\Models\User;

class ContactPolicy extends ResourcePolicy
{
    protected string $resource = 'contacts';

    public function update(User $user): bool
    {
        return $user->can('update_contacts');
    }
}
