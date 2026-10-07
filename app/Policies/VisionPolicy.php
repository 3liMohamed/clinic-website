<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vision;

class VisionPolicy extends BasePolicy
{
    protected string $permission = 'vision';

    public function toggleVisibility(User $user, Vision $vision): bool
    {
        return $this->can($user, 'visibility');
    }


}
