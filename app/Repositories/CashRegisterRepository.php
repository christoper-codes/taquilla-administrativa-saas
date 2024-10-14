<?php

namespace App\Repositories;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;

class CashRegisterRepository implements CashRegisterRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return CashRegister::all();
    }

    public function getById($id)
    {
        return CashRegister::findOrFail($id);
    }

    public function save(array $data)
    {
        return CashRegister::create($data);
    }

    public function update($id, array $data)
    {
        return CashRegister::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return CashRegister::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
