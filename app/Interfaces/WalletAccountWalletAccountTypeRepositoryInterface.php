<?php

namespace App\Interfaces;

interface WalletAccountWalletAccountTypeRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function save(array $data);
    public function update(int $id, array $data);
}
