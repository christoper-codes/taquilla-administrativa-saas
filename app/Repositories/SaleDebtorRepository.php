<?php

namespace App\Repositories;

use App\Interfaces\SaleDebtorRepositoryInterface;
use App\Models\SaleDebtor;

class SaleDebtorRepository implements SaleDebtorRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll($id = null)
    {
        return SaleDebtor::where('stadium_id', $id)->get();
    }

    public function getById($id)
    {
        return SaleDebtor::findOrfail($id);
    }

    public function save(array $data)
    {
        return SaleDebtor::create($data);
    }

    public function update($id, array $data)
    {
        return SaleDebtor::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return SaleDebtor::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function cancelSaleDebtor()
    {

    }
}
