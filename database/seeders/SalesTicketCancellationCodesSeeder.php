<?php

namespace Database\Seeders;

use App\Models\SalesTicketCancellationCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesTicketCancellationCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesTicketCancellationCode::create([
            'ticket_office_id' => 2,
            'cancellation_code' => 123456,
            'description' => 'codigo de cancelacion para la taquilla halcones',
            'is_active' => true
        ]);

        SalesTicketCancellationCode::create([
            'ticket_office_id' => 3,
            'cancellation_code' => 654321,
            'description' => 'codigo de cancelacion para la taquilla test 1',
            'is_active' => true
        ]);
    }
}
