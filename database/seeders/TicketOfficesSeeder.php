<?php

namespace Database\Seeders;

use App\Models\TicketOffice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketOfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla el nido del halcon',
            'description' => 'taquilla del estadio el nido del halcon',
            'is_active' => true
        ]);
    }
}
