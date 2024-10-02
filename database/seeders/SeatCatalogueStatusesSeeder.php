<?php

namespace Database\Seeders;

use App\Models\SeatCatalogueStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatCatalogueStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeatCatalogueStatus::create([
            'name' => 'Disponible',
            'description' => 'Asiento disponible',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'Reservado',
            'description' => 'Asiento reservado',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'Vendido',
            'description' => 'Asiento vendido',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'Inhabilitado',
            'description' => 'Asiento inhabilitado',
            'is_active' => true
        ]);
    }
}
