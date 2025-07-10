<?php

namespace App\Repositories;

use App\Interfaces\WalletTransactionTypeRepositoryInterface;
use App\Models\WalletTransactionType;

class WalletTransactionTypeRepository implements WalletTransactionTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return WalletTransactionType::where('is_active', true)->get();
    }

    public function getById(Int $id)
    {
        return WalletTransactionType::find($id);
    }
}
