<?php

namespace App\Services;

use App\Interfaces\EventSeatCatalogueRepositoryInterface;
use App\Models\Event;
use App\Models\EventSeatCatalog;
use App\Models\PriceCatalogue;
use App\Models\SeasonTicket;
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
    public function saveInBulk(Event $event)
    {
        try {
            $get_all_seats_for_stadium = $this->seat_catalogue_service->getAllSeatsForStadium(1);
            $soldStatusId = $this->seat_catalogue_statuses_service->getByName("vendido")->id;
            $availableStatusId = $this->seat_catalogue_statuses_service->getByName("disponible")->id;
            $event_seat_catalogue = collect([]);

            $get_all_seats_for_stadium->each(function (SeatCatalogue $seat_catalogue) use (&$event_seat_catalogue, $event, $soldStatusId, $availableStatusId){

                $season_ticket = SeasonTicket::where('seat_catalogue_id', $seat_catalogue->id)
                    ->where('global_season_id', $event->global_season_id)
                    ->where('is_active', true)
                    ->first();

                $is_season_event_seat_catalogue = $season_ticket && $season_ticket->EventSeatCatalogues->count();

                $event_seat_catalogue->push([
                    'event_id' => $event->id,
                    'seat_catalogue_id' => $seat_catalogue->id,
                    'user_id' => $is_season_event_seat_catalogue ? $season_ticket->user_id : null,
                    'season_ticket_id' => $is_season_event_seat_catalogue ? $season_ticket->id : null,
                    'seat_catalogue_status_id' => $is_season_event_seat_catalogue ? $soldStatusId : $availableStatusId,
                    'sale_ticket_id' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->sale_ticket_id : null,
                    'qr' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->qr : null,
                    'price' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->price : null,
                    'original_price' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->original_price : null,
                    'purchase_type' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->purchase_type : null,
                    'is_gift' => $is_season_event_seat_catalogue ? $season_ticket->EventSeatCatalogues->first()->is_gift : false,
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
            $eventSeatCatalogs = EventSeatCatalog::where('event_id', $event->id)->get();
            $seatingPrices = [
                'courtside' => [
                    "id" => 4,
                    "price" => 8300
                ],
                'dorado'    =>
                [
                    "id" => 5,
                    "price" => 5100
                ],
                'purpura'   => [
                    "id" => 6,
                    "price" => 3500
                ],
                'fan'       => [
                    "id" => 7,
                    "price" => 1900
                ],
                'publico'   => [
                    "id" => 8,
                    "price" => 1900
                ],
            ];

            /*
            * Attach the price types to the event seat catalogue
            */
            $eventSeatCatalogs->each(function (EventSeatCatalog $eventSeatCatalog) use ($seatingPrices){
                $abono = $seatingPrices[$eventSeatCatalog->seatCatalogue->seatType->name];
                $eventSeatCatalog->priceTypes()->attach([
                    1 => [
                        'price_catalogue_id' => 1,
                        'price' => '100.00',
                        'is_active' => true
                    ],
                    3 => [
                        'price_catalogue_id' => $abono["id"],
                        'price' => $abono["price"],
                        'is_active' => true
                    ],
                ]);
            });

            return $newEventSeatCatalogs;
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get event seat catalog by event
    */
    public function getByEvent(int $id)
    {
        try {
            $event_seat_catalogue = $this->event_seat_catalogue_repository_interface->getByEvent($id);

            return $event_seat_catalogue;

        } catch (\Exception $e) {

            throw $e;
        }
    }



}
