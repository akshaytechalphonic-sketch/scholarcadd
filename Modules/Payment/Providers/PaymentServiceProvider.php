<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Payment/Routes/web.php'));
        $this->loadViewsFrom(module_path('Payment/Views'), 'payment');
        $this->loadMigrationsFrom(module_path('Payment/database/migrations'));
        $this->loadRoutesFrom(module_path('Payment/Routes/api.php'));
    }
}
