<?php

namespace App\Repositories;

use App\Interfaces\WalletCurrencyRepositoryInterface;
use App\Models\WalletCurrency;

class WalletCurrencyRepository implements WalletCurrencyRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return WalletCurrency::all();
    }

    public function getById($id)
    {
        return WalletCurrency::findOrfail($id);
    }
}
