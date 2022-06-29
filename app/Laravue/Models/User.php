<?php

namespace App\Laravue\Models;

use App\Notifications\UserRegistered;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role[] $roles
 *
 * @method static User create(array $user)
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'oss_username', 'active', 'access_token', 'refresh_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set permissions guard to API by default
     * @var string
     */
    protected $guard_name = 'api';

    protected $raw_password = '';

    public function getRawPassword()
    {
        return $this->raw_password;
    }

    public function setRawPassword($password)
    {
        $this->raw_password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if user has a permission
     * @param String
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if (in_array($permission, $role->permissions->pluck('name')->toArray())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($user) {
            // $admins = User::all()->filter(function($user) {
            //     return $user->hasRole('admin');
            // });

            // Notification::send($admins, new UserRegistered($user));
            Notification::send($user, new UserRegistered($user));
            event(new \App\Events\NotificationEvent());
        });
    }

    public function getAvatarAttribute()
    {
        if($this->attributes['avatar']) {
            if(str_contains($this->attributes['avatar'], 'storage/')) {
                return $this->attributes['avatar'];
            } else {
                return Storage::url($this->attributes['avatar']);
            }
        }

        return null;
    }
}
