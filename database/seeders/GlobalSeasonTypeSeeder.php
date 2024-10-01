<?php

namespace Database\Seeders;

use App\Models\GlobalSeasonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalSeasonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalSeasonType::create([
            'name' => 'femenil',
            'description' => 'temporada femenil',
            'is_active' => true,
        ]);

        GlobalSeasonType::create([
            'name' => 'varonil',
            'description' => 'temporada varonil',
            'is_active' => true,
        ]);

        GlobalSeasonType::create([
            'name' => 'mixto',
            'description' => 'temporada mixta',
            'is_active' => true,
        ]);

        GlobalSeasonType::create([
            'name' => 'generica',
            'description' => 'temporada generica',
            'is_active' => true,
        ]);
    }
}
