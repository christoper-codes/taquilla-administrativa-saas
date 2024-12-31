<?php

namespace Database\Seeders;

use App\Models\CourtesyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtesyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourtesyType::create([
            'stadium_id' => 1,
            'name' => 'soporte y correccion',
            'description' => 'generico',
            'is_active' => true
        ]);

        CourtesyType::create([
            'stadium_id' => 1,
            'name' => 'porra',
            'description' => 'generico',
            'is_active' => true
        ]);

        CourtesyType::create([
            'stadium_id' => 1,
            'name' => 'prensa',
            'description' => 'generico',
            'is_active' => true
        ]);
    }
}
