<?php

namespace App\Policies;

use App\Models\Statistic;
use App\Models\User;

class StatisticPolicy extends BasePolicy
{
    protected string $permission = 'statistics';

    public function toggleVisibility(User $user, Statistic $statistic): bool
    {
        return $this->can($user, 'visibility');
    }

}
