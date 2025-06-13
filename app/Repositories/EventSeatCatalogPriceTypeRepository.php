<?php

namespace App\Repositories;

use App\Interfaces\EventSeatCatalogPriceTypeRepositoryInterface;
use App\Models\EventSeatCatalogPriceType;

class EventSeatCatalogPriceTypeRepository implements EventSeatCatalogPriceTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function saveInBulk(array $data)
    {
        return  EventSeatCatalogPriceType::insert($data);

    }

    public function updateInBulk(array $seats)
    {
        $seatsUpdate = collect([]);

        foreach ($seats as $seat) {

            $findByEventSeatCatalog = $this->findByEventSeatCatalog($seat['event_seat_catalog_id'], $seat['price_type_id']);

            if ($findByEventSeatCatalog->isNotEmpty()) {
                foreach ($findByEventSeatCatalog as $findByEventSeatCatalogUpdate) {
                    $findByEventSeatCatalogUpdate->update(['is_active' => false]);
                }
            }

            $seatUpdate = EventSeatCatalogPriceType::create([
                'event_seat_catalog_id' => $seat['event_seat_catalog_id'],
                'price_type_id' => $seat['price_type_id'],
                'price_catalogue_id' => $seat['price_catalogue_id'],
                'price' => $seat['price'],
                'is_active' => true,
            ]);

            $seatUpdate->eventSeatCatalog->priceTypes;

            $seatsUpdate->push($seatUpdate->eventSeatCatalog);
        }

        return $seatsUpdate;
    }

    public function findByEventSeatCatalog(int $event_seat_catalog_id, int $price_type_id)
    {
        return  EventSeatCatalogPriceType::where('event_seat_catalog_id', $event_seat_catalog_id)
                                         ->where('price_type_id', $price_type_id)
                                         ->where('is_active', true)
                                         ->get();

    }
}
