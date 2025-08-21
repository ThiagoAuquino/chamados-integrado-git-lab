<?php

namespace App\Console\Commands\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateDomains extends Command
{
    protected $signature = 'migrate:domains';
    protected $description = 'Executa todas as migrations organizadas por domínio';

    public function handle()
    {
        $domainFolders = File::directories(database_path('migrations'));

        foreach ($domainFolders as $folder) {
            $relativePath = str_replace(base_path() . '/', '', $folder);
            $this->line("Migrando: <info>{$relativePath}</info>");
            $this->call('migrate', ['--path' => $relativePath]);
        }

        $this->info("Todas as migrations de domínio foram executadas com sucesso.");
        return Command::SUCCESS;
    }
}
