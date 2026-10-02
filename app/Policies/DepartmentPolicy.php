<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy extends BasePolicy
{
    protected string $permission = 'departments';

    public function toggleVisibility(User $user, Department $department): bool
    {
        return $this->can($user, 'visibility');
    }
}
