<?php

namespace Modules\SystemControl\Providers;

use Illuminate\Support\ServiceProvider;

class SystemControlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('SystemControl/Routes/web.php'));
        $this->loadViewsFrom(module_path('SystemControl/Views'), 'systemcontrol');
        $this->loadMigrationsFrom(module_path('SystemControl/database/migrations'));
    }
}