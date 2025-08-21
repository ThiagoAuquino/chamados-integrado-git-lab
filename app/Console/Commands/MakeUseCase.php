<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeUseCase extends Command
{
    protected $signature = 'make:usecase {module} {name}';
    protected $description = 'Cria um UseCase dentro de app/Domain/{Modulo}/UseCases';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $directory = app_path("Domain/{$module}/UseCases");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("UseCase {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Domain\\{$module}\UseCases;

class {$name}
{
    public function __construct()
    {
        // Injete dependências aqui
    }

    public function handle()
    {
        // Lógica principal aqui
    }
}
PHP;

        File::put($path, $stub);
        $this->info("UseCase {$name} criado com sucesso em Domain/{$module}/UseCases.");
        return Command::SUCCESS;
    }
}
