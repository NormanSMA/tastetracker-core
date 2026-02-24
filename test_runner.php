<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\StockManager;

try {
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

    $mozzarella = Ingredient::create([
        'branch_id' => $branch->id,
        'category_id' => $category->id,
        'name' => 'Queso Mozzarella',
        'unit' => 'gramos',
        'is_active' => true,
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

    $pizza = Product::create([
        'branch_id' => $branch->id,
        'name' => 'Pizza Pepperoni',
        'price' => 250,
        'is_active' => true,
    ]);

    $pizza->ingredients()->attach([
        $mozzarella->id => ['quantity' => 200],
        $pepperoni->id => ['quantity' => 50],
    ]);

    $stockManager = new StockManager();
    $stockManager->processSale($pizza, 2);

    echo "Stock final Mozzarella: " . $mozzarella->fresh()->stock_quantity . "\n";
    echo "Deltas: " . $mozzarella->stockDeltas()->count() . "\n";
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    echo "EXCEPTION THROWN:\n";
    echo $e->getMessage() . "\n";
}
