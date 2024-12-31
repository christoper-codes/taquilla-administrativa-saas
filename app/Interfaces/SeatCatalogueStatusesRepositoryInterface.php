<?php

namespace App\Interfaces;

interface SeatCatalogueStatusesRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */

    public function getByName($name);
    public function getBlockAndReservationStatuses();
    public function addStatusToSeat(int $id, int $seat_catalogue_status_id);

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
