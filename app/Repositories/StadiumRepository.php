<?php

namespace App\Repositories;

use App\Interfaces\StadiumRepositoryInterface;
use App\Models\Stadium;

class StadiumRepository implements StadiumRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Stadium::all();
    }

    public function getById($id)
    {
        return Stadium::findOrfail($id);
    }

    public function save(array $data)
    {
        return Stadium::create($data);
    }

    public function update($id, array $data)
    {
        return Stadium::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Stadium::destroy($id);
    }
}
