<?php

namespace Modules\Enrolled\Providers;

use Illuminate\Support\ServiceProvider;

class EnrolledServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Enrolled/Routes/web.php'));
        $this->loadViewsFrom(module_path('Enrolled/Views'), 'enrolled');
        $this->loadMigrationsFrom(module_path('Enrolled/database/migrations'));
    }
}