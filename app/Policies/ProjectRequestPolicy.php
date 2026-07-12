<?php

namespace App\Policies;

use App\Models\User;

class ProjectRequestPolicy extends ResourcePolicy
{
    protected string $resource = 'project_requests';

    public function update(User $user): bool
    {
        return $user->can('update_project_requests');
    }
}
