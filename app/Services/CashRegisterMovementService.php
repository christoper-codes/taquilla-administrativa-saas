<?php

namespace App\Services;

use App\Interfaces\CashRegisterMovementRepositoryInterface;

class CashRegisterMovementService
{
     /*
    * |--------------------------------------------------------------------------
    * | CashRegisterMovementService the repository services for global module by Christoper Patiño
    */

    protected $cash_register_movement_repository;

    public function __construct(CashRegisterMovementRepositoryInterface $cash_register_movement_repository)
    {
        $this->cash_register_movement_repository = $cash_register_movement_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new cash register movement
    */
    public function save(array $data)
    {
        try {

            return  $this->cash_register_movement_repository->save($data);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
