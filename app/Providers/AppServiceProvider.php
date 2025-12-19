<?php

namespace App\Providers;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
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
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        JsonResource::withoutWrapping();

        if (Schema::hasTable('permissions')) {
            Gate::before(function (User $user, string $ability) {
                if ($user->role && $user->role->permissions->contains('name', $ability)) {
                    return true;
                }

                return null;
            });
        }
    }
}
