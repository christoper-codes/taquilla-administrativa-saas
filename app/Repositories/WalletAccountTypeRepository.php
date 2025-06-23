<?php

namespace App\Repositories;

use App\Interfaces\WalletAccountTypeRepositoryInterface;
use App\Models\WalletAccountType;

class WalletAccountTypeRepository implements WalletAccountTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return WalletAccountType::select(
            'id',
            'wallet_currency_id',
            'name'
        )->with([
            'walletCurrency' => function ($query) {
                $query->select('id', 'name', 'symbol');
            }
        ])->get();
    }

    public function getById($id)
    {
        return WalletAccountType::findOrfail($id);
    }
}
