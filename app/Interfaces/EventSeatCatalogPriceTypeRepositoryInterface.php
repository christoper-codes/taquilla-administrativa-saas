<?php

namespace App\Interfaces;

interface EventSeatCatalogPriceTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function saveInBulk(array $data);
    public function updateInBulk(array $data);
    public function findByEventSeatCatalog(int $event_seat_catalog_id, int $price_type_id);
}
