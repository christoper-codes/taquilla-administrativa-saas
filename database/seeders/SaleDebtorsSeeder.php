<?php

namespace Database\Seeders;

use App\Models\SaleDebtor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleDebtorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleDebtor::create([
            'stadium_id' => 1,
            'first_name' => 'nuevo',
            'last_name' => 'deudor',
            'phone_number' => '00000',
            'email' => 'test@gmail.com',
            'is_active' => true,
        ]);
    }
}
