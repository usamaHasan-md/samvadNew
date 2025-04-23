<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('manage-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-vendor', function ($user) {
            return in_array($user->role, ['vendor1', 'vendor2', 'vendor3']);
        });

        Gate::define('manage-fieldagent', function ($user) {
            return in_array($user->role, ['fieldagent1', 'fieldagent2', 'fieldagent3']);
        });
    }
}
