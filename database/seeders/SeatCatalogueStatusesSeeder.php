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
            'name' => 'disponible',
            'description' => 'Asiento disponible',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'reservado',
            'description' => 'Asiento reservado',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'vendido',
            'description' => 'Asiento vendido',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'inhabilitado',
            'description' => 'Asiento inhabilitado',
            'is_active' => true
        ]);

        SeatCatalogueStatus::create([
            'name' => 'transito',
            'description' => 'Asiento en transito',
            'is_active' => true
        ]);
    }
}
