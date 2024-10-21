<?php

namespace App\Repositories;

use App\Interfaces\SeatCatalogueRepositoryInterface;
use App\Models\SeatCatalogue;

class SeatCatalogueRepository implements SeatCatalogueRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return SeatCatalogue::all();
    }

    public function getAllSeatsForStadium(int $id)
    {
        return SeatCatalogue::where('stadium_id',$id)->get();
    }

    public function getById($id)
    {
        return SeatCatalogue::findOrfail($id);
    }

    public function save(array $data)
    {
        return SeatCatalogue::create($data);
    }

    public function saveAllSeatsForStadium(array $data){

        return SeatCatalogue::insert($data);
    }

    public function update($id, array $data)
    {
        return SeatCatalogue::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return SeatCatalogue::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
