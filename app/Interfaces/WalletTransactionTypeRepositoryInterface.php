<?php

namespace App\Interfaces;

interface WalletTransactionTypeRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll();
    public function getById(int $id);
}
