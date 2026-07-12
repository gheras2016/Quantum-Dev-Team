<?php

namespace App\Policies;

class UserPolicy extends ResourcePolicy
{
    protected string $resource = 'users';
}
