<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\SeatCatalogueStatus;

class EventRepository implements EventRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Event::with('globalImage')->get();
    }

    public function getById($id)
    {
        $event = Event::with([
            'globalImage',
            'eventSeatCatalogues.seatCatalogue.seatType',
            'eventSeatCatalogues.priceTypes',
            'eventSeatCatalogues.seatCatalogueStatus',
        ])->findOrFail($id);

       /*  $event = Event::with([
            'globalImage',
            'eventSeatCatalogues.seatCatalogue.seatType',
            'eventSeatCatalogues.seatCatalogueStatus',
            'eventSeatCatalogues.priceTypes' => function ($query) {
                $query->withPivot('price_catalogue_id', 'is_active');
            },
            'eventSeatCatalogues.priceTypes.pivot' => function ($query) {
                $query->with('priceCatalogue');
            }
        ])->findOrFail($id); */

        return $event;
    }

    public function save(array $data)
    {
        return Event::create($data);
    }

    public function update($id, array $data)
    {
        return Event::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Event::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function reserveSeatsToBuy($event_id, $seat_catalogue_id, $member_user_id)
    {
        $event = Event::findOrFail($event_id);

        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'transito')->first();

        $event->eventSeatCatalogues()->where('seat_catalogue_id', $seat_catalogue_id)->update([
            'seat_catalogue_status_id' => $seat_catalogue_status->id,
            'user_id' => $member_user_id,
        ]);

        return $event;
    }

    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null)
    {
        $event = Event::findOrFail($event_id);

        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'vendido')->first();

        $event->eventSeatCatalogues()->where('seat_catalogue_id', $seat_catalogue_id)->update([
            'seat_catalogue_status_id' => $seat_catalogue_status->id,
            'user_id' => $member_user_id ?? null,
            'sale_ticket_id' => $sale_ticket_id,
            'qr' => $qr,
            'price' => $price,
        ]);

        return $event;
    }

    public function getEventsBySerie($serie_id)
    {
        return Event::where('serie_id', $serie_id)
            ->where('is_active', true)
            ->get();
    }
}
