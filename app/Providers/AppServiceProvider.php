<?php

namespace App\Providers;

use App\Models\User\Permission;
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

            $permissions->each(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->role->permissions->contains($permission);
                });
            });
        }
    }
}
