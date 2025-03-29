<?php

namespace Database\Seeders;

use App\Models\PriceCatalogue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceCataloguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 100,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 0,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 300,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 8300,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 5090,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 3490,
            'description' => 'generic',
            'is_active' => true,
        ]);

        PriceCatalogue::create([
            'stadium_id' => 1,
            'price' => 1860,
            'description' => 'generic',
            'is_active' => true,
        ]);
    }
}
