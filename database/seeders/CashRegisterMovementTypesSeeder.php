<?php

namespace Database\Seeders;

use App\Models\CashRegisterMovementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegisterMovementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashRegisterMovementType::create([
            'name' => 'venta',
            'description' => 'venta de producto',
            'is_active' => true
        ]);

        CashRegisterMovementType::create([
            'name' => 'cancelacion total de venta',
            'description' => 'cancelacion total de venta',
            'is_active' => true
        ]);

        CashRegisterMovementType::create([
            'name' => 'cancelacion parcial de venta',
            'description' => 'cancelacion parcial de venta',
            'is_active' => true
        ]);

    }
}
