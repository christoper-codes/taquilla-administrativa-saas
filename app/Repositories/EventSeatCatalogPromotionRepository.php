<?php

namespace App\Repositories;

use App\Interfaces\EventSeatCatalogPromotionRepositoryInterface;
use App\Models\EventSeatCatalog;

class EventSeatCatalogPromotionRepository implements EventSeatCatalogPromotionRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function addPromotionToSeat(int $id, array $promotions)
    {


        $event_seat_catalog = EventSeatCatalog::with('promotions', 'seatCatalogue')->find($id);

        if (count($promotions) === 0) {

            $event_seat_catalog->promotions()->detach();

        }else{

            $event_seat_catalog->promotions()->sync($promotions);
        }

        $event_seat_catalog->load('promotions');

        return $event_seat_catalog;
    }
}
