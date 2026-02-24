<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait HasStockDeltas
 *
 * Motor de Deltas (Blueprint 1.3).
 * Usa operaciones atómicas increment()/decrement() para prevenir race conditions.
 * El stock_quantity NUNCA debe ser editado directamente — solo vía adjustStock().
 */
trait HasStockDeltas
{
    /**
     * Ajusta el stock de forma atómica usando deltas.
     *
     * @param  float  $amount  Positivo para sumar, negativo para restar.
     * @param  string  $reason  Motivo del ajuste (para auditoría).
     */
    public function adjustStock(float $amount, string $reason): void
    {
        DB::transaction(function () use ($amount, $reason) {
            if ($amount >= 0) {
                $this->increment('stock_quantity', $amount);
            } else {
                $this->decrement('stock_quantity', abs($amount));
            }

            \App\Models\StockLog::create([
                'branch_id' => $this->branch_id,
                'ingredient_id' => $this->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'amount' => abs($amount),
                'type' => $amount >= 0 ? 'add' : 'subtract',
                'reason' => $reason,
            ]);

            $this->refresh();
        });
    }
}
