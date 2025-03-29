<?php

namespace App\Interfaces;

interface PriceCatalogRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAllForStadium(int $stadium_id);
    public function firstOrCreate(array $data);
}
