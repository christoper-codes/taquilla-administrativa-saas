<?php

namespace Database\Seeders;

use App\Models\GlobalAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalAddressesSeeder extends Seeder
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
        GlobalAddress::create([
            'user_id' => null,
            'address_line_1' => '1234 main st',
            'address_line_2' => 'apt 123',
            'city' => 'xalapa',
            'state' => 'veracruz',
            'zip_code' => '90001',
            'country' => 'mexico',
            'is_active' => true,
        ]);

        /*
        * |--------------------------------------------------------------------------
        * | Instantiate the GlobalAddress register |  by Christoper Patiño
        * |--------------------------------------------------------------------------
        * | Lobos Buap
        */
        GlobalAddress::create([
            'user_id' => null,
            'address_line_1' => '1234 main st',
            'address_line_2' => 'apt 123',
            'city' => 'puebla',
            'state' => 'puebla',
            'zip_code' => '90001',
            'country' => 'mexico',
            'is_active' => true,
        ]);
    }
}
