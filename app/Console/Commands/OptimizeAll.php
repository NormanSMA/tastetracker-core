<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-all';

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
        $this->info('Optimizando Laravel Core...');
        $this->call('optimize');

        $this->info('Optimizando Filament Panels...');
        $this->call('filament:optimize');

        $this->info('¡Inyección de velocidad completada! 🚀');
    }
}
