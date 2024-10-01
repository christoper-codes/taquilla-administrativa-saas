<?php

namespace Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Serie::create([
            'global_season_id' => 1,
            'name' => 'A',
            'description' => 'Serie A',
            'start_date' => '2024-09-24',
            'end_date' => '2025-06-30',
            'is_active' => true
        ]);
    }
}
