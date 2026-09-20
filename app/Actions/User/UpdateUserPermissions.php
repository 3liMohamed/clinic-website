<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateUserPermissions
{
    public function handle(User $user, array $permissions): User
    {
        $user->update([
            'permissions' => $permissions,
        ]);
        $user->refresh();

        if (Auth::id() === $user->id) {
            Auth::setUser($user);
        }
        return $user;
    }
}
