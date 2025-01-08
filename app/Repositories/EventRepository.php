<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\SeatCatalogueStatus;
use App\Models\EventSeatCatalog;
use App\Models\SaleTicket;

class EventRepository implements EventRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Event::with(['globalImage','serie.globalSeason.stadium.globalAddress', 'globalSeason'])->get();
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

    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null, $is_gift = null)
    {
        $event = Event::findOrFail($event_id);

        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'vendido')->first();

        $event->eventSeatCatalogues()->where('seat_catalogue_id', $seat_catalogue_id)->update([
            'seat_catalogue_status_id' => $seat_catalogue_status->id,
            'user_id' => $member_user_id ?? null,
            'sale_ticket_id' => $sale_ticket_id,
            'qr' => $qr,
            'price' => $price,
            'is_gift' => $is_gift,
        ]);

        return $event;
    }

    public function getEventsBySerie($serie_id)
    {
        return Event::where('serie_id', $serie_id)
            ->where('is_active', true)
            ->get();
    }

    public function getOnlyEvent($id)
    {
        return Event::findOrFail($id);
    }

    public function getUsersEventForSaleTickets($id){

        try {
            $listResponse = [];

        $saleSeats = EventSeatCatalog::join(
            'seat_catalogues',
            'event_seat_catalog.seat_catalogue_id', '=', 'seat_catalogues.id')
            ->join('sale_tickets', 'event_seat_catalog.sale_ticket_id', '=', 'sale_tickets.id')
            ->join('users', 'event_seat_catalog.user_id', '=', 'users.id')
            ->select( 'event_seat_catalog.event_id AS id_event',
             'event_seat_catalog.user_id',
             'users.email',
             'seat_catalogues.code',
             'sale_tickets.id AS id_sale_tickets',
             'sale_tickets.sale_ticket_status_id'
             )
             ->where('event_seat_catalog.event_id', $id)->get();

            foreach ($saleSeats as $item) {

                $payments = SaleTicket::join(
                    'global_payment_type_sale_ticket', 'sale_tickets.id', '=', 'global_payment_type_sale_ticket.sale_ticket_id')
                    ->join('global_payment_types', 'global_payment_type_sale_ticket.global_payment_type_id', '=', 'global_payment_types.id')
                    ->select(
                        'sale_tickets.id AS id_sale_tickets',
                        'global_payment_types.name AS payment_name',
                        'global_payment_type_sale_ticket.amount'
                    )->where('sale_tickets.id', $item->id_sale_tickets)->get();

                    $itemArray = $item;
                    $itemArray['payments'] = $payments;


                array_push($listResponse, $itemArray);
            };

        $response = [
            'saleSeats' => $saleSeats
        ];

        return $response;

        } catch (\Throwable $th) {

            return $th;

        }

    }

}
