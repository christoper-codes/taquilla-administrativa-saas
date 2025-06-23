<?php

namespace Database\Seeders;

use App\Models\WalletAccountType;
use Illuminate\Database\Seeder;

class WalletAccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletAccountType::create([
            'wallet_currency_id' => 1,
            'name' => 'cashless',
            'color' => '#6d28d9',
            'description' => 'moneda electronica cashless de halcones de Xalapa',
            'is_active' => true,
        ]);

        WalletAccountType::create([
            'wallet_currency_id' => 2,
            'name' => 'cashback',
            'color' => '#a21caf',
            'description' => 'moneda cashback de halcones de Xalapa',
            'is_active' => true,
        ]);
    }
}
