<?php

namespace App\Repositories;

use App\Interfaces\OnlinePaymentTransactionRepositoryInterface;
use App\Models\OnlinePaymentTransaction;

class OnlinePaymentTransactionRepository implements OnlinePaymentTransactionRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll($id = null)
    {
        return OnlinePaymentTransaction::all();
    }

    public function getById($id)
    {
        return OnlinePaymentTransaction::findOrfail($id);
    }

    public function save(array $data)
    {
        return OnlinePaymentTransaction::create($data);
    }

    public function update($id, array $data)
    {
        return OnlinePaymentTransaction::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return OnlinePaymentTransaction::destroy($id);
    }
}
