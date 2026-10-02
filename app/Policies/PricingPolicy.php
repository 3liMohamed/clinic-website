<?php

namespace App\Policies;

use App\Models\Pricing;
use App\Models\User;

class PricingPolicy extends BasePolicy
{
    protected string $permission = 'pricings';

    public function toggleVisibility(User $user, Pricing $pricing): bool
    {
        return $this->can($user, 'visibility');
    }

}
