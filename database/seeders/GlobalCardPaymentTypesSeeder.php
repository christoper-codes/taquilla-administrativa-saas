<?php

namespace Database\Seeders;

use App\Models\GlobalCardPaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalCardPaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalCardPaymentType::create([
            'name' => 'credito',
            'description' => 'Pago con tarjeta de credito',
            'is_active' => true
        ]);

        GlobalCardPaymentType::create([
            'name' => 'debito',
            'description' => 'Pago con tarjeta de debito',
            'is_active' => true
        ]);
    }
}
