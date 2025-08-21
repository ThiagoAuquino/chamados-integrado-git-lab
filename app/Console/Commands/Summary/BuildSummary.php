<?php

namespace App\Console\Commands\Summary;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BuildSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basePath = base_path();
        $appPath = "$basePath/app";
        $migrationPath = "$basePath/database/migrations";
        $composerFile = "$basePath/composer.json";

        // Lista todos os arquivos PHP recursivamente em uma pasta
        $listarArquivosPorCategoria = function ($pasta) {
            $arquivosPorCategoria = [];

            if (!is_dir($pasta)) return $arquivosPorCategoria;

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($pasta)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relPath = str_replace(base_path() . '/', '', $file->getPathname());

                    // Detecta categoria com base no caminho
                    $categoria = match (true) {
                        str_contains($relPath, '/Http/Controllers/') => 'Controllers',
                        str_contains($relPath, '/Models/') => 'Models',
                        str_contains($relPath, '/UseCases/') => 'UseCases',
                        str_contains($relPath, '/Repositories/') => 'Repositories',
                        str_contains($relPath, '/Services/') => 'Services',
                        default => 'Outros'
                    };

                    $arquivosPorCategoria[$categoria][] = $relPath;
                }
            }

            ksort($arquivosPorCategoria); // ordena as categorias

            return $arquivosPorCategoria;
        };

        $arquivosApp = $listarArquivosPorCategoria($appPath);

        // Migrations
        $migrations = [];
        if (is_dir($migrationPath)) {
            foreach (glob("$migrationPath/*.php") as $file) {
                $migrations[] = str_replace(base_path() . '/', '', $file);
            }
        }

        // Composer e Laravel version
        $composer = file_exists($composerFile) ? json_decode(file_get_contents($composerFile), true) : [];
        $laravelVersion = \Illuminate\Foundation\Application::VERSION;

        // Rotas usando Laravel
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return sprintf(
                "%-8s %-50s %-30s",
                $route->methods()[0],
                $route->uri(),
                $route->getActionName()
            );
        })->implode("\n");

        // Texto de objetivo
        $objetivo = <<<OBJ
## 🎯 Objetivo do Sistema
... [seu texto atual] ...
OBJ;

        // Início do README
        $readme = "# 📝 Documentação do Projeto Laravel\n\n";
        $readme .= "$objetivo\n\n";
        $readme .= "## ⚙️ Versão do Laravel\n`$laravelVersion`\n\n";

        // Adiciona seções por categoria automaticamente
        foreach ($arquivosApp as $categoria => $arquivos) {
            $readme .= "## 📁 $categoria\n";
            foreach ($arquivos as $file) {
                $readme .= "- `$file`\n";
            }
            $readme .= "\n";
        }

        // Migrations
        $readme .= "## 📁 Migrations\n";
        $readme .= empty($migrations) ? "_Nenhuma encontrada_\n" : implode("\n", array_map(fn($m) => "- `$m`", $migrations)) . "\n";

        // Dependências
        $readme .= "\n## 📦 Dependências do Composer:\n";
        foreach (($composer['require'] ?? []) as $dep => $ver) {
            $readme .= "- `$dep`: `$ver`\n";
        }

        // Rotas
        $readme .= "\n## 🛣️ Lista de Rotas Registradas\n```\n$routes\n```\n";

        // Backlog
        $readme .= "\n## 📌 Pendências / Backlog\n";
        $readme .= "- [ ] Adicionar funcionalidades pendentes aqui\n";

        // Rodapé
        $readme .= "\n---\nGerado automaticamente por `php artisan build:summary` em " . now()->format('d/m/Y H:i:s') . "\n";

        // Salva o arquivo
        file_put_contents("$basePath/README.md", $readme);

        $this->info('✅ README.md gerado com sucesso!');
    }
}
