<?php

namespace App\Services;

use App\Interfaces\WalletCurrencyRepositoryInterface;

class WalletCurrencyService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletCurrencyService the repository services for global module
    */

    protected $wallet_currency_repository_interface;

    public function __construct(WalletCurrencyRepositoryInterface $wallet_currency_repository_interface)
    {
        $this->wallet_currency_repository_interface = $wallet_currency_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all Wallet Currency
    */
    public function getAll()
    {
        try {

            $wallet_currencies = $this->wallet_currency_repository_interface->getAll();

            return $wallet_currencies;

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

            $wallet_currency = $this->wallet_currency_repository_interface->getById($id);

            return $wallet_currency;

        } catch (\Exception $e) {

            throw $e;

        }
    }
}
