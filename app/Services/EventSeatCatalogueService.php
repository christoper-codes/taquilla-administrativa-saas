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
    protected $util_service;


    public function __construct(EventSeatCatalogueRepositoryInterface $event_seat_catalogue_repository_interface, SeatCatalogueService $seat_catalogue_service,
                                SeatCatalogueStatusesService $seat_catalogue_statuses_service, UtilService $util_service)
    {
        $this->event_seat_catalogue_repository_interface = $event_seat_catalogue_repository_interface;
        $this->seat_catalogue_service = $seat_catalogue_service;
        $this->seat_catalogue_statuses_service = $seat_catalogue_statuses_service;
        $this->util_service = $util_service;
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
                $seat_status_id = $availableStatusId;
                $last_event_seat = null;
                if ($season_ticket) {
                    if ($is_season_event_seat_catalogue) {
                        $last_event_seat = $season_ticket->EventSeatCatalogues->first();
                        $seat_status_id = $last_event_seat->seat_catalogue_status_id;
                    } else {
                        $seat_status_id = $soldStatusId;
                    }
                }

                $event_seat_catalogue->push([
                    'event_id' => $event->id,
                    'seat_catalogue_id' => $seat_catalogue->id,
                    'user_id' => $season_ticket ? $season_ticket->user_id : null,
                    'season_ticket_id' => $season_ticket ? $season_ticket->id : null,
                    'seat_catalogue_status_id' => $seat_status_id,
                    'sale_ticket_id' => $last_event_seat?->sale_ticket_id,
                    'qr' => $last_event_seat?->qr,
                    'price' => $last_event_seat?->price,
                    'original_price' => $last_event_seat?->original_price,
                    'purchase_type' => $last_event_seat?->purchase_type,
                    'is_gift' => $last_event_seat?->is_gift ?? false,
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

            $seatingSubcriberPrices = [
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

            $seatingRegularPrices = [
                'courtside' => [
                    "id" => 15,
                    "price" => 500
                ],
                'dorado'    =>
                [
                    "id" => 3,
                    "price" => 300
                ],
                'purpura'   => [
                    "id" => 16,
                    "price" => 200
                ],
                'fan'       => [
                    "id" => 17,
                    "price" => 99
                ],
                'publico'   => [
                    "id" => 17,
                    "price" => 99
                ],
            ];

            /*
            * Attach the price types to the event seat catalogue
            */
            $eventSeatCatalogs->each(function (EventSeatCatalog $eventSeatCatalog) use ($seatingSubcriberPrices, $seatingRegularPrices){

                $abono = $seatingSubcriberPrices[$eventSeatCatalog->seatCatalogue->seatType->name];

                $regular = $seatingRegularPrices[$eventSeatCatalog->seatCatalogue->seatType->name];

                $eventSeatCatalog->priceTypes()->attach([
                    1 => [
                        'price_catalogue_id' => $regular['id'],
                        'price' => $regular['price'],
                        'is_active' => true
                    ],
                    2 => [
                        'price_catalogue_id' => 2,
                        'price' => "0.0000",
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
