<?php

namespace App\Policies;

use App\Models\AboutUs;
use App\Models\User;

class AboutUsPolicy extends BasePolicy
{
    protected string $permission = 'about_us';

    public function visibility(User $user, AboutUs $aboutUs): bool
    {
        return $this->can($user, 'visibility');
    }

}
