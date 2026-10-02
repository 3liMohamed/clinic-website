<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PagePolicy extends BasePolicy
{
    protected string $permission = 'pages';

    public function create(User $user): bool
    {
        return app()->isLocal();
    }

    public function delete(User $user, Model $model): bool
    {
        return app()->isLocal();
    }
}
