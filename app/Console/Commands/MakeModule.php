<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a full module structure under Modules/{name}';

    public function handle(): void
    {
        $moduleName = ucfirst($this->argument('name'));
        $moduleNameLower = strtolower($moduleName);
        $basePath = base_path("Modules/{$moduleName}");

        if (File::exists($basePath)) {
            $this->error("Module {$moduleName} already exists!");
            return;
        }

        // Folder structure
        $folders = [
            "database/factories",
            "database/migrations",
            "database/seeders",
            "Http/Controllers",
            "Http/Helpers",
            "Http/Requests",
            "Http/Traits",
            "Models/scope",
            "Providers",
            "Repositories",
            "Routes",
            "Services",
            "Views",
        ];

        foreach ($folders as $folder) {
            File::makeDirectory("{$basePath}/{$folder}", 0755, true);
        }

        // === 1. Create ServiceProvider ===
        $providerPath = "{$basePath}/Providers/{$moduleName}ServiceProvider.php";
        $providerContent = <<<PHP
<?php

namespace Modules\\{$moduleName}\\Providers;

use Illuminate\Support\ServiceProvider;

class {$moduleName}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings here
    }

    public function boot(): void
    {
        \$this->loadRoutesFrom(module_path('{$moduleName}/Routes/web.php'));
        \$this->loadViewsFrom(module_path('{$moduleName}/Views'), '{$moduleNameLower}');
        \$this->loadMigrationsFrom(module_path('{$moduleName}/database/migrations'));
    }
}
PHP;
        File::put($providerPath, $providerContent);

        // === 2. Create Controller ===
        $controllerName = "{$moduleName}Controller";
        $controllerPath = "{$basePath}/Http/Controllers/{$controllerName}.php";
        $controllerContent = <<<PHP
<?php

namespace Modules\\{$moduleName}\\Http\\Controllers;

use App\Http\Controllers\Controller;
use Modules\\{$moduleName}\\Services\\{$moduleName}Service;
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    protected {$moduleName}Service \$service;

    public function __construct({$moduleName}Service \$service)
    {
        \$this->service = \$service;
    }

    protected function index(Request \$request)
    {
        return \$this->service->index(\$request);
    }
}
PHP;
        File::put($controllerPath, $controllerContent);

        // === 3. Create Service ===
        $servicePath = "{$basePath}/Services/{$moduleName}Service.php";
        $serviceContent = <<<PHP
<?php

namespace Modules\\{$moduleName}\\Services;

use Modules\\{$moduleName}\\Repositories\\{$moduleName}Repository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class {$moduleName}Service extends Controller
{
    protected {$moduleName}Repository \$repository;

    public function __construct({$moduleName}Repository \$repository)
    {
        \$this->repository = \$repository;
    }

    public function index(Request \$request)
    {
        // Add business logic here
        return "Index from service for {$moduleName}";
    }
}
PHP;
        File::put($servicePath, $serviceContent);

        // === 4. Create Repository ===
        $repositoryPath = "{$basePath}/Repositories/{$moduleName}Repository.php";
        $repositoryContent = <<<PHP
<?php

namespace Modules\\{$moduleName}\\Repositories;

use App\Http\Controllers\Controller;

class {$moduleName}Repository extends Controller
{
    // Repository logic here
}
PHP;
        File::put($repositoryPath, $repositoryContent);

        // === 5. Create Routes/web.php ===
        $routesPath = "{$basePath}/Routes/web.php";
        $routesContent = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use Modules\\{$moduleName}\\Http\\Controllers\\{$moduleName}Controller;

Route::prefix(strtolower('{$moduleName}'))->group(function () {
    Route::get('/', [{$moduleName}Controller::class, 'index'])->name('{$moduleNameLower}.index');
});
PHP;
        File::put($routesPath, $routesContent);

        $this->info("Module {$moduleName} structure and starter files created successfully!");
    }
}
