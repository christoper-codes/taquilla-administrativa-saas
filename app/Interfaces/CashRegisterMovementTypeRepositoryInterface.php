<?php

namespace App\Interfaces;

interface CashRegisterMovementTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */

    public function getByName($name);
}
