<?php

namespace App\Services;

use App\Interfaces\EventSeatCatalogueRepositoryInterface;
use App\Models\EventSeatCatalog;
use App\Models\PriceCatalogue;
use App\Models\SeatCatalogue;

class EventSeatCatalogueService
{
    /*
    * |--------------------------------------------------------------------------
    * | EventService the repository services for global module by Christoper Patiño
    */

    protected $event_seat_catalogue_repository_interface;
    protected $seat_catalogue_service;
    protected $seat_catalogue_statuses_service;


    public function __construct(EventSeatCatalogueRepositoryInterface $event_seat_catalogue_repository_interface, SeatCatalogueService $seat_catalogue_service,
                                SeatCatalogueStatusesService $seat_catalogue_statuses_service)
    {
        $this->event_seat_catalogue_repository_interface = $event_seat_catalogue_repository_interface;
        $this->seat_catalogue_service = $seat_catalogue_service;
        $this->seat_catalogue_statuses_service = $seat_catalogue_statuses_service;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Save new event seat catalogue
    */

    public function saveInBulk(int $event_id)
    {
        try {

            $get_all_seats_for_stadium = $this->seat_catalogue_service->getAllSeatsForStadium(1);

            $event_seat_catalogue = collect([]);

            $get_all_seats_for_stadium->each(function (SeatCatalogue $seat_catalogue) use (&$event_seat_catalogue, $event_id){

                $event_seat_catalogue->push([

                    'event_id' => $event_id,
                    'seat_catalogue_id' => $seat_catalogue->id,
                    'user_id' => null,
                    'season_ticket_id' => null,
                    'seat_catalogue_status_id' => $this->seat_catalogue_statuses_service->getByName("disponible")->id,
                    'sale_ticket_id' => null,
                    'price' => null,
                    'is_verified' => false,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });

            /*
            * save the event seat catalogue in bulk
            */
            $newEventSeatCatalogs =  $this->event_seat_catalogue_repository_interface->saveInBulk($event_seat_catalogue->toArray());

            /*
            * Get all event seat catalogue where event_id
            */
            $eventSeatCatalogs = EventSeatCatalog::where('event_id', $event_id)->get();

            /*
            * Attach the price types to the event seat catalogue
            */
            $eventSeatCatalogs->each(function (EventSeatCatalog $eventSeatCatalog) {

                $eventSeatCatalog->priceTypes()->attach([
                    1 => [
                        'price_catalogue_id' => 1,
                        'price' => PriceCatalogue::where('id', 1)->first()->price,
                        'is_active' => true
                    ],
                    2 => [
                        'price_catalogue_id' => 2,
                        'price' => PriceCatalogue::where('id', 2)->first()->price,
                        'is_active' => true
                    ],
                ]);

            });

            return $newEventSeatCatalogs;

        } catch (\Exception $e) {

            throw $e;
        }
    }




}
