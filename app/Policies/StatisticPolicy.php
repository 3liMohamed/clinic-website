<?php

namespace App\Policies;

use App\Models\Statistic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatisticPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('statistics', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('statistics', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'force_delete');
    }

    public function toggleVisibility(User $user, Statistic $statistic): bool
    {
        return $user->hasPermission('statistics', 'visibility');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('statistics', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('statistics', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('statistics', 'restore');
    }
}
