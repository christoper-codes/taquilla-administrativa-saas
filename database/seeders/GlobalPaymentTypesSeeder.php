<?php

namespace Database\Seeders;

use App\Models\GlobalPaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalPaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalPaymentType::create([
            'name' => 'efectivo',
            'description' => 'Pago en efectivo',
            'is_active' => true
        ]);

        GlobalPaymentType::create([
            'name' => 'tarjeta',
            'description' => 'Pago con tarjeta',
            'is_active' => true
        ]);

        GlobalPaymentType::create([
            'name' => 'cortesia',
            'description' => 'pago por cortesia',
            'is_active' => true
        ]);

        GlobalPaymentType::create([
            'name' => 'plazos',
            'description' => 'pago a plazos',
            'is_active' => true
        ]);
    }
}
