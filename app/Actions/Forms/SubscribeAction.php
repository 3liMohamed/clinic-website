<?php

namespace App\Actions\Forms;

use App\Models\Subscriber;

class SubscribeAction
{
    public function handle(string $email): Subscriber
    {
        return Subscriber::create([
            'email' => $email,
            'subscribed_at' => now(),
        ]);
    }
}
