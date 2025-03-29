<?php

namespace Database\Seeders;

use App\Models\TicketOffice;
use App\Models\User;
use App\Services\CashRegisterService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TicketOfficesSeeder extends Seeder
{
    protected $cash_register_service;

    public function __construct(CashRegisterService $cash_register_service)
    {
        $this->cash_register_service = $cash_register_service;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        * |--------------------------------------------------------------------------
        * | Create a online seller user
        */
        $seller_user = User::create([
            'user_gender_id' => 4,
            'first_name' => 'Juan',
            'middle_name' => 'Carlos',
            'last_name' => 'Hernández',
            'username' => 'juan-carlos-hdz',
            'birthdate' =>  Carbon::now(),
            'email' => 'juan.carlos.hdz@example.com',
            'color' => 'purple',
            'is_active' => true,
            'password' => Hash::make('123456789'),
        ]);

        $seller_user->userRoles()->attach(2, ['is_active' => true]);


        /*
        * |--------------------------------------------------------------------------
        * | Create a online seller user
        */
        $online_seller_user = User::create([
            'user_gender_id' => 4,
            'first_name' => 'usuario hdx online',
            'last_name' => 'usuario hdx online',
            'middle_name' => 'usuario hdx online',
            'username' => 'usuario-hdx-online',
            'birthdate' =>  Carbon::now(),
            'email' => 'usuario.hdx.online@gmail.com',
            'color' => 'purple',
            'is_active' => true,
            'password' => Hash::make('123456789'),
        ]);

        $online_seller_user->userRoles()->attach(2, ['is_active' => true]);

        /*
        * |--------------------------------------------------------------------------
        * | Create ticket offices
        */
        $ticket_office_online = TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla online',
            'description' => 'taquilla online para la venta de boletos en linea',
            'is_active' => false
        ]);

        $ticket_office1 =  TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla el nido del halcon',
            'description' => 'taquilla del estadio el nido del halcon en la entrada principal',
            'is_active' => true
        ]);

        $ticket_office2 = TicketOffice::create([
            'stadium_id' => 1,
            'global_image_id' => null,
            'global_address_id' => null,
            'name' => 'taquilla test 1',
            'description' => 'taquilla de prueba 1, para testear el sistema y pruebas de integracion',
            'is_active' => false
        ]);

        /*
        * Relation ships with cash register types (many to many)
        */
        $ticket_office_online->cashRegisterTypes()->attach([
            1 => ['is_active' => true],
        ]);

        $ticket_office1->cashRegisterTypes()->attach([
            1 => ['is_active' => true],
            2 => ['is_active' => true],
            3 => ['is_active' => true]
        ]);

        $ticket_office2->cashRegisterTypes()->attach([
            1 => ['is_active' => true],
            2 => ['is_active' => true],
            3 => ['is_active' => true]
        ]);

        /*
        * |--------------------------------------------------------------------------
        * | Open cash register for the online seller user
        */
        $data = [
            'seller_user_opening_id' => $online_seller_user->id,
            'cash_register_type_id' => 1,
            'ticket_office_id' => $ticket_office_online->id,
            'opening_balance' => 0.0000,
        ];

        $this->cash_register_service->openCashRegister($data);

    }
}
