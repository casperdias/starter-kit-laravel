<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($permission) {
            Cache::forget('permissions');
        });

        static::updated(function ($permission) {
            Cache::forget('permissions');
        });

        static::deleted(function ($permission) {
            Cache::forget('permissions');
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
