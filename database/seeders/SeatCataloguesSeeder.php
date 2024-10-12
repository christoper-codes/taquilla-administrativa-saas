<?php

namespace Database\Seeders;

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
         $seatCatalog1 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 1,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '1',
            'code' => 'AA1',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog2 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 1,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '2',
            'code' => 'AA2',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog3 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 1,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '3',
            'code' => 'AA3',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog4 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 2,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '4',
            'code' => 'AA4',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog5 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 2,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '5',
            'code' => 'AA5',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog6 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 2,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '6',
            'code' => 'AA6',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog7 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 3,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '7',
            'code' => 'AA7',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog8 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 3,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '8',
            'code' => 'AA8',
            'description' => 'generic',
            'is_active' => true
        ]);

        $seatCatalog9 = SeatCatalogue::create([
            'stadium_id' => 1,
            'zone_type_id' => null,
            'seat_type_id' => 3,
            'row_type_id' => null,
            'zone' => 'A',
            'row' => 'A',
            'seat' => '9',
            'code' => 'AA9',
            'description' => 'generic',
            'is_active' => true
        ]);

        /*
        * relation ship with price types
        */
        $seatCatalog1->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog1->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog1->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog2->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog2->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog2->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog3->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog3->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog3->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog4->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog4->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog4->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog5->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog5->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog5->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog6->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog6->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog6->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);


        $seatCatalog7->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog7->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog7->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog8->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog8->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog8->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        $seatCatalog9->priceTypes()->attach(1, ['price_catalogue_id' => 1, 'is_active' => true]);
        $seatCatalog9->priceTypes()->attach(2, ['price_catalogue_id' => 2, 'is_active' => true]);
        $seatCatalog9->priceTypes()->attach(3, ['price_catalogue_id' => 3, 'is_active' => true]);

        /*
        * relation ship with events
        */

        $seatCatalog1->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog2->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog3->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog4->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog5->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog6->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog7->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog8->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

        $seatCatalog9->events()->attach(1, [
            'user_id' => null,
            'season_ticket_id' => null,
            'seat_catalogue_status_id' => 1,
            'sale_ticket_id' => null,
            'price' => null,
            'is_verified' => false,
            'is_active' => true
        ]);

    }
}
