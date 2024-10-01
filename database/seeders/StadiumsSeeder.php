<?php

namespace Database\Seeders;

use App\Models\Stadium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StadiumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        * |--------------------------------------------------------------------------
        * | Instantiate the GlobalAddress register |  by Christoper Patiño
        * |--------------------------------------------------------------------------
        * | Halcones de Xalapa
        */
        Stadium::create([
            'leading_company_id' => 1,
            'global_address_id' => 1,
            'name' => 'el_nido_del_halcon',
            'club_name' => 'halcones_de_xalapa',
            'email' => 'elnidodelhalcon@gmail.com',
            'phone_number' => '123456789',
            'description' => 'Club de baloncesto profesional de la ciudad de Xalapa, Veracruz',
            'is_active' => true
        ]);

         /*
        * |--------------------------------------------------------------------------
        * | Instantiate the GlobalAddress register |  by Christoper Patiño
        * |--------------------------------------------------------------------------
        * | Lobos buap
        */
        Stadium::create([
            'leading_company_id' => 1,
            'global_address_id' => 2,
            'name' => 'arena_buap',
            'club_name' => 'lobos_buap',
            'email' => 'arenabuap@gmail.com',
            'phone_number' => '123456789',
            'description' => 'Club de futbol profesional de la ciudad de Puebla, Puebla',
            'is_active' => true
        ]);
    }
}
