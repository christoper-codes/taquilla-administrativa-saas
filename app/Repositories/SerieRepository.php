<?php

namespace App\Repositories;

use App\Interfaces\SerieRepositoryInterface;
use App\Models\Serie;

class SerieRepository implements SerieRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Serie::all();
    }

    public function getById($id)
    {
        return Serie::findOrfail($id);
    }

    public function save(array $data)
    {
        return Serie::create($data);
    }

    public function update($id, array $data)
    {
        return Serie::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Serie::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
