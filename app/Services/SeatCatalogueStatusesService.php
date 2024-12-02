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

    /*
    * |--------------------------------------------------------------------------
    * | Obtain seat catalogs for Block and Reserve
    */
    public function getBlockAndReservationStatuses()
    {
        return $this->seat_catalogue_statuses_repository->getBlockAndReservationStatuses();
    }

    public function addStatusToSeat(array $seats)
    {
        try {

            $seats_updated = collect([]);

            collect($seats)->each(function ($seat) use ($seats_updated){

                $response = $this->seat_catalogue_statuses_repository->addStatusToSeat($seat['id'], $seat['modified_status_id']);

                $seats_updated->push($response);

            });

            $a_zone = [];
            $b_zone = [];
            $c_zone = [];
            $f_zone = [];

            $seats_updated->groupBy(function ($item) {

                return $item->seatCatalogue->zone;

            })->each(function ($item, $key) use (&$a_zone, &$b_zone, &$c_zone, &$f_zone) {
                if ($key === 'A') {
                    $a_zone = $item;
                } elseif ($key === 'B') {
                    $b_zone = $item;
                } elseif ($key === 'C') {
                    $c_zone = $item;
                } elseif ($key === 'F') {
                    $f_zone = $item;
                }
            });

            $response = [
                'a_zone' => $a_zone,
                'b_zone' => $b_zone,
                'c_zone' => $c_zone,
                'f_zone' => $f_zone
            ];

            return $response;

        } catch (\Exception $e) {

            throw $e;
        }
    }

}
