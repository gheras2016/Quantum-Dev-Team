<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Base policy that maps the standard CRUD abilities to spatie permission
 * names of the form "{ability}_{resource}" (e.g. "view_projects").
 *
 * Concrete policies only need to declare their $resource key, removing the
 * repetitive per-model policy boilerplate of the legacy code base.
 */
abstract class ResourcePolicy
{
    use HandlesAuthorization;

    /** The permission suffix, e.g. "projects", "services". */
    protected string $resource = '';

    public function viewAny(User $user): bool
    {
        return $user->can("view_{$this->resource}");
    }

    public function view(User $user): bool
    {
        return $user->can("view_{$this->resource}");
    }

    public function create(User $user): bool
    {
        return $user->can("create_{$this->resource}");
    }

    public function update(User $user): bool
    {
        return $user->can("edit_{$this->resource}");
    }

    public function delete(User $user): bool
    {
        return $user->can("delete_{$this->resource}");
    }
}
