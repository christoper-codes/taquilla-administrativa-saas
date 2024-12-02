<?php

namespace Database\Seeders;

use App\Models\EventSeatCatalog;
use App\Models\SeatCatalogue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatCataloguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* $seatCatalogues = [
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '1', 'code' => 'AA1', 'seat_type_id' => 1],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '2', 'code' => 'AA2', 'seat_type_id' => 1],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '3', 'code' => 'AA3', 'seat_type_id' => 1],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '4', 'code' => 'AA4', 'seat_type_id' => 2],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '5', 'code' => 'AA5', 'seat_type_id' => 2],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '6', 'code' => 'AA6', 'seat_type_id' => 2],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '7', 'code' => 'AA7', 'seat_type_id' => 3],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '8', 'code' => 'AA8', 'seat_type_id' => 3],
            ['stadium_id' => 1, 'zone' => 'A', 'row' => 'A', 'seat' => '9', 'code' => 'AA9', 'seat_type_id' => 3],
        ];

        foreach ($seatCatalogues as $seatData) {
            $seatCatalogue = SeatCatalogue::create(array_merge($seatData, [
                'zone_type_id' => null,
                'row_type_id' => null,
                'description' => 'generic',
                'is_active' => true,
            ]));

            // Establecer la relación con eventos
            $seatCatalogue->events()->attach(1, [
                'user_id' => null,
                'season_ticket_id' => null,
                'seat_catalogue_status_id' => 1,
                'sale_ticket_id' => null,
                'price' => null,
                'is_verified' => false,
                'is_active' => true,
            ]);

            // Obtener el registro de la tabla pivote 'event_seat_catalog'
            $eventSeatCatalog = EventSeatCatalog::where('event_id', 1)
                ->where('seat_catalogue_id', $seatCatalogue->id)
                ->first();

            // Establecer la relación con tipos de precio
            $eventSeatCatalog->priceTypes()->attach([
                1 => ['price_catalogue_id' => 1, 'is_active' => true],
                2 => ['price_catalogue_id' => 2, 'is_active' => true],
            ]);
        } */
    }
}
