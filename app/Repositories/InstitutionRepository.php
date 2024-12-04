<?php

namespace App\Repositories;

use App\Interfaces\InstitutionRepositoryInterface;
use App\Models\Institution;

class InstitutionRepository implements InstitutionRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Institution::with('stadium', 'globalImage')->get();
    }

    public function getById($id)
    {
        return Institution::findOrfail($id);
    }

    public function save(array $data)
    {
        return Institution::create($data);
    }

    public function update($id, array $data)
    {
        return Institution::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Institution::destroy($id);
    }
}
