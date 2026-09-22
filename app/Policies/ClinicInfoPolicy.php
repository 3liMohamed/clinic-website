<?php

namespace App\Policies;

use App\Models\ClinicInfo;
use App\Models\User;

class ClinicInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('clinic_infos', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClinicInfo $clinicInfo): bool
    {
        return $user->hasPermission('clinic_infos', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('clinic_infos', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClinicInfo $clinicInfo): bool
    {
        return $user->hasPermission('clinic_infos', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClinicInfo $clinicInfo): bool
    {
        return $user->hasPermission('clinic_infos', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClinicInfo $clinicInfo): bool
    {
        return $user->hasPermission('clinic_infos', 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClinicInfo $clinicInfo): bool
    {
        return $user->hasPermission('clinic_infos', 'force_delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('clinic_infos', 'delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermission('clinic_infos', 'force_delete');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasPermission('clinic_infos', 'restore');
    }
}
