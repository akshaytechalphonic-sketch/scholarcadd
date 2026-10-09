<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleController extends Command
{
    protected $signature = 'make:modulecontroller {module} {name}';
    protected $description = 'Create a controller for a specific module in Modules/{module}/Http/Controllers';

    public function handle(): void
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $controllerName = "{$name}Controller";
        $serviceName = "{$name}Service";

        $modulePath = base_path("Modules/{$module}");
        $controllerPath = "{$modulePath}/Http/Controllers/{$controllerName}.php";
        $namespace = "Modules\\{$module}\\Http\\Controllers";
        $serviceNamespace = "Modules\\{$module}\\Services\\{$serviceName}";

        if (!File::exists("{$modulePath}/Http/Controllers")) {
            File::makeDirectory("{$modulePath}/Http/Controllers", 0755, true);
        }

        if (File::exists($controllerPath)) {
            $this->error("Controller {$controllerName} already exists in module {$module}.");
            return;
        }

        $content = <<<PHP
<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
use {$serviceNamespace};
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    protected {$serviceName} \$service;

    public function __construct({$serviceName} \$service)
    {
        \$this->service = \$service;
    }

    public function index(Request \$request)
    {
        return \$this->service->index(\$request);
    }
}
PHP;

        File::put($controllerPath, $content);
        $this->info("Controller {$controllerName} created in module {$module}.");
    }
}
