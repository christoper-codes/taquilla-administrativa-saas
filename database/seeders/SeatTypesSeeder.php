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
            'percentage_cashback' => 15,
            'description' => 'courtside',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'dorado',
            'code' => 'AB',
            'percentage_cashback' => 15,
            'description' => 'dorado',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'purpura',
            'code' => 'AC',
            'percentage_cashback' => 15,
            'description' => 'purpura',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'fan',
            'code' => 'AD',
            'percentage_cashback' => 15,
            'description' => 'fan',
            'is_active' => true,
        ]);

        SeatType::create([
            'stadium_id' => 1,
            'name' => 'publico',
            'code' => 'AE',
            'percentage_cashback' => 15,
            'description' => 'publico',
            'is_active' => true,
        ]);
    }
}
