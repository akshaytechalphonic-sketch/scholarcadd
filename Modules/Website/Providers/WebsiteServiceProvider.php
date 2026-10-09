<?php

namespace Modules\Website\Providers;

use Illuminate\Support\ServiceProvider;

class WebsiteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Website/Routes/web.php'));
        $this->loadViewsFrom(module_path('Website/Views'), 'website');
        $this->loadMigrationsFrom(module_path('Website/database/migrations'));
    }
}