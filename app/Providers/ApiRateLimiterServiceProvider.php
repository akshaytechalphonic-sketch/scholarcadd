<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class ApiRateLimiterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerModuleRoutes();
        $this->registerApiRateLimiter();
    }

    /**
     * Load all module web routes automatically.
     */
    protected function registerModuleRoutes(): void
    {
        $modulesPath = base_path('Modules');

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $webRoutePath = $modulesPath . "/$module/Routes/web.php";
            if (file_exists($webRoutePath)) {
                Route::middleware('web')
                    ->group($webRoutePath);
            }

            $apiRoutePath = $modulesPath . "/$module/Routes/api.php";
            if (file_exists($apiRoutePath)) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($apiRoutePath);
            }
        }
    }

    /**
     * Register the "api" rate limiter.
     */
    protected function registerApiRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
