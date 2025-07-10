<?php

namespace App\Services;

use App\Interfaces\WalletTransactionTypeRepositoryInterface;

class WalletTransactionTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletTransactionTypeService the repository services for global module
    */
    protected $wallet_transaction_type_repository_interface;

    public function __construct(WalletTransactionTypeRepositoryInterface $wallet_transaction_type_repository_interface)
    {
        $this->wallet_transaction_type_repository_interface = $wallet_transaction_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all wallet account catalogue
    */
    public function getAll()
    {
        try {

            return $this->wallet_transaction_type_repository_interface->getAll();

        } catch (\Exception $e) {

            throw $e;
        }
    }
    public function getById(int $id)
    {
        try {

            return $this->wallet_transaction_type_repository_interface->getById($id);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
