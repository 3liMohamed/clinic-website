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
        return asset("images/{$this->avatar}");
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
        ];
    }


}
