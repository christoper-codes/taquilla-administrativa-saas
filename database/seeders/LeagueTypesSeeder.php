<?php

namespace Database\Seeders;

use App\Models\LeagueType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeagueTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeagueType::create([
            'global_image_id' => null,
            'name' => 'liga generica',
            'description' => 'liga generica',
            'is_active' => true
        ]);
    }
}
