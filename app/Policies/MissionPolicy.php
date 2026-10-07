<?php

namespace App\Policies;

use App\Models\Mission;
use App\Models\User;

class MissionPolicy extends BasePolicy
{
    protected string $permission = 'missions';

    public function visibility(User $user, Mission $mission): bool
    {
        return $this->can($user, 'visibility');
    }
}
