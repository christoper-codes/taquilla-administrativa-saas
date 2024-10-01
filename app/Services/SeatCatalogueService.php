<?php

namespace App\Services;

use App\Interfaces\SeatCatalogueRepositoryInterface;

class SeatCatalogueService
{
    /*
    * |--------------------------------------------------------------------------
    * | SeatCatalogueService the repository services for global module by Christoper Patiño
    */

    protected $seat_catalogue_repository;

    public function __construct(SeatCatalogueRepositoryInterface $seat_catalogue_repository)
    {
        $this->seat_catalogue_repository = $seat_catalogue_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all seat catalogues
    */
    public function getAll()
    {
        $seat_catalogue = $this->seat_catalogue_repository->getAll();

        return $seat_catalogue;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new seat catalogue
    */
    public function save(array $data)
    {

        try {
            $seat_catalogue = $this->seat_catalogue_repository->save($data);

            return $seat_catalogue;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
