<?php

namespace App\Repositories;

use App\Interfaces\WalletAccountWalletAccountTypeRepositoryInterface;
use App\Models\WalletAccountWalletAccountType;

class WalletAccountWalletAccountTypeRepository implements WalletAccountWalletAccountTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function save(array $data)
    {
        return WalletAccountWalletAccountType::create($data);
    }

    public function update(int $id, array $data)
    {
        return WalletAccountWalletAccountType::whereId($id)->update($data);
    }
}
