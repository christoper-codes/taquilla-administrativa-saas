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
            'description' => 'cada boleto adquirido tendra un porcentaje de descuento',
            'is_active' => true
        ]);
        PromotionType::create([
            'name' => 'descuento_por_porcentaje_por_compra',
            'description' => 'al total de la compra se le aplicara un descuento',
            'is_active' => true
        ]);
        PromotionType::create([
            'name' => 'descuento_por_compra_multiple',
            'description' => 'cada cantidad de boletos se regalaran cierta cantidad',
            'is_active' => true
        ]);
    }
}
