<?php

namespace App\Repositories;

use App\Interfaces\GlobalImageRepositoryInterface;
use App\Models\GlobalImage;

class GlobalImageRepository implements GlobalImageRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return GlobalImage::all();
    }

    public function getById($id)
    {
        return GlobalImage::findOrfail($id);
    }

    public function save(array $data)
    {
        return GlobalImage::create($data);
    }

    public function update($id, array $data)
    {
        return GlobalImage::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return GlobalImage::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */

}
