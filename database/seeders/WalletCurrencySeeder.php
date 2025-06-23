<?php

namespace Database\Seeders;

use App\Models\WalletCurrency;
use Illuminate\Database\Seeder;

class WalletCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletCurrency::create([
            'name' => 'cashless_halcones',
            'description' => 'Moneda cashless de halcones de Xalapa',
            'symbol' => 'CLH',
            'image_file' => null,
            'is_active' => true,
        ]);

        WalletCurrency::create([
            'name' => 'cashback_halcones',
            'description' => 'Moneda cashback de halcones de Xalapa',
            'symbol' => 'CBH',
            'image_file' => null,
            'is_active' => true,
        ]);

        WalletCurrency::create([
            'name' => 'peso_mexicano',
            'description' => 'Moneda de pesos mexicanos',
            'symbol' => 'MXN',
            'image_file' => null,
            'is_active' => true,
        ]);
    }
}
