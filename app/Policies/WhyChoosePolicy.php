<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhyChoose;

class WhyChoosePolicy extends BasePolicy
{
    protected string $permission = 'why_chooses';

    public function visibility(User $user, WhyChoose $whyChoose): bool
    {
        return $this->can($user, 'visibility');
    }

}
