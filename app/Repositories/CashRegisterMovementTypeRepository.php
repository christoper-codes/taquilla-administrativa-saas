<?php

namespace App\Repositories;

use App\Interfaces\CashRegisterMovementTypeRepositoryInterface;
use App\Models\CashRegisterMovementType;

class CashRegisterMovementTypeRepository implements CashRegisterMovementTypeRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getByName($name)
    {
        return CashRegisterMovementType::where('name', $name)->first();
    }
}
