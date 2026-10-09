<?php

namespace Modules\FAQS\Providers;

use Illuminate\Support\ServiceProvider;

class FAQSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('FAQS/Routes/web.php'));
        $this->loadViewsFrom(module_path('FAQS/Views'), 'faqs');
        $this->loadMigrationsFrom(module_path('FAQS/database/migrations'));
    }
}