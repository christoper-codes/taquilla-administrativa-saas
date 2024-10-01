<?php

namespace Database\Seeders;

use App\Models\RowType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RowTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RowType::create([
            'name' => 'Fila A',
            'code' => 'A',
            'description' => 'Fila A',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila B',
            'code' => 'B',
            'description' => 'Fila B',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila C',
            'code' => 'C',
            'description' => 'Fila C',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila D',
            'code' => 'D',
            'description' => 'Fila D',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila E',
            'code' => 'E',
            'description' => 'Fila E',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila F',
            'code' => 'F',
            'description' => 'Fila F',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila G',
            'code' => 'G',
            'description' => 'Fila G',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila H',
            'code' => 'H',
            'description' => 'Fila H',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila I',
            'code' => 'I',
            'description' => 'Fila I',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila J',
            'code' => 'J',
            'description' => 'Fila J',
            'is_active' => true,
        ]);

        RowType::create([
            'name' => 'Fila K',
            'code' => 'K',
            'description' => 'Fila K',
            'is_active' => true,
        ]);
    }
}
