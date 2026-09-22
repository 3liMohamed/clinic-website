<?php

namespace App\Policies;

use App\Models\Slide;
use App\Models\User;

class SlidePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('slides', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Slide $slide): bool
    {
        return $user->hasPermission('slides', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('slides', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Slide $slide): bool
    {
        return $user->hasPermission('slides', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Slide $slide): bool
    {
        return $user->hasPermission('slides', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Slide $slide): bool
    {
        return $user->hasPermission('slides', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Slide $slide): bool
    {
        return $user->hasPermission('slides', 'force_delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('slides', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('slides', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('slides', 'restore');
    }
}
