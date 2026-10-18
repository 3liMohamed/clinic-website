<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['avatar', 'name', 'email', 'phone', 'password', 'is_active', 'permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar
{
    use SoftDeletes, Notifiable, HasAuditLog;

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'permissions' => 'array'
        ];
    }

    protected static function booted()
    {
        static::creating(function (User $user) {
            $user->permissions = self::getPermissionsSchema();
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {

        return $this->is_active;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return Storage::disk('images')->url($this->avatar ?: 'default.webp');
    }


    public function hasPermission(string $model, string $action): bool
    {
        return (bool) data_get($this->permissions, "{$model}.{$action}", false);
    }

    public static function getPermissionsSchema(): array
    {
        return [
            'audit-logs' => [
                'view' => true,
            ],
            'translations' => [
                'view' => true,
                'edit' => true,
            ],
            'settings' => [
                'view' => true,
                'edit' => true,
            ],
            'pages' => [
                'view' => true,
                'edit' => true,
            ],
            'website_galleries' => [
                'view' => true,
                'edit' => true,
            ],
            'users' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'permission' => true,
            ],
            'slides' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'clinic_infos' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'features' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'statistics' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'why_chooses' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'call_actions' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'categories' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'portfolios' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'services' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'toggle_home' => true,
            ],
            'testimonials' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'departments' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'pricings' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'doctors' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'toggle_home' => true,
                'working_hours' => true,
            ],
            'blogs' => [
                'view_all' => true,
                'view_self' => true,
                'create' => true,
                'edit_all' => true,
                'edit_self' => true,
                'delete_all' => true,
                'delete_self' => true,
                'restore_all' => true,
                'restore_self' => true,
                'force_delete_all' => true,
                'force_delete_self' => true,
                'toggle_home_all' => true,
                'toggle_home_self' => true,
                'publish_all' => true,
                'publish_self' => true,
            ],
            'about_us' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'vision' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'missions' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
                'visibility' => true,
            ],
            'clients' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'faqs' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'restore' => true,
                'force_delete' => true,
            ],
            'subscribers' => [
                'view' => true,
                'delete' => true,
            ],
            'appointments' => [
                'view' => true,
                'delete' => true,
                'set_appointment_date' => true,
            ],
            'contact_us' => [
                'view' => true,
                'delete' => true,
            ],
        ];
    }
}
