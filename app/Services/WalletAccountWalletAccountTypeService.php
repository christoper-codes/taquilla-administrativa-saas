<?php

namespace App\Services;

use App\Interfaces\WalletAccountWalletAccountTypeRepositoryInterface;

class WalletAccountWalletAccountTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletAccountService the repository services for global module
    */

    protected $wallet_account_wallet_account_type_repository_interface;

    public function __construct(WalletAccountWalletAccountTypeRepositoryInterface $wallet_account_wallet_account_type_repository_interface)
    {
        $this->wallet_account_wallet_account_type_repository_interface = $wallet_account_wallet_account_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new wallet account wallet account type catalogue
    */
    public function save(array $data)
    {
        try {

            $wallet_account_wallet_account_type = $this->wallet_account_wallet_account_type_repository_interface->save($data);

            return $wallet_account_wallet_account_type;

        } catch (\Exception $e) {

            throw $e;
        }
    }


    /*
    * |--------------------------------------------------------------------------
    * | updated wallet account wallet account type catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $wallet_account_wallet_account_type = $this->wallet_account_wallet_account_type_repository_interface->update($id, $data);

            return $wallet_account_wallet_account_type;

        } catch (\Exception $e) {

            throw $e;
        }
    }

}
