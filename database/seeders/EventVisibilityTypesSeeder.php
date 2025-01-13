<?php

namespace Database\Seeders;

use App\Models\EventVisibilityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventVisibilityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventVisibilityType::create([
            'name' => 'publico',
            'description' => 'Evento visible para todo el publico',
            'is_active' => true,
        ]);

        EventVisibilityType::create([
            'name' => 'vendedores',
            'description' => 'Evento visible solo para vendedores y administradores',
            'is_active' => true,
        ]);

        EventVisibilityType::create([
            'name' => 'administradores',
            'description' => 'Evento visible solo para administradores',
            'is_active' => true,
        ]);

    }
}
