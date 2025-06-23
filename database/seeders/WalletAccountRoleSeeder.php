<?php

namespace Database\Seeders;

use App\Models\WalletAccountRole;
use Illuminate\Database\Seeder;

class WalletAccountRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletAccountRole::create([
            'name' => 'member',
            'description' => 'Miembro de la plataforma',
            'is_active' => true,
        ]);

        WalletAccountRole::create([
            'name' => 'seller',
            'description' => 'Vendedor de la plataforma',
            'is_active' => true,
        ]);

        WalletAccountRole::create([
            'name' => 'app_seller',
            'description' => 'Vendedor de la aplicación',
            'is_active' => true,
        ]);

        WalletAccountRole::create([
            'name' => 'admin',
            'description' => 'Administrador de la plataforma',
            'is_active' => true,
        ]);

        WalletAccountRole::create([
            'name' => 'super_admin',
            'description' => 'Super administrador de la plataforma',
            'is_active' => true,
        ]);
    }
}
