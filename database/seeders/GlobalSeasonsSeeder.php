<?php

namespace Database\Seeders;

use App\Models\GlobalSeason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalSeasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalSeason::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_season_type_id' => 3,
            'league_type_id' => 1,
            'name' => 'Temporada 2024/2025',
            'start_date' => '2024-09-24',
            'end_date' => '2025-06-30',
            'description' => 'Temporada 2024/2025',
            'is_active' => true
        ]);
    }
}
