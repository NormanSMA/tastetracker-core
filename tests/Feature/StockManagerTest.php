<?php

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\StockManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deducts stock correctly according to product recipe', function () {
    try {
        // 1. Crear entorno y dependencias
        $branch = Branch::create([
            'name' => 'Sucursal Central',
            'city' => 'Managua',
            'is_active' => true,
        ]);

        $category = Category::create([
            'branch_id' => $branch->id,
            'name' => 'Lácteos',
            'is_active' => true,
        ]);

        // 2. Crear ingredientes con stock inicial
        $mozzarella = Ingredient::create([
            'branch_id' => $branch->id,
            'category_id' => $category->id,
            'name' => 'Queso Mozzarella',
            'unit' => 'gramos',
            'is_active' => true,
            // stock_quantity is not fillable, so we adjust it after creation
        ]);
        $mozzarella->adjustStock(1000, 'Inventario Inicial');

        $pepperoni = Ingredient::create([
            'branch_id' => $branch->id,
            'category_id' => $category->id,
            'name' => 'Pepperoni',
            'unit' => 'gramos',
            'is_active' => true,
        ]);
        $pepperoni->adjustStock(500, 'Inventario Inicial');

        // 3. Crear producto (Pizza) y definir receta (pivot)
        $pizza = Product::create([
            'branch_id' => $branch->id,
            'name' => 'Pizza Pepperoni',
            'price' => 250,
            'is_active' => true,
        ]);

        // Receta: 200g mozzarella, 50g pepperoni por pizza
        $pizza->ingredients()->attach([
            $mozzarella->id => ['quantity' => 200],
            $pepperoni->id => ['quantity' => 50],
        ]);

        // 4. Ejecutar el servicio simulando la venta de 2 pizzas
        $stockManager = new StockManager();
        $stockManager->processSale($pizza, 2);

        // 5. Verificar (Assert) que los stocks se actualizaron correctamente en la base de datos
        // Mozzarella: 1000 - (200 * 2) = 600
        expect($mozzarella->fresh()->stock_quantity)->toEqual(600);

        // Pepperoni: 500 - (50 * 2) = 400
        expect($pepperoni->fresh()->stock_quantity)->toEqual(400);

    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('TEST ERROR: ' . $e->getMessage());
        throw $e;
    }
});
