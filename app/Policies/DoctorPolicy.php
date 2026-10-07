<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;


class DoctorPolicy extends BasePolicy
{
    protected string $permission = 'doctors';

    public function toggleHome(User $user, Doctor $doctor): bool
    {
        return $this->can($user, 'toggle_home');
    }

    public function workingHours(User $user, Doctor $doctor): bool
    {
        return $this->can($user, 'working_hours');
    }
}
