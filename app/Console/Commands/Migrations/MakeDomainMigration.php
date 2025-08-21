<?php

namespace App\Console\Commands\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainMigration extends Command
{
    protected $signature = 'make:migration:domain 
                            {module} 
                            {name} 
                            {--model : Cria a model junto} 
                            {--factory : Cria a factory para o model} 
                            {--seed : Cria o seeder para o model} 
                            {--controller : Cria o controller para o model} 
                            {--resource : Cria controller resource} 
                            {--all : Cria migration, model, factory, seeder e controller}';

    protected $description = 'Cria uma migration dentro de database/migrations/{Modulo} e opcionalmente model e outros arquivos';

    public function handle()
    {
        $module = ucfirst($this->argument('module')); // ex: Demanda
        $name = Str::snake($this->argument('name'));  // ex: create_demandas_table

        $timestamp = now()->format('Y_m_d_His');
        $filename = "{$timestamp}_{$name}.php";
        $className = Str::studly($name); // ex: CreateDemandasTable

        $directory = database_path("migrations/{$module}");
        $path = "{$directory}/{$filename}";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

$classStub = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$className} extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
}
PHP;

        File::put($path, $classStub);
        $this->info("Migration {$filename} criada com sucesso em database/migrations/{$module}.");

        // Decide se cria a model e outras classes
        $createAll = $this->option('all');

        if ($this->option('model') || $this->option('factory') || $this->option('seed') || $this->option('controller') || $this->option('resource') || $createAll) {

            $modelName = $module;
            $modelNamespace = "{$module}\\{$modelName}";
            $modelPath = app_path("{$module}/{$modelName}.php");

            if (!File::exists($modelPath)) {
                // Cria o diretório do model, caso não exista
                $modelDir = dirname($modelPath);
                if (!File::exists($modelDir)) {
                    File::makeDirectory($modelDir, 0755, true);
                }

                // Monta opções para make:model
                $options = [];

                if ($createAll) {
                    $options['--all'] = true;
                } else {
                    if ($this->option('factory')) {
                        $options['--factory'] = true;
                    }
                    if ($this->option('seed')) {
                        $options['--seed'] = true;
                    }
                    if ($this->option('controller')) {
                        $options['--controller'] = true;
                    }
                    if ($this->option('resource')) {
                        $options['--resource'] = true;
                    }
                }

                $this->call('make:model', array_merge(['name' => $modelNamespace], $options));
                $this->info("Model {$modelNamespace} criada com sucesso.");
            } else {
                $this->warn("Model {$modelNamespace} já existe.");
            }
        }

        return Command::SUCCESS;
    }
}
