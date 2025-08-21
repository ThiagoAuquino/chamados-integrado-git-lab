<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEntity extends Command
{
    protected $signature = 'make:entity {module} {name}';
    protected $description = 'Cria uma Entity dentro de app/Domain/{Modulo}/Entities';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $directory = app_path("Domain/{$module}/Entities");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Entity {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Domain\\{$module}\Entities;

class {$name}
{
    // Defina as propriedades da entidade aqui

    public function __construct(
        // Adicione os atributos no construtor
    ) {}
}
PHP;

        File::put($path, $stub);
        $this->info("Entity {$name} criada com sucesso em Domain/{$module}/Entities.");
        return Command::SUCCESS;
    }
}
