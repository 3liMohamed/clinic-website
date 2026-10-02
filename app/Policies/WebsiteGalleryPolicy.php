<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WebsiteGalleryPolicy extends BasePolicy
{
    protected string $permission = 'website_galleries';

    public function create(User $user): bool
    {
        return app()->isLocal();
    }

    public function delete(User $user, Model $model): bool
    {
        return app()->isLocal();
    }

}
