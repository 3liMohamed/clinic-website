<?php

namespace App\Actions\Forms;

use App\Models\Appointment;

class AppointmentAction
{
    public function handle(array $data): Appointment
    {
        return Appointment::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'doctor_id' => $data['doctor_id'] ?? null,
            'message' => $data['message'] ?? null,
        ]);
    }
}
