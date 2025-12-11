<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')
            ->withPivot('status');
    }
}
