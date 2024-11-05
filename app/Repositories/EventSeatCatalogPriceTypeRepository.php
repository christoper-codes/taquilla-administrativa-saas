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
}
