<?php

namespace App\Policies;

use App\Models\ClinicInfo;
use App\Models\User;

class ClinicInfoPolicy extends BasePolicy
{
    protected string $permission = 'clinic_infos';

    public function toggleVisibility(User $user, ClinicInfo $clinicInfo): bool
    {
        return $this->can($user, 'visibility');
    }
}
