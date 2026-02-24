<?php

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Stock Delta Precision Tests (Blueprint 1.3 / 4.1)
 *
 * Valida que las operaciones de ajuste de stock mantienen
 * precisión decimal y son seguras ante concurrencia.
 */
beforeEach(function () {
    $this->branch = Branch::create([
        'name' => 'Sucursal Test',
        'is_active' => true,
    ]);

    $this->category = Category::create([
        'branch_id' => $this->branch->id,
        'name' => 'Categoría Test',
        'is_active' => true,
    ]);
});

it('ajusta stock con precisión decimal exacta', function () {
    $ingredient = Ingredient::create([
        'branch_id' => $this->branch->id,
        'category_id' => $this->category->id,
        'name' => 'Queso Mozzarella',
        'unit' => 'gramos',
        'min_stock' => 5.00,
        'is_active' => true,
    ]);

    // Establecer stock inicial
    $ingredient->adjustStock(10.00, 'Stock inicial');

    expect((float) $ingredient->stock_quantity)->toBe(10.00);

    // Restar 2.50 y validar resultado exacto
    $ingredient->adjustStock(-2.50, 'Uso en producción');

    expect((float) $ingredient->stock_quantity)->toBe(7.50);
});

it('maneja múltiples ajustes secuenciales sin pérdida de precisión', function () {
    $ingredient = Ingredient::create([
        'branch_id' => $this->branch->id,
        'category_id' => $this->category->id,
        'name' => 'Harina',
        'unit' => 'gramos',
        'min_stock' => 100.00,
        'is_active' => true,
    ]);

    $ingredient->adjustStock(100.00, 'Compra proveedor');
    $ingredient->adjustStock(-33.33, 'Producción lote A');
    $ingredient->adjustStock(-33.33, 'Producción lote B');
    $ingredient->adjustStock(-33.34, 'Producción lote C');

    expect((float) $ingredient->stock_quantity)->toBe(0.00);
});

it('permite stock en cero pero no negativos conceptualmente', function () {
    $ingredient = Ingredient::create([
        'branch_id' => $this->branch->id,
        'category_id' => $this->category->id,
        'name' => 'Leche',
        'unit' => 'ml',
        'min_stock' => 0,
        'is_active' => true,
    ]);

    $ingredient->adjustStock(5.00, 'Stock inicial');
    $ingredient->adjustStock(-5.00, 'Agotado');

    expect((float) $ingredient->stock_quantity)->toBe(0.00);
});

it('usa operaciones atómicas que previenen race conditions', function () {
    $ingredient = Ingredient::create([
        'branch_id' => $this->branch->id,
        'category_id' => $this->category->id,
        'name' => 'Aceite',
        'unit' => 'ml',
        'min_stock' => 0,
        'is_active' => true,
    ]);

    $ingredient->adjustStock(100.00, 'Stock inicial');

    // Simular concurrencia: dos ajustes desde instancias distintas
    $ingredientA = Ingredient::find($ingredient->id);
    $ingredientB = Ingredient::find($ingredient->id);

    $ingredientA->adjustStock(-10.00, 'Ajuste A');
    $ingredientB->adjustStock(-20.00, 'Ajuste B');

    // Recargar desde DB — debe reflejar ambos ajustes atómicos
    $ingredient->refresh();

    expect((float) $ingredient->stock_quantity)->toBe(70.00);
});
