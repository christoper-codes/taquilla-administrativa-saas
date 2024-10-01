<?php

namespace Database\Seeders;

use App\Models\SeatType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeatType::create([
            'stadium_id' => 1,
            'name' => 'courtside',
            'code' => 'AA',
            'description' => 'courtside',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'dorado',
            'code' => 'AB',
            'description' => 'dorado',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'purpura',
            'code' => 'AC',
            'description' => 'purpura',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'fan',
            'code' => 'AD',
            'description' => 'fan',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'publico',
            'code' => 'AE',
            'description' => 'publico',
            'is_active' => true,
        ]);
    }
}
