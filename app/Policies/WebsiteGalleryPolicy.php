<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebsiteGallery;
use Illuminate\Auth\Access\Response;

class WebsiteGalleryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('website_galleries', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WebsiteGallery $websiteGallery): bool
    {
        return $user->hasPermission('website_galleries', 'view');
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
    public function update(User $user, WebsiteGallery $websiteGallery): bool
    {
        return $user->hasPermission('website_galleries', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WebsiteGallery $websiteGallery): bool
    {
        return app()->isLocal();
    }

}
