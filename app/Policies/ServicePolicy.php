<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy extends BasePolicy
{
    protected string $permission = 'services';

    public function toggleHome(User $user, Service $service): bool
    {
        return $this->can($user, 'toggle_home');
    }
}
