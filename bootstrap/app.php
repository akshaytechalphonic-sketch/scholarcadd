<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\MakeModuleMigration;
use App\Console\Commands\MakeModuleModel;
use App\Console\Commands\MakeModule;
use App\Console\Commands\MakeModuleController;
use App\Http\Middleware\ApiMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\FrameHeadersMiddleware;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Console\Commands\ExpirePromoCodes;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // ✅ load your module API routes here
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'login-check',
            'api/*',
            'api/payments/ccavenue/response',
        ]);

        // ✅ Add middlewares for web routes
        $middleware->appendToGroup('web', [
            LanguageMiddleware::class,
            RedirectIfAuthenticated::class,
        ]);

        // ✅ Add global middleware for all API routes
        $middleware->appendToGroup('api', [
            ApiMiddleware::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Http\Middleware\HandleCors::class, //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        MakeModuleMigration::class,
        MakeModuleModel::class,
        MakeModule::class,
        MakeModuleController::class,
        ExpirePromoCodes::class
    ])
    ->create();

return $app;
