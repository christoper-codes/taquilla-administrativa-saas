<?php

namespace App\Repositories;

use App\Interfaces\SeatCatalogueStatusesRepositoryInterface;
use App\Models\EventSeatCatalog;
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

    public function getBlockAndReservationStatuses()
    {
        return SeatCatalogueStatus::select('id', 'name', 'description')->whereIn('name',['disponible', 'reservado','inhabilitado'])->get();
    }


    public function addStatusToSeat(int $id, int $seat_catalogue_status_id)
    {
        $event_seat_catalog = EventSeatCatalog::with('promotions', 'seatCatalogue')->find($id);

        $event_seat_catalog->seat_catalogue_status_id = $seat_catalogue_status_id;

        $event_seat_catalog->save();

        $event_seat_catalog->load('seatCatalogueStatus');

        return $event_seat_catalog;
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
