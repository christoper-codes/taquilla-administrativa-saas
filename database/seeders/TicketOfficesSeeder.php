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
        $ticket_office1 =  TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla el nido del halcon',
            'description' => 'taquilla del estadio el nido del halcon en la entrada principal',
            'is_active' => true
        ]);

        $ticket_office2 = TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla test 1',
            'description' => 'taquilla de prueba 1, para testear el sistema y pruebas de integracion',
            'is_active' => true
        ]);

        /*
        * Relation ships with cash register types (many to many)
        */
        $ticket_office1->cashRegisterTypes()->attach([
            1 => ['is_active' => true],
            2 => ['is_active' => true],
            3 => ['is_active' => true]
        ]);

        $ticket_office2->cashRegisterTypes()->attach([
            1 => ['is_active' => true],
            2 => ['is_active' => true],
            3 => ['is_active' => true]
        ]);
    }
}
