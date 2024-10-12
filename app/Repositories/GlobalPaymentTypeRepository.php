<?php

namespace App\Repositories;

use App\Interfaces\GlobalPaymentTypeRepositoryInterface;
use App\Models\GlobalPaymentType;

class GlobalPaymentTypeRepository implements GlobalPaymentTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return GlobalPaymentType::all();
    }

    public function getById($id)
    {
        return GlobalPaymentType::findOrFail($id);
    }

    public function save(array $data)
    {
        return GlobalPaymentType::create($data);
    }

    public function update($id, array $data)
    {
        return GlobalPaymentType::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return GlobalPaymentType::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */

}
