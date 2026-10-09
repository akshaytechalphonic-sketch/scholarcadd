<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->loadModuleRoutes();
    }

    protected function loadModuleRoutes()
    {
        $modulesPath = base_path('Modules');

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $routePath = $modulesPath . "/$module/Routes/web.php";
            if (file_exists($routePath)) {
                Route::middleware('web')
                    ->group($routePath);
            }
        }
    }
}
