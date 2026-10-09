<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        // $this->loadRoutesFrom(module_path('Course/Routes/web.php'));
        // $this->loadViewsFrom(module_path('Course/Views'), 'Course');
        // $this->loadMigrationsFrom(module_path('Course/database/migrations'));
    }
}
