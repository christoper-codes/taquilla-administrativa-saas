<?php

namespace App\Interfaces;

interface WalletAccountTypeRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll();
    public function getById($name);
}
