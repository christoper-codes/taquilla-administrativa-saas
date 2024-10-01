<?php

namespace Database\Seeders;

use App\Models\LeadingCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadingCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /*
        * |--------------------------------------------------------------------------
        * | Instantiate the leadingCompany register | by Christoper Patiño
        * |--------------------------------------------------------------------------
        * | Halcones de xalapa
        */
        LeadingCompany::create([
            'global_image_id' => null,
            'global_address_id' => 1,
            'name' => 'sama bienes y servicios',
            'email' => 'samabienesyservicios@gmail.com',
            'phone_number' => '2281234567',
            'description' => 'company description',
            'is_active' => true,
        ]);
    }
}
