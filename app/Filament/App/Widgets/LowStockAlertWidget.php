<?php

namespace App\Filament\App\Widgets;

use App\Models\Ingredient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LowStockAlertWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $totalIngredients = Ingredient::where('is_active', true)->count();

        $lowStock = Ingredient::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'min_stock')
            ->count();

        $healthyStock = $totalIngredients - $lowStock;

        return [
            Stat::make('Alerta de stock', $lowStock)
                ->description('Ingredientes bajo el mínimo')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'success'),
            Stat::make('Stock saludable', $healthyStock)
                ->description('Ingredientes sobre el mínimo')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Total activos', $totalIngredients)
                ->description('Ingredientes registrados')
                ->descriptionIcon('heroicon-o-beaker')
                ->color('info'),
        ];
    }
}
