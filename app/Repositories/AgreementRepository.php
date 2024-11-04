<?php

namespace App\Repositories;

use App\Interfaces\AgreementRepositoryInterface;
use App\Models\Agreement;

class AgreementRepository implements AgreementRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Agreement::with('institution', 'globalSeason', 'promotions')->get();
    }

    public function getById($id)
    {
        return Agreement::findOrfail($id);
    }

    public function save(array $data)
    {
        return Agreement::create($data);
    }

    public function update($id, array $data)
    {
        return Agreement::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Agreement::destroy($id);
    }
}
