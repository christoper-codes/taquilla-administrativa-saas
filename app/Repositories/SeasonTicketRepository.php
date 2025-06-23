<?php

namespace App\Repositories;

use App\Interfaces\SeasonTicketRepositoryInterface;
use App\Models\SeasonTicket;

class SeasonTicketRepository implements SeasonTicketRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return  SeasonTicket::all();
    }

    public function getById($id)
    {
        return  SeasonTicket::findOrFail($id);
    }

    public function save(array $data)
    {
        return  SeasonTicket::create($data);
    }

    public function update($id, array $data)
    {
        return  SeasonTicket::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return  SeasonTicket::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */

    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getBySeason(int $id)
    {
        return SeasonTicket::with(['user','globalSeason','seatCatalogue'])->where('global_season_id',$id)->get();
    }
}
