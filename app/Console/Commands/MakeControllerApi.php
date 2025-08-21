<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeControllerApi extends Command
{
    protected $signature = 'make:controller:api {name}';
    protected $description = 'Cria um Controller dentro de app/Http/Controllers/Api';

    public function handle()
    {
        $name = ucfirst($this->argument('name')) . 'Controller';

        $directory = app_path("Http/Controllers/Api");
        $path = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error("Controller API {$name} já existe.");
            return Command::FAILURE;
        }

        $stub = <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name} extends Controller
{
    // Implemente os métodos do controller aqui
}
PHP;

        File::put($path, $stub);
        $this->info("Controller API {$name} criado com sucesso em Http/Controllers/Api.");
        return Command::SUCCESS;
    }
}
