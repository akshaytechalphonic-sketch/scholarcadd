<?php

namespace Modules\Loginer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LoginerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register anything needed here
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('Loginer/Routes/api.php'));
        $this->loadRoutesFrom(module_path('Loginer/Routes/web.php'));
        $this->loadViewsFrom(module_path('Loginer/Views'), 'loginer');
        $this->loadMigrationsFrom(module_path('Loginer/database/migrations'));
         //$this->loadTranslationsFrom(module_path('Loginer', 'Resources/lang'), 'loginer');
         $this->loadTranslationsFrom(module_path('Loginer/Resources/lang'), 'loginer');
    }
}
