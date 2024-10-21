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
     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
