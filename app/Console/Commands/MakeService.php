<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Cria um Service dentro de app/Infrastructure/Services';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $directory = app_path("Infrastructure/Services");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Service {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Infrastructure\Services;

class {$name}
{
    // Implemente a lógica do serviço aqui
}
PHP;

        File::put($path, $stub);
        $this->info("Service {$name} criado com sucesso em Infrastructure/Services.");
        return Command::SUCCESS;
    }
}
