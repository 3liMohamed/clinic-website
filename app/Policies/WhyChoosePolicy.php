<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhyChoose;

class WhyChoosePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('why_chooses', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('why_chooses', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'force_delete');
    }

    public function toggleVisibility(User $user, WhyChoose $whyChoose): bool
    {
        return $user->hasPermission('why_chooses', 'visibility');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('why_chooses', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('why_chooses', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('why_chooses', 'restore');
    }
}
