<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    protected string $permission = 'users';

    public function permission(User $user, User $model): bool
    {
        return $this->can($user, 'permission');
    }

}
