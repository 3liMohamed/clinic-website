<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TranslationPolicy extends BasePolicy
{
    protected string $permission = 'translations';


    public function create(User $user): bool
    {
        return app()->isLocal();
    }

    public function delete(User $user, Model $model): bool
    {
        return app()->isLocal();
    }
}
