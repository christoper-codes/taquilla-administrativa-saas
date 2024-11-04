<?php

namespace App\Repositories;

use App\Interfaces\EventSeatCatalogueRepositoryInterface;
use App\Models\EventSeatCatalog;

class EventSeatCatalogueRepository implements EventSeatCatalogueRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function saveInBulk(array $data)
    {
        return EventSeatCatalog::insert($data);
    }

    public function getByEvent(int $id)
    {
        return EventSeatCatalog::with('seatCatalogue')->where('event_id', $id)->get();
    }
}
