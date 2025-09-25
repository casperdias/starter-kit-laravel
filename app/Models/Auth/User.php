<?php

namespace App\Models\Auth;

use App\Models\Content\Chat;
use App\Models\Content\News;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
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

    public function activeRole()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')
            ->wherePivot('status', 'ACTIVE');
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
        if (! $this->roles()->where('role_id', $role->id)->exists()) {
            return false;
        }

        $this->roles()->updateExistingPivot(
            $this->roles->pluck('id')->toArray(),
            ['status' => 'INACTIVE']
        );

        $this->roles()->updateExistingPivot($role->id, ['status' => 'ACTIVE']);

        return true;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function news()
    {
        return $this->hasMany(News::class, 'user_id');
    }

    public function chatsAsSender()
    {
        return $this->hasMany(Chat::class, 'from_id');
    }

    public function chatsAsReceiver()
    {
        return $this->hasMany(Chat::class, 'to_id');
    }

    public function lastChat()
    {
        return $this->hasOne(Chat::class)
            ->where(function ($query) {
                $query->where('from_id', $this->id)
                    ->orWhere('to_id', $this->id);
            })
            ->orderBy('created_at', 'desc')
            ->latest();
    }
}
