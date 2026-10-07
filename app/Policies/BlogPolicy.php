<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BlogPolicy extends BasePolicy
{
    protected string $permission = 'blogs';
    protected string $ownerColumn = 'user_id';

    public function viewAny(User $user): bool
    {
        return $this->can($user, 'view_all') || $this->can($user, 'view_self');
    }

    public function view(User $user, Model $model): bool
    {
        return $this->can($user, 'view_all') || ($this->can($user, 'view_self') && $this->isOwner($user, $model));
    }

    public function create(User $user): bool
    {
        return $this->can($user, 'create');
    }

    public function update(User $user, Model $model): bool
    {
        return $this->can($user, 'edit_all') || ($this->can($user, 'edit_self') && $this->isOwner($user, $model));
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->can($user, 'delete_all') || ($this->can($user, 'delete_self') && $this->isOwner($user, $model));
    }

    public function restore(User $user, Model $model): bool
    {
        return $this->can($user, 'restore_all') || ($this->can($user, 'restore_self') && $this->isOwner($user, $model));
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $this->can($user, 'force_delete_all') || ( $this->can($user, 'force_delete_self') && $this->isOwner($user, $model));
    }

    public function toggleHome(User $user, Model $model): bool
    {
        return $this->can($user, 'toggle_home_all') || ( $this->can($user, 'toggle_home_self') && $this->isOwner($user, $model));
    }

    public function publish(User $user, Model $model): bool
    {
        return $this->can($user, 'publish_all') || ( $this->can($user, 'publish_self') && $this->isOwner($user, $model));
    }

    public function deleteAny(User $user): bool
    {
        return $this->can($user, 'delete_all') || $this->can($user, 'delete_self');
    }

    public function restoreAny(User $user): bool
    {
        return $this->can($user, 'restore_all') || $this->can($user, 'restore_self');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $this->can($user, 'force_delete_all') || $this->can($user, 'force_delete_self');
    }

    protected function isOwner(User $user, Model $model): bool
    {
        return $model->{$this->ownerColumn} === $user->id;
    }
}
