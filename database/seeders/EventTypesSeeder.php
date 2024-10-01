<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventType::create([
            'name' => 'deportivo',
            'description' => 'evento con fines deportivos',
            'is_active' => true
        ]);

        EventType::create([
            'name' => 'cultural',
            'description' => 'evento con fines culturales',
            'is_active' => true
        ]);

        EventType::create([
            'name' => 'generico',
            'description' => 'evento con fines genericos',
            'is_active' => true
        ]);
    }
}
