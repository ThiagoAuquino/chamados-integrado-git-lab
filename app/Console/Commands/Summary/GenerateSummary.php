<?php

namespace App\Console\Commands\Summary;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function Illuminate\Log\log;

class GenerateSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:summary';

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

        // Classificador por tipo baseado no caminho
        $classificarArquivoPorTipo = function (string $path): string {
            return match (true) {
                str_contains($path, '/Http/Controllers/') => 'Controllers',
                str_contains($path, '/Http/Requests/') => 'Requests',
                str_contains($path, '/Models/') => 'Models',
                str_contains($path, '/UseCases/') => 'UseCases',
                str_contains($path, '/Entities/') => 'Entities',
                str_contains($path, '/Repositories/') => 'Repositories',
                str_contains($path, '/DTOs/') => 'DTOs',
                str_contains($path, '/Policies/') => 'Policies',
                str_contains($path, '/Services/') => 'Services',
                str_contains($path, '/Console/Commands/') => 'Commands',
                str_contains($path, '/Providers/') => 'Providers',
                str_contains($path, '/Infrastructure/') => 'Infrastructure',
                default => 'Outros',
            };
        };

        // Escanear recursivamente arquivos do app
        $arquivosPorCategoria = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($appPath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relPath = str_replace($basePath . '/', '', $file->getPathname());
                $categoria = $classificarArquivoPorTipo($relPath);
                $arquivosPorCategoria[$categoria][] = $relPath;
            }
        }

        ksort($arquivosPorCategoria);

        // Migrations
        $migrations = [];
        if (is_dir($migrationPath)) {
            foreach (glob("$migrationPath/*.php") as $file) {
                $migrations[] = str_replace($basePath . '/', '', $file);
            }
        }

        // Arquivos de rota
        // Rotas customizadas baseadas nos módulos definidos
        $rotasCustomizadas = [];
        $modules = ['demanda', 'usuario', 'status', 'notificacao', 'lembrete'];

        foreach ($modules as $module) {
            $path = base_path("routes/api/{$module}/{$module}.php");
            Log::info($path);
            if (file_exists($path)) {
                $rotasCustomizadas[] = "routes/api/{$module}/{$module}.php";
            }
        }

        // Carrega rotas dos módulos manualmente para comandos

        foreach ($modules as $module) {
            $path = base_path("routes/api/{$module}/{$module}.php");

            if (file_exists($path)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($path);
            }
        }

        // Composer e Laravel
        $composer = file_exists($composerFile) ? json_decode(file_get_contents($composerFile), true) : [];
        $laravelVersion = \Illuminate\Foundation\Application::VERSION;

        // Rotas (lista completa)
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
            return sprintf(
                "%-8s %-50s %-30s",
                $route->methods()[0],
                $route->uri(),
                $route->getActionName()
            );
        })->implode("\n");


    // Bloco objetivo
    $objetivo = <<<OBJ
## 🎯 Objetivo do Sistema

Quero criar esse sistema para organizar tarefas do time ou projeto, com campos para responsáveis, prazos e status.  
Ele deve permitir:

- ✅ Alterar a prioridade das tarefas manualmente, como arrastando linhas (drag-and-drop) em uma lista.
- ⏰ Me avisar automaticamente quando as tarefas estiverem próximas de se atrasar.
- 📆 Gerar um cronograma, onde posso atribuir um responsável a cada tarefa.
- 📲 Enviar notificações por e-mail ou WhatsApp ao responsável quando a tarefa for criada ou alterada.
- 🔔 Atuar como um sistema de lembretes configurável, que envia notificações repetidas até que a tarefa seja concluída.

### 📌 O que é uma "Demanda"

No meu caso, uma “demanda” é uma tarefa atribuída a um desenvolvedor, que pode vir de:

- Um chamado do CRM feito pelo cliente
- Um job do GitLab que está sendo executado

O objetivo é que o sistema centralize a visão dessas tarefas em andamento, com:

- 👨‍💻 Quem está executando
- ⏱️ Data de entrada, previsão, entrega
- 🔁 Possibilidade do dev estar atuando em mais de uma tarefa
- 🧩 Tipo (melhoria, bug, novo recurso)
- 📊 Priorização visual (ex: Verde, Amarelo, Laranja, Vermelho)
- 📚 Histórico de troca de prioridades

Ou seja, uma demanda é uma tarefa de desenvolvimento rastreável, que conecta os dois sistemas atuais e melhora o controle interno.
OBJ;

        // Montar README
        $readme = "# 📝 Documentação do Projeto Laravel\n\n";
        $readme .= "$objetivo\n\n";
        $readme .= "## ⚙️ Versão do Laravel\n`$laravelVersion`\n\n";

        foreach ($arquivosPorCategoria as $categoria => $arquivos) {
            $readme .= "## 📁 $categoria\n";
            foreach ($arquivos as $file) {
                $readme .= "- `$file`\n";
            }
            $readme .= "\n";
        }

        $readme .= "## 📁 Migrations\n";
        $readme .= empty($migrations) ? "_Nenhuma encontrada_\n" : implode("\n", array_map(fn($m) => "- `$m`", $migrations)) . "\n";

        $readme .= "\n## 📁 Arquivos de Rotas Customizadas\n";
        $readme .= empty($rotasCustomizadas) ? "_Nenhum encontrado_\n" : implode("\n", array_map(fn($r) => "- `$r`", $rotasCustomizadas)) . "\n";

        $readme .= "\n## 📦 Dependências do Composer:\n";
        foreach (($composer['require'] ?? []) as $dep => $ver) {
            $readme .= "- `$dep`: `$ver`\n";
        }

        $readme .= "\n## 🛣️ Lista de Rotas Registradas\n```\n$routes\n```\n";

        $readme .= "\n## 📌 Pendências / Backlog\n";
        $readme .= "- [ ] Adicionar funcionalidades pendentes aqui\n";

        $readme .= "\n---\nGerado automaticamente por `php artisan generate:summary` em " . now()->format('d/m/Y H:i:s') . "\n";

        file_put_contents("$basePath/README.md", $readme);

        $this->info('✅ README.md gerado com sucesso!');
    }
}
