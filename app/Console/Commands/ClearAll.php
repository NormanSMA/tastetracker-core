<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-all';

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
        $this->info('Limpiando caché de Laravel...');
        $this->call('optimize:clear');

        $this->info('Limpiando caché de Filament...');
        $this->call('filament:optimize-clear');

        $this->info('Limpiando logs temporales...');
        $this->call('log:clear');

        $this->info('¡Limpieza profunda completada! ✨');
    }
}
