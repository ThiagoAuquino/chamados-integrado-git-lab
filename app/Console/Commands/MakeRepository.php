<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {module} {name}';
    protected $description = 'Cria uma classe concreta de repositório em app/Infrastructure/Persistence/{Modulo}';

    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name')) . 'Repository';
        $interfaceName = $name . 'Interface';
        $directory = app_path("Infrastructure/Persistence/{$module}");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Repositório {$name} já existe.");
            return Command::FAILURE;
        }

        $modelClass = "App\\Models\\{$module}";
        $modelVar = lcfirst($module);

        $stub = <<<PHP
<?php

namespace App\Infrastructure\Persistence\\{$module};

use App\Domain\\{$module}\Repositories\\{$interfaceName};
use {$modelClass};
use Illuminate\Support\Collection;

class {$name} implements {$interfaceName}
{
    protected \${$modelVar}Model;

    public function __construct({$modelClass} \${$modelVar})
    {
        \$this->{$modelVar}Model = \${$modelVar};
    }

    public function all(): Collection
    {
        return \$this->{$modelVar}Model->all();
    }

    public function find(int \$id): ?object
    {
        return \$this->{$modelVar}Model->find(\$id);
    }

    public function create(array \$data): object
    {
        return \$this->{$modelVar}Model->create(\$data);
    }

    public function update(int \$id, array \$data): bool
    {
        \$item = \$this->find(\$id);
        if (!\$item) {
            return false;
        }
        return \$item->update(\$data);
    }

    public function delete(int \$id): bool
    {
        \$item = \$this->find(\$id);
        if (!\$item) {
            return false;
        }
        return \$item->delete();
    }
}
PHP;

        File::put($path, $stub);

        $this->info("Repositório {$name} criado com sucesso em Infrastructure/Persistence/{$module}.");

        return Command::SUCCESS;
    }
}
