<?php

namespace App\Policies;

use App\Models\Feature;
use App\Models\User;

class FeaturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('features', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Feature $Feature): bool
    {
        return $user->hasPermission('features', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('features', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Feature $Feature): bool
    {
        return $user->hasPermission('features', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Feature $Feature): bool
    {
        return $user->hasPermission('features', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Feature $Feature): bool
    {
        return $user->hasPermission('features', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Feature $Feature): bool
    {
        return $user->hasPermission('features', 'force_delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('features', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('features', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('features', 'restore');
    }
}
