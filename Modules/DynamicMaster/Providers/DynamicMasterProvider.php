<?php

namespace Modules\DynamicMaster\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class DynamicMasterProvider extends ServiceProvider
{
    public function register()
    {
        // Register anything needed here
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('DynamicMaster/Routes/web.php'));
        $this->loadViewsFrom(module_path('DynamicMaster/Views'), 'dynamicmaster');
        $this->loadMigrationsFrom(module_path('DynamicMaster/database/migrations'));
        $this->loadRoutesFrom(module_path('DynamicMaster/Routes/api.php'));


    }
}
