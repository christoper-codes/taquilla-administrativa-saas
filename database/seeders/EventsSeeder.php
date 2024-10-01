<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'event_type_id' => 1,
            'serie_id' => 1,
            'global_image_id' => null,
            'name' => 'Halcones de xalapa vs Leones de yucatan',
            'description' => 'Partido de la jornada 1 de la serie A',
            'start_date' => '2024-09-24 20:00:00',
            'end_date' => '2024-09-24 22:00:00',
            'is_active' => true
        ]);

        Event::create([
            'event_type_id' => 1,
            'serie_id' => 1,
            'global_image_id' => null,
            'name' => 'Halcones de xalapa vs Piratas de campeche',
            'description' => 'Partido de la jornada 1 de la serie A',
            'start_date' => '2024-09-24 20:00:00',
            'end_date' => '2024-09-24 22:00:00',
            'is_active' => true
        ]);
    }
}
