<?php

namespace Database\Seeders;

use App\Models\SaleTicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleTicketStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleTicketStatus::create([
            'name' => 'pendiente',
            'description' => 'venta pendiente',
            'is_active' => true
        ]);

        SaleTicketStatus::create([
            'name' => 'pagado',
            'description' => 'venta pagada',
            'is_active' => true
        ]);

        SaleTicketStatus::create([
            'name' => 'cancelado',
            'description' => 'venta cancelada',
            'is_active' => true
        ]);

        SaleTicketStatus::create([
            'name' => 'parcialmente cancelado',
            'description' => 'venta parcialmente cancelada',
            'is_active' => true
        ]);

    }
}
