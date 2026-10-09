<?php

namespace Modules\Course\Providers;

use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Course/Routes/api.php'));
        $this->loadRoutesFrom(module_path('Course/Routes/web.php'));
        $this->loadViewsFrom(module_path('Course/Views'), 'Course');
        $this->loadMigrationsFrom(module_path('Course/database/migrations'));
    }
}
