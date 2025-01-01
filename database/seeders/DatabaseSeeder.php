<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserGendersTableSeeder::class,
            UserRolesTableSeeder::class,
            GlobalAddressesSeeder::class,
            LeadingCompaniesSeeder::class,
            StadiumsSeeder::class,
            GlobalSeasonTypeSeeder::class,
            ZoneTypesSeeder::class,
            RowTypesSeeder::class,
            SeatTypesSeeder::class,
            LeagueTypesSeeder::class,
            InstitutionsSeeder::class,
            PriceTypeSeeder::class,
            EventTypesSeeder::class,
            GlobalSeasonsSeeder::class,
            SeriesSeeder::class,
            EventsSeeder::class,
            SeatCatalogueStatusesSeeder::class,
            CashRegisterTypesSeeder::class,
            TicketOfficesSeeder::class,
            CashRegisterMovementTypesSeeder::class,
            SaleTicketStatusesSeeder::class,
            GlobalPaymentTypesSeeder::class,
            GlobalCardPaymentTypesSeeder::class,
            PriceCataloguesSeeder::class,
            SeatCataloguesSeeder::class,
            PromotionTypesSeeder::class,
            //SeatCataloguesSeeder::class,
            SalesTicketCancellationCodesSeeder::class,
            CourtesyTypesSeeder::class,
            ReasonAgreementsSeeder::class,
            AgreementsSeeder::class,
        ]);
    }
}
