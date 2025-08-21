<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeControllerWeb extends Command
{
    protected $signature = 'make:controller:web {name}';
    protected $description = 'Cria um Controller dentro de app/Http/Controllers/Web';

    public function handle()
    {
        $name = ucfirst($this->argument('name')) . 'Controller';

        $directory = app_path("Http/Controllers/Web");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Controller Web {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name} extends Controller
{
    // Implemente os métodos do controller aqui
}
PHP;

        File::put($path, $stub);
        $this->info("Controller Web {$name} criado com sucesso em Http/Controllers/Web.");
        return Command::SUCCESS;
    }
}
