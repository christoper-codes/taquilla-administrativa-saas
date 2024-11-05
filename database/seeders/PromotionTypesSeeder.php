<?php

namespace Database\Seeders;

use App\Models\PromotionType;
use Illuminate\Database\Seeder;

class PromotionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromotionType::create([
            'name' => 'descuento_por_porcentaje_por_boleto',
            'description' => 'descuento_por_porcentaje_por_boleto',
            'is_active' => true
        ]);
        PromotionType::create([
            'name' => 'descuento_por_porcentaje_por_compra',
            'description' => 'descuento_por_porcentaje_por_compra',
            'is_active' => true
        ]);
        PromotionType::create([
            'name' => 'descuento_por_compra_multiple',
            'description' => 'descuento_por_compra_multiple',
            'is_active' => true
        ]);
    }
}
