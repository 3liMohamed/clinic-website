<?php

namespace App\Filament\Resources\Subscribers\Pages;

use App\Filament\Resources\Subscribers\SubscriberResource;
use App\Models\Subscriber;
use Filament\Resources\Pages\ManageRecords;

class ManageSubscribers extends ManageRecords
{
    protected static string $resource = SubscriberResource::class;

    public function mount(): void
    {
        parent::mount();

        Subscriber::query()
            ->whereNull('read_at')
            ->get()
            ->each(function (Subscriber $subscriber) {
                $subscriber->update([
                    'read_at' => now(),
                ]);
            });
    }
}
