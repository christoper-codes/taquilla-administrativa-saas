<?php

namespace App\Repositories;

use App\Interfaces\GlobalSeasonRepositoryInterface;
use App\Models\GlobalSeason;

class GlobalSeasonRepository implements GlobalSeasonRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return GlobalSeason::all();
    }

    public function getById($id)
    {
        return GlobalSeason::findOrfail($id);
    }

    public function save(array $data)
    {
        return GlobalSeason::create($data);
    }

    public function update($id, array $data)
    {
        return GlobalSeason::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return GlobalSeason::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
