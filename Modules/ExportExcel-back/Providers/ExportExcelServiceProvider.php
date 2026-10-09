<?php

namespace Modules\ExportExcel\Providers;

use Illuminate\Support\ServiceProvider;

class ExportExcelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('ExportExcel/Routes/web.php'));
        $this->loadViewsFrom(module_path('ExportExcel/Views'), 'ExportExcel');
        $this->loadMigrationsFrom(module_path('ExportExcel/database/migrations'));
    }
}
