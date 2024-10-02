<?php

namespace Database\Seeders;

use App\Models\CashRegisterType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegisterTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashRegisterType::create([
            'name' => '1',
            'description' => 'Caja 1',
            'is_active' => true
        ]);

        CashRegisterType::create([
            'name' => '2',
            'description' => 'Caja 2',
            'is_active' => true
        ]);

        CashRegisterType::create([
            'name' => '3',
            'description' => 'Caja 3',
            'is_active' => true
        ]);
    }
}
