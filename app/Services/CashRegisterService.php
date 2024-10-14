<?php

namespace App\Services;

use App\Interfaces\CashRegisterRepositoryInterface;

class CashRegisterService
{
     /*
    * |--------------------------------------------------------------------------
    * | CashRegisterService the repository services for global module by Christoper Patiño
    */

    protected $cash_register_repository;

    public function __construct(CashRegisterRepositoryInterface $cash_register_repository)
    {
        $this->cash_register_repository = $cash_register_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all cash registers
    */

    public function getAll()
    {
        try {
            $cash_register = $this->cash_register_repository->getAll();
            return $cash_register;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by id
    */
    public function getById($id)
    {
        try {
            $cash_register = $this->cash_register_repository->getById($id);
            return $cash_register;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save cash register
    */
    public function save(array $data)
    {
        try {
            $cash_register = $this->cash_register_repository->save($data);
            return $cash_register;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
