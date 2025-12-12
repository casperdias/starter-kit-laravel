<?php

namespace App\Models\Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * @param  Builder<Role>  $query
     * @return Builder<Role>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('display_name', 'like', "%{$search}%");
        });
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')
            ->withPivot('status');
    }
}
