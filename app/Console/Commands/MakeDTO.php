<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDTO extends Command
{
    protected $signature = 'make:dto {module} {name}';
    protected $description = 'Cria um DTO dentro de app/Domain/{Modulo}/DTOs';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $directory = app_path("Domain/{$module}/DTOs");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("DTO {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Domain\\{$module}\DTOs;

class {$name}
{
    public function __construct(
        // Adicione os atributos aqui
    ) {}

    public static function fromArray(array \$data): self
    {
        return new self(
            // mapeie os dados aqui
        );
    }

    public function toArray(): array
    {
        return [
            // converta os dados aqui
        ];
    }
}
PHP;

        File::put($path, $stub);
        $this->info("DTO {$name} criado com sucesso em Domain/{$module}/DTOs.");
        return Command::SUCCESS;
    }
}
