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
        return Event::with(['globalImage','serie.globalSeason.stadium.globalAddress', 'globalSeason', 'eventVisibilityType'])->get();
    }

    public function getById($id)
    {
        $event = Event::with([
            'globalImage',
            'eventSeatCatalogues.seatCatalogue.seatType',
            'eventSeatCatalogues.priceTypes',
            'eventSeatCatalogues.seatCatalogueStatus',
            'eventSeatCatalogues.promotions',
            'eventSeatCatalogues.promotions.promotionType',
        ])->findOrFail($id);

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

    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null, $is_gift = null, $purchase_type = null)
    {
        $event = Event::findOrFail($event_id);

        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'vendido')->first();

        $event->eventSeatCatalogues()->where('seat_catalogue_id', $seat_catalogue_id)->update([
            'seat_catalogue_status_id' => $seat_catalogue_status->id,
            'user_id' => $member_user_id ?? null,
            'sale_ticket_id' => $sale_ticket_id,
            'qr' => $qr,
            'price' => $price,
            'purchase_type' => $purchase_type,
            'is_gift' => $is_gift,
        ]);

        return $event;
    }

    public function getEventsBySerie($serie_id)
    {
        return Event::where('serie_id', $serie_id)
            ->where('enabled_for_season_tickets', false)
            ->where('is_active', true)
            ->get();
    }

    public function getOnlyEvent($id)
    {
        return Event::findOrFail($id);
    }
}
