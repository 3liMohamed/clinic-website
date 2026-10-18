<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Filament\Resources\Pages\ManageRecords;

class ManageAppointments extends ManageRecords
{
    protected static string $resource = AppointmentResource::class;

    public function mount(): void
    {
        parent::mount();

        Appointment::query()
            ->whereNull('read_at')
            ->get()
            ->each(function (Appointment $appointment) {
                $appointment->update([
                    'read_at' => now(),
                ]);
            });
    }
}
