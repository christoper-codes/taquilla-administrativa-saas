<?php

namespace Database\Seeders;

use App\Models\WalletTransactionType;
use Illuminate\Database\Seeder;

class WalletTransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletTransactionType::create([
            'name' => 'recarga',
            'description' => 'transaccion de recarga',
            'color' => '#16a34a',
            'is_active' => true
        ]);

        WalletTransactionType::create([
            'name' => 'compra',
            'description' => 'transaccion de compra',
            'color' => '#d97706',
            'is_active' => true
        ]);

        WalletTransactionType::create([
            'name' => 'transferencia',
            'description' => 'transaccion de transferencia',
            'color' => '#2563eb',
            'is_active' => true
        ]);

        WalletTransactionType::create([
            'name' => 'cancelacion',
            'description' => 'transaccion de cancelacion',
            'color' => '#dc2626',
            'is_active' => true
        ]);

        WalletTransactionType::create([
            'name' => 'cancelacion_parcial',
            'description' => 'transaccion de cancelacion parcial',
            'color' => '#ea580c',
            'is_active' => true
        ]);

        WalletTransactionType::create([
            'name' => 'devolucion',
            'description' => 'transaccion de devolucion',
            'color' => '#f59e0b',
            'is_active' => true
        ]);

    }
}
