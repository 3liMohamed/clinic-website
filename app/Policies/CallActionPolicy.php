<?php

namespace App\Policies;

use App\Models\CallAction;
use App\Models\User;

class CallActionPolicy extends BasePolicy
{
    protected string $permission = 'call_actions';

    public function toggleVisibility(User $user, CallAction $callAction): bool
    {
        return $this->can($user, 'visibility');
    }

}
