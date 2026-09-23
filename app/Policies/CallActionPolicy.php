<?php

namespace App\Policies;

use App\Models\CallAction;
use App\Models\User;

class CallActionPolicy
{
    /**
     * Determine whether the user can view any models.
     */

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('call_actions', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('call_actions', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'force_delete');
    }

    public function toggleVisibility(User $user, CallAction $callAction): bool
    {
        return $user->hasPermission('call_actions', 'visibility');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('call_actions', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('call_actions', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('call_actions', 'restore');
    }
}
