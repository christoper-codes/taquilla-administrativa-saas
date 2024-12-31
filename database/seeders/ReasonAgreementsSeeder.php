<?php

namespace Database\Seeders;

use App\Models\ReasonAgreement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonAgreementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReasonAgreement::create([
            'stadium_id' => 1,
            'name' => 'otro',
            'description' => 'rason especial'
        ]);
        ReasonAgreement::create([
            'stadium_id' => 1,
            'name' => 'soporte y correccion',
            'description' => 'rason generica'
        ]);
        ReasonAgreement::create([
            'stadium_id' => 1,
            'name' => 'personal halcones',
            'description' => 'rason generica'
        ]);
    }
}
