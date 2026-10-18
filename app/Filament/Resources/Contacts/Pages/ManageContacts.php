<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact;
use Filament\Resources\Pages\ManageRecords;

class ManageContacts extends ManageRecords
{
    protected static string $resource = ContactResource::class;

    public function mount(): void
    {
        parent::mount();

        Contact::query()
            ->whereNull('read_at')
            ->get()
            ->each(function (Contact $appointment) {
                $appointment->update([
                    'read_at' => now(),
                ]);
            });
    }
}
