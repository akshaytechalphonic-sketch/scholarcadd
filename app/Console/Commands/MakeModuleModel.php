<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleModel extends Command
{
    protected $signature = 'make:module-model {name} {--module=DynamicMaster}';

    protected $description = 'Create a new model under Modules/{module}/Models';

    public function handle(): void
    {
        $name = $this->argument('name');
        $module = $this->option('module');

        $modelPath = base_path("Modules/{$module}/Models");
        $modelFile = "{$modelPath}/{$name}.php";
        if (!File::exists($modelPath)) {
            File::makeDirectory($modelPath, 0755, true);
        }
        if (File::exists($modelFile)) {
            $this->error("Model {$name} already exists at {$modelFile}");
            return;
        }
        $modelContent = <<<EOT
<?php

namespace Modules\\{$module}\\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;

    protected \$guarded = [''];
}
EOT;

        // Create model file
        File::put($modelFile, $modelContent);
        $this->info("Model {$name} created successfully in Modules/{$module}/Models");
    }
}
