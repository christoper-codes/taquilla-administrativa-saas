<?php

namespace Database\Seeders;

use App\Models\WalletExchangeRate;
use Illuminate\Database\Seeder;

class WalletExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletExchangeRate::create([
            'from_wallet_currency_id' => 1,
            'to_wallet_currency_id' => 3,
            'rate' => 1.0,
            'is_active' => true,
        ]);

        WalletExchangeRate::create([
            'from_wallet_currency_id' => 2,
            'to_wallet_currency_id' => 3,
            'rate' => 1.0,
            'is_active' => true,
        ]);
    }
}
