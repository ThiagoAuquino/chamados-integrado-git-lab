<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryInterface extends Command
{
    protected $signature = 'make:repository-interface {module} {name}';
    protected $description = 'Cria uma interface de repositório em app/Domain/{Modulo}/Repositories';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name')) . 'RepositoryInterface';
        $directory = app_path("Domain/{$module}/Repositories");
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

namespace App\Domain\\{$module}\Repositories;

use Illuminate\Support\Collection;

interface {$name}
{
    public function all(): Collection;

    public function find(int \$id): ?object;

    public function create(array \$data): object;

    public function update(int \$id, array \$data): bool;

    public function delete(int \$id): bool;
}
PHP;

        File::put($path, $stub);

        $this->info("Interface {$name} criada com sucesso em Domain/{$module}/Repositories.");

        return Command::SUCCESS;
    }
}
