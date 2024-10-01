<?php

namespace Database\Seeders;

use App\Models\ZoneType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_a',
            'code' => 'A',
            'capacity' => 1000,
            'rows' => 10,
            'columns' => 10,
            'seats' => 100,
            'vip_seats' => 10,
            'description' => 'zona a del estadio',
            'is_active' => true,
        ]);

        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_b',
            'code' => 'B',
            'capacity' => 2000,
            'rows' => 20,
            'columns' => 20,
            'seats' => 400,
            'vip_seats' => 20,
            'description' => 'zona b del estadio',
            'is_active' => true,
        ]);

        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_c',
            'code' => 'C',
            'capacity' => 3000,
            'rows' => 30,
            'columns' => 30,
            'seats' => 900,
            'vip_seats' => 30,
            'description' => 'zona c del estadio',
            'is_active' => true,
        ]);

        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_e',
            'code' => 'E',
            'capacity' => 5000,
            'rows' => 50,
            'columns' => 50,
            'seats' => 2500,
            'vip_seats' => 50,
            'description' => 'zona e del estadio',
            'is_active' => true,
        ]);

        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_f',
            'code' => 'F',
            'capacity' => 6000,
            'rows' => 60,
            'columns' => 60,
            'seats' => 3600,
            'vip_seats' => 60,
            'description' => 'zona f del estadio',
            'is_active' => true,
        ]);

        ZoneType::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'name' => 'zona_h',
            'code' => 'H',
            'capacity' => 8000,
            'rows' => 80,
            'columns' => 80,
            'seats' => 6400,
            'vip_seats' => 80,
            'description' => 'zona h del estadio',
            'is_active' => true,
        ]);
    }
}
