<?php

namespace App\Services;

use App\Interfaces\WalletRechargeAmountRepositoryInterface;

class WalletRechargeAmountService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletRechargeAmountService the repository services for global module
    */
    protected $wallet_recharge_amount_repository_interface;

    public function __construct(WalletRechargeAmountRepositoryInterface $wallet_recharge_amount_repository_interface)
    {
        $this->wallet_recharge_amount_repository_interface = $wallet_recharge_amount_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all wallet account catalogue
    */
    public function getAll()
    {
        try {

            $wallet_recharge_amounts = $this->wallet_recharge_amount_repository_interface->getAll();

            return $wallet_recharge_amounts;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    public function find(int $id)
    {
        try {

            return $this->wallet_recharge_amount_repository_interface->find($id);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
