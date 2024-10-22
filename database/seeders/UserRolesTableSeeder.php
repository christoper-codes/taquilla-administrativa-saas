<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create([
            'name' => 'miembro',
            'description' => 'rol de miembro',
            'is_active' => true,
        ]);

        UserRole::create([
            'name' => 'vendedor',
            'description' => 'rol de vendor',
            'is_active' => true,
        ]);

        UserRole::create([
            'name' => 'verificador',
            'description' => 'rol de verificador',
            'is_active' => true,
        ]);

        UserRole::create([
            'name' => 'administrador',
            'description' => 'rol de administrador',
            'is_active' => true,
        ]);

        UserRole::create([
            'name' => 'super_administrador',
            'description' => 'rol de super administrador',
            'is_active' => true,
        ]);
    }
}
