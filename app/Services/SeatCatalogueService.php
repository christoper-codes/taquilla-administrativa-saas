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
        return $this->seat_catalogue_repository->getAll();
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all seat catalogues
    */
    public function getAllSeatsForStadium(int $id)
    {
        return $this->seat_catalogue_repository->getAllSeatsForStadium($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new seat catalogue
    */
    public function save(array $data)
    {

        try {

            return $this->seat_catalogue_repository->save($data);

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new seat catalogue
    */
    public function saveAllSeatsForStadium(array $data)
    {
        try {

            return $this->seat_catalogue_repository->saveAllSeatsForStadium($data);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
