<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\SeatCatalogueStatus;
use App\Models\EventSeatCatalog;
use App\Models\SaleTicket;
use Illuminate\Support\Facades\Cache;

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

    public function getAllActive()
    {
         return Event::with(['globalImage','serie.globalSeason.stadium.globalAddress', 'globalSeason', 'eventVisibilityType'])
        ->where('is_active', true)
        ->get();
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
       $seat_catalogue_status_id = $this->getTransitoStatusId();

      return EventSeatCatalog::where('event_id', $event_id)
        ->where('seat_catalogue_id', $seat_catalogue_id)
        ->update([
            'seat_catalogue_status_id' => $seat_catalogue_status_id,
            'user_id' => $member_user_id,
        ]);
    }

    private function getTransitoStatusId()
    {
        return Cache::remember('seat_status_transito_id', 3600, function () {
            return SeatCatalogueStatus::where('name', 'transito')->value('id');
        });
    }

    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null,$original_pricde = null,  $is_gift = null, $purchase_type = null)
    {
        $event = Event::findOrFail($event_id);

        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'vendido')->first();

        $event->eventSeatCatalogues()->where('seat_catalogue_id', $seat_catalogue_id)->update([
            'seat_catalogue_status_id' => $seat_catalogue_status->id,
            'user_id' => $member_user_id ?? null,
            'sale_ticket_id' => $sale_ticket_id,
            'qr' => $qr,
            'price' => $price,
            'original_price' => $original_pricde,
            'purchase_type' => $purchase_type,
            'is_gift' => $is_gift,
        ]);

        return $event;
    }

    public function getEventsBySerie($serie_id)
    {
       $cache_key = "events_serie_{$serie_id}";

        return Cache::remember($cache_key, 3600, function () use ($serie_id) {
            return Event::where([
                    ['serie_id', '=', $serie_id],
                    ['enabled_for_season_tickets', '=', false],
                    ['is_active', '=', true]
                ])
                ->get();
        });
    }

    public function getOnlyById($id)
    {
        return Event::findOrFail($id);
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

    public function getAllWithTraffic()
    {
        $transitoStatusId = SeatCatalogueStatus::where('name', 'transito')->first()->id;

        return Event::with(['globalImage'])
            ->where('is_active', true)
            ->whereHas('eventSeatCatalogues', function ($query) use ($transitoStatusId) {
                $query->where('seat_catalogue_status_id', $transitoStatusId);
            })
            ->withCount(['eventSeatCatalogues as traffic_seats_count' => function ($query) use ($transitoStatusId) {
                $query->where('seat_catalogue_status_id', $transitoStatusId);
            }])
            ->get();
    }

    public function releaseReservedSeats($event_id)
    {
        $event = Event::findOrFail($event_id);
        $seat_catalogue_status = SeatCatalogueStatus::where('name', 'disponible')->first();
        $transitoStatusId = SeatCatalogueStatus::where('name', 'transito')->first()->id;

        $event->eventSeatCatalogues()
            ->where('seat_catalogue_status_id', $transitoStatusId)
            ->where('created_at', '<', now()->subMinutes(20))
            ->update([
                'seat_catalogue_status_id' => $seat_catalogue_status->id,
                'user_id' => null,
                'sale_ticket_id' => null,
                'qr' => null,
                'price' => null,
                'original_price' => null,
                'purchase_type' => null,
                'is_gift' => false,
            ]);

        return $event;
    }

    public function reserveSeatsToBuyBatch($event_id, $seat_catalogue_ids, $member_user_id)
    {
        $seat_catalogue_status_id = $this->getTransitoStatusId();

        return EventSeatCatalog::where('event_id', $event_id)
            ->whereIn('seat_catalogue_id', $seat_catalogue_ids)
            ->update([
                'seat_catalogue_status_id' => $seat_catalogue_status_id,
                'user_id' => $member_user_id,
                'updated_at' => now()
            ]);
    }
}
