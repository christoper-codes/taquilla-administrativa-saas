<?php

namespace App\Services;

use App\Interfaces\WalletAccountTypeRepositoryInterface;

class WalletAccountTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletAccountTypeService the repository services for global module
    */

    protected $wallet_account_type_repository_interface;

    public function __construct(WalletAccountTypeRepositoryInterface $wallet_account_type_repository_interface)
    {
        $this->wallet_account_type_repository_interface = $wallet_account_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all Wallet Currency
    */
    public function getAll()
    {
        try {

            $wallet_account_type = $this->wallet_account_type_repository_interface->getAll();

            return $wallet_account_type;

        } catch (\Exception $e) {

            throw $e;

        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get Wallet Currency by name
    */
    public function getById($id)
    {
        try {

            $wallet_currency = $this->wallet_account_type_repository_interface->getById($id);

            return $wallet_currency;

        } catch (\Exception $e) {

            throw $e;

        }
    }
}
