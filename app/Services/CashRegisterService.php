<?php

namespace App\Services;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;

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
    * | Open cash register
    */
    public function openCashRegister(array $data)
    {
        try {

                /*
                * Determine if the user who is opening the cash register has any asscociated and active cash register
                */
                $cash_register_is_active = CashRegister::where('seller_user_opening_id', $data['seller_user_opening_id'])
                                    ->where('is_open', 1)
                                    ->first();
                if ($cash_register_is_active) {
                    throw new \Exception('El usuario ya tiene una caja abierta');
                }



        } catch (\Exception $e) {
            throw $e;
        }
    }
}
