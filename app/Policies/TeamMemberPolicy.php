<?php

namespace App\Policies;

class TeamMemberPolicy extends ResourcePolicy
{
    protected string $resource = 'team';
}
