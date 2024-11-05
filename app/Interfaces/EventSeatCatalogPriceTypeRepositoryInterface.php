<?php

namespace App\Interfaces;

interface EventSeatCatalogPriceTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function saveInBulk(array $data);
}
