<?php

namespace Database\Seeders;

use App\Models\WalletTransactionStatus;
use Illuminate\Database\Seeder;

class WalletTransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletTransactionStatus::create([
            'name' => 'pendiente',
            'description' => 'transaccion pendiente',
            'color' => '#d97706',
            'is_active' => true
        ]);

        WalletTransactionStatus::create([
            'name' => 'pagado',
            'description' => 'transaccion pagada',
            'color' => '#16a34a',
            'is_active' => true
        ]);

        WalletTransactionStatus::create([
            'name' => 'cancelado',
            'description' => 'transaccion cancelada',
            'color' => '#dc2626',
            'is_active' => true
        ]);

        WalletTransactionStatus::create([
            'name' => 'parcialmente_cancelado',
            'description' => 'transaccion parcialmente cancelada',
            'color' => '#ea580c',
            'is_active' => true
        ]);


    }
}
