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
            'description' => 'caja registradora 1',
            'is_active' => true
        ]);

        CashRegisterType::create([
            'name' => '2',
            'description' => 'caja registradora 2',
            'is_active' => true
        ]);

        CashRegisterType::create([
            'name' => '3',
            'description' => 'caja registradora 3',
            'is_active' => true
        ]);
    }
}
