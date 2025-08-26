<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\DemandaLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    public function demandaLogs()
    {
        return $this->hasMany(DemandaLog::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function deniedPermissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'denied_permissions',
            'user_id',
            'permission_id'
        );
    }
}
