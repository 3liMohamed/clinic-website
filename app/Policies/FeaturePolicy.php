<?php

namespace App\Policies;

use App\Models\Feature;
use App\Models\User;

class FeaturePolicy extends BasePolicy
{
    protected string $permission = 'features';

    public function toggleVisibility(User $user, Feature $feature): bool
    {
        return $this->can($user, 'visibility');
    }

}
