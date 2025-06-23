<?php

namespace Database\Seeders;

use App\Models\WalletRechargeAmount;
use Illuminate\Database\Seeder;

class WalletRechargeAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletRechargeAmount::create([
            'amount' => 50,
            'description' => 'recarga de 50 pesos',
            'is_active' => true
        ]);

        WalletRechargeAmount::create([
            'amount' => 100,
            'description' => 'recarga de 100 pesos',
            'is_active' => true
        ]);

        WalletRechargeAmount::create([
            'amount' => 200,
            'description' => 'recarga de 200 pesos',
            'is_active' => true
        ]);

        WalletRechargeAmount::create([
            'amount' => 500,
            'description' => 'recarga de 500 pesos',
            'is_active' => true
        ]);

        WalletRechargeAmount::create([
            'amount' => 1000,
            'description' => 'recarga de 1000 pesos',
            'is_active' => true
        ]);
    }
}
