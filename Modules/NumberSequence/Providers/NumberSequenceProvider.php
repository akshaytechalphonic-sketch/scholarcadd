<?php

namespace Modules\NumberSequence\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class NumberSequenceProvider extends ServiceProvider
{
    public function register()
    {
        // Register anything needed here
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('NumberSequence/Routes/web.php'));
        $this->loadViewsFrom(module_path('NumberSequence/Views'), 'numbersequence');
        $this->loadMigrationsFrom(module_path('NumberSequence/database/migrations'));

    }
}
