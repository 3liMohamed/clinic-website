<?php

namespace App\Policies;

use App\Models\Translation;
use App\Models\User;

class TranslationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('translations', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Translation $translation): bool
    {
        return $user->hasPermission('translations', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return app()->isLocal();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Translation $translation): bool
    {
        return $user->hasPermission('translations', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Translation $translation): bool
    {
        return app()->isLocal();
    }
}
