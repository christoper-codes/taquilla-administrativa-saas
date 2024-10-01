<?php

namespace Database\Seeders;

use App\Models\UserGender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserGender::create([
            'name' => 'masculino',
            'description' => 'Género masculino',
            'color' => '#0000FF',
            'is_active' => true,
        ]);

        UserGender::create([
            'name' => 'femenino',
            'description' => 'Género femenino',
            'color' => '#FF0000',
            'is_active' => true,
        ]);

        UserGender::create([
            'name' => 'no_binario',
            'description' => 'Género no binario',
            'color' => '#00FF00',
            'is_active' => true,
        ]);

        UserGender::create([
            'name' => 'otro',
            'description' => 'Otro género',
            'color' => '#000000',
            'is_active' => true,
        ]);
    }
}
