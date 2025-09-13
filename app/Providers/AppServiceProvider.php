<?php

namespace App\Providers;

use App\Models\Auth\Permission;
use App\Models\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        if (Schema::hasTable('permissions')) {
            $permissions = cache()->rememberForever('permissions', function () {
                return Permission::all();
            });

            $permissions->each(function (Permission $permission) {
                Gate::define($permission->name, function (User $user) use ($permission) {
                    return $user->role && $user->role->permissions->contains($permission);
                });
            });
        }
    }
}
