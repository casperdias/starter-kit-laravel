<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')
            ->withPivot('status');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->where('status', 'ACTIVE')->first();
    }

    public function assignRole(Role $role)
    {
        if (! $this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role, ['status' => $this->roles->isEmpty() ? 'ACTIVE' : 'INACTIVE']);

            return true;
        }

        return false;
    }

    public function revokeRole(Role $role)
    {
        if ($this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->detach($role);

            if ($this->roles()->where('status', 'ACTIVE')->doesntExist()) {
                $firstRole = $this->roles()->first();
                if ($firstRole) {
                    $this->roles()->updateExistingPivot($firstRole->id, ['status' => 'ACTIVE']);
                }
            }

            return true;
        }

        return false;
    }

    public function changeRole(Role $role)
    {
        $this->roles()->updateExistingPivot(
            $this->roles->pluck('id')->toArray(),
            ['status' => 'INACTIVE']
        );

        if ($this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->updateExistingPivot($role->id, ['status' => 'ACTIVE']);

            return true;
        }
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
