<?php

namespace Modules\PaymentHistory\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentHistoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('PaymentHistory/Routes/web.php'));
        $this->loadViewsFrom(module_path('PaymentHistory/Views'), 'paymenthistory');
        $this->loadMigrationsFrom(module_path('PaymentHistory/database/migrations'));
    }
}