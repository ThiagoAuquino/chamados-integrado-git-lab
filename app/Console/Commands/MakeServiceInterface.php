<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceInterface extends Command
{
    protected $signature = 'make:service-interface {module} {name}';
    protected $description = 'Cria uma interface de serviço em app/Domain/{Modulo}/Services';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name')) . 'Interface';

        $directory = app_path("Domain/{$module}/Services");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Interface {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Domain\\{$module}\Services;

interface {$name}
{
    // Defina os métodos da interface aqui
}
PHP;

        File::put($path, $stub);
        $this->info("Interface {$name} criada com sucesso em Domain/{$module}/Services.");
        return Command::SUCCESS;
    }
}
