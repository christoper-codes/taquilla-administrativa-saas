<?php

namespace App\Services;

use App\Repositories\CashRegisterMovementTypeRepository;

class CashRegisterMovementTypeService
{
     /*
    * |--------------------------------------------------------------------------
    * | CashRegisterMovementTypeService the repository services for global module by Christoper Patiño
    */
    protected $cash_register_movement_type_repository;

    public function __construct(CashRegisterMovementTypeRepository $cash_register_movement_type_repository)
    {
        $this->cash_register_movement_type_repository = $cash_register_movement_type_repository;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by name
    */
    public function getByName($name)
    {
        try {

            return $this->cash_register_movement_type_repository->getByName($name);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
