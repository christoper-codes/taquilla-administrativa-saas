<?php

namespace App\Services;

use App\Interfaces\SeatCatalogueStatusesRepositoryInterface;

class SeatCatalogueStatusesService
{
    /*
    * |--------------------------------------------------------------------------
    * | SeatCatalogueStatusesService the repository services for global module by Christoper Patiño
    */

    protected $seat_catalogue_statuses_repository;

    public function __construct(SeatCatalogueStatusesRepositoryInterface $seat_catalogue_statuses_repository)
    {
        $this->seat_catalogue_statuses_repository = $seat_catalogue_statuses_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all seat catalogues
    */
    public function getByName($name)
    {
        return $this->seat_catalogue_statuses_repository->getByName($name);
    }

}
