<?php

namespace Modules\Workflow\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WorkflowProvider extends ServiceProvider
{
    public function register()
    {
        // Register anything needed here
    }

    public function boot()
    {        
        $this->loadRoutesFrom(module_path('Workflow/Routes/web.php'));
        $this->loadViewsFrom(module_path('Workflow/Views'), 'workflow');
        $this->loadMigrationsFrom(module_path('Workflow/database/migrations'));

    }
}
