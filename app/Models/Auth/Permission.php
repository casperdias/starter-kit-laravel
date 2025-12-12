<?php

namespace App\Models\Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    protected static function booted(): void
    {
        $clearCache = fn () => Cache::forget('permissions');

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    /**
     * @param  Builder<Permission>  $query
     * @return Builder<Permission>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('display_name', 'like', "%{$search}%");
        });
    }

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
