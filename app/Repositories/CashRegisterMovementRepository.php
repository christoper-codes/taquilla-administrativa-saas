<?php

namespace App\Repositories;

use App\Interfaces\CashRegisterMovementRepositoryInterface;
use App\Models\CashRegisterMovement;

class CashRegisterMovementRepository implements CashRegisterMovementRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function save(array $data)
    {
        return CashRegisterMovement::create($data);
    }
}
