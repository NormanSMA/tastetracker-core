<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * StockManager — Servicio de descuento automático de inventario (Blueprint 1.3).
 *
 * Procesa la venta de un producto recorriendo su receta (ingredientes)
 * y ejecutando adjustStock() con delta negativo por cada ingrediente.
 */
class StockManager
{
    /**
     * Procesa la venta de un producto, descontando stock de cada ingrediente de su receta.
     *
     * @param  int  $quantity  Cantidad de unidades vendidas del producto.
     */
    public function processSale(Product $product, int $quantity = 1): void
    {
        $product->load('ingredients');

        if ($product->ingredients->isEmpty()) {
            Log::warning("StockManager: Producto '{$product->name}' no tiene receta definida.");

            return;
        }

        DB::transaction(function () use ($product, $quantity) {
            foreach ($product->ingredients as $ingredient) {
                $amountToDeduct = $ingredient->pivot->quantity * $quantity;

                $ingredient->adjustStock(
                    -$amountToDeduct,
                    "Venta de {$quantity}x {$product->name}"
                );
            }
        });

        Log::info("StockManager: Venta procesada — {$quantity}x '{$product->name}', {$product->ingredients->count()} ingredientes descontados.");
    }
}
