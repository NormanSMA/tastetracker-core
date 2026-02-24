<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $branch = \App\Models\Branch::create([
            'name' => 'Sucursal Principal',
            'city' => 'Managua',
            'is_active' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin Sistema',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Gerente Sucursal',
            'email' => 'gerente@app.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'branch_id' => $branch->id,
            'is_active' => true,
        ]);
    }
}
