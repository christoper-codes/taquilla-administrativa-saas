<?php

namespace App\Repositories;

use App\Interfaces\GlobalCardPaymentTypeRepositoryInterface;
use App\Models\GlobalCardPaymentType;

class GlobalCardPaymentTypeRepository implements GlobalCardPaymentTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return GlobalCardPaymentType::all();
    }

    public function getById($id)
    {
        return GlobalCardPaymentType::findOrFail($id);
    }

    public function save(array $data)
    {
        return GlobalCardPaymentType::create($data);
    }

    public function update($id, array $data)
    {
        return GlobalCardPaymentType::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return GlobalCardPaymentType::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
