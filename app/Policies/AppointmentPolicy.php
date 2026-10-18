<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy extends BasePolicy
{
    protected string $permission = 'appointments';

    public function setAppointmentDate(User $user, Appointment $appointment): bool
    {
        return $this->can($user, 'set_appointment_date');
    }
}
