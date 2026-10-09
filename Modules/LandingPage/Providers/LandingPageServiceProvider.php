<?php

namespace Modules\LandingPage\Providers;

use Illuminate\Support\ServiceProvider;

class LandingPageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('LandingPage/Routes/web.php'));
        $this->loadViewsFrom(module_path('LandingPage/Views'), 'landingpage');
        $this->loadMigrationsFrom(module_path('LandingPage/database/migrations'));
    }
}