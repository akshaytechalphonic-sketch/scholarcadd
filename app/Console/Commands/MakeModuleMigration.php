<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleMigration extends Command
{
    protected $signature = 'make:module-migration {name?} {module?}';
    protected $description = 'Create a migration (new table or modify existing one) inside a module folder with full structure';

    public function handle(): void
    {
        // Ask for migration name if not given
        $name = $this->argument('name') ?? $this->ask('Enter the migration name (e.g. create_users_table or add_status_to_users_table)');
        $name = Str::snake(trim($name));

        // Ask for module name if not given
        $module = $this->argument('module') ?? $this->ask('Enter the module name (e.g. User, Blog, Order)');
        $module = trim($module);

        $timestamp = now()->format('Y_m_d_His');
        $className = Str::studly($name);
        $fileName = "{$timestamp}_{$name}.php";

        $migrationPath = base_path("Modules/{$module}/database/migrations");

        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }

        $filePath = "{$migrationPath}/{$fileName}";

        if (file_exists($filePath)) {
            $this->error("Migration already exists at {$filePath}");
            return;
        }

        // Determine migration type
        if (Str::startsWith($name, 'create_') && Str::endsWith($name, '_table')) {
            $table = Str::between($name, 'create_', '_table');
            $stub = $this->buildCreateStub($className, $table);
        } elseif (Str::startsWith($name, 'add_') && Str::contains($name, '_to_') && Str::endsWith($name, '_table')) {
            $columns = Str::between($name, 'add_', '_to_');
            $table = Str::before(Str::after($name, '_to_'), '_table');
            $stub = $this->buildAddColumnStub($className, $table, $columns);
        } else {
            $this->error("Invalid migration name format. Use create_*_table or add_*_to_*_table.");
            return;
        }

        file_put_contents($filePath, $stub);
        $this->info("✅ Migration created: Modules/{$module}/database/migrations/{$fileName}");
    }

    protected function buildCreateStub(string $className, string $table): string
    {
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('$table', function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$table');
    }
};
PHP;
    }

    protected function buildAddColumnStub(string $className, string $table, string $columns): string
    {
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            // Example: \$table->string('$columns')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            // Example: \$table->dropColumn('$columns');
        });
      }
};
PHP;
    }
}
