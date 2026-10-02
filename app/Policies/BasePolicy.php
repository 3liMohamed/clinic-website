<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    protected string $permission;

    protected function can(User $user, string $action): bool
    {
        return $user->hasPermission($this->permission, $action);
    }

    public function viewAny(User $user): bool
    {
        return $this->can($user, 'view');
    }

    public function view(User $user, Model $model): bool
    {
        return $this->can($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->can($user, 'create');
    }

    public function update(User $user, Model $model): bool
    {
        return $this->can($user, 'edit');
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->can($user, 'delete');
    }

    public function restore(User $user, Model $model): bool
    {
        return $this->can($user, 'restore');
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $this->can($user, 'force_delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->can($user, 'delete');
    }

    public function restoreAny(User $user): bool
    {
        return $this->can($user, 'restore');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $this->can($user, 'force_delete');
    }
}
