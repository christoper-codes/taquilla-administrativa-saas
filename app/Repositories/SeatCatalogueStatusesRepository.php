<?php

namespace App\Repositories;

use App\Interfaces\SeatCatalogueStatusesRepositoryInterface;
use App\Models\SeatCatalogueStatus;

class SeatCatalogueStatusesRepository implements SeatCatalogueStatusesRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */


    public function getByName($name)
    {
        return SeatCatalogueStatus::where('name',$name)->first();
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
