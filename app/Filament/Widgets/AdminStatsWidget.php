<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $activeBranches = Branch::where('is_active', true)->count();
        $inactiveBranches = Branch::where('is_active', false)->count();
        $totalBranches = $activeBranches + $inactiveBranches;

        // Redis status
        $redisStatus = 'Desconectado';
        $redisColor = 'danger';
        try {
            Redis::ping();
            $redisStatus = 'Conectado ✓';
            $redisColor = 'success';
        } catch (\Exception $e) {
            $redisStatus = 'Sin Redis';
            $redisColor = 'warning';
        }

        // Storage usage for user photos
        $photosDisk = Storage::disk('public');
        $photosSize = 0;
        $photosCount = 0;
        if ($photosDisk->exists('user-photos')) {
            $files = $photosDisk->files('user-photos');
            $photosCount = count($files);
            foreach ($files as $file) {
                $photosSize += $photosDisk->size($file);
            }
        }
        $photosSizeMb = round($photosSize / 1024 / 1024, 2);

        return [
            Stat::make('Sucursales', $totalBranches)
                ->description("{$activeBranches} activas · {$inactiveBranches} inactivas")
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color($inactiveBranches > 0 ? 'warning' : 'success')
                ->chart([$activeBranches, $inactiveBranches]),
            Stat::make('Cola de Trabajo (Redis)', $redisStatus)
                ->description('Estado de la conexión')
                ->descriptionIcon('heroicon-o-signal')
                ->color($redisColor),
            Stat::make('Fotos de Perfil', "{$photosCount} archivos")
                ->description("{$photosSizeMb} MB usados")
                ->descriptionIcon('heroicon-o-photo')
                ->color($photosCount > 0 ? 'info' : 'gray'),
        ];
    }
}
