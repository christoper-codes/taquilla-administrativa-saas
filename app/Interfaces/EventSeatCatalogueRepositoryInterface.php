<?php

namespace App\Interfaces;

interface EventSeatCatalogueRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function saveInBulk(array $data);
    public function getByEvent(int $id);
}
