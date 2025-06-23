<?php

namespace App\Interfaces;

interface WalletCurrencyRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll();
    public function getById($id);
}
