<?php

namespace App\Repositories;

use App\Interfaces\WalletRechargeAmountRepositoryInterface;
use App\Models\WalletRechargeAmount;

class WalletRechargeAmountRepository implements WalletRechargeAmountRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return WalletRechargeAmount::all();
    }

    public function find(int $id)
    {
        return WalletRechargeAmount::find($id);
    }
}
