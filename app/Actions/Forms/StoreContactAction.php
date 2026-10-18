<?php

namespace App\Actions\Forms;

use App\Models\Contact;

class StoreContactAction
{
    public function handle(array $data): Contact
    {
        return Contact::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);
    }
}
