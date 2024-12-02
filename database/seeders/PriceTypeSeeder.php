<?php

namespace Database\Seeders;

use App\Models\PriceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceType::create([
            'name' => 'regular',
            'description' => 'venta regular parae el publico',
            'is_active' => true
        ]);

        PriceType::create([
            'name' => 'cortesia',
            'description' => 'venta de cortesia para el publico',
            'is_active' => true
        ]);

        PriceType::create([
            'name' => 'abonado',
            'description' => 'venta de abonado para al publico',
            'is_active' => true
        ]);
    }
}
