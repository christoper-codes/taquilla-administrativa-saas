<?php

namespace App\Services;

use App\Interfaces\WalletAccountRepositoryInterface;
use App\Models\WalletAccount;

class WalletAccountService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletAccountService the repository services for global module
    */

    protected $wallet_account_repository_interface;

    public function __construct(WalletAccountRepositoryInterface $wallet_account_repository_interface)
    {
        $this->wallet_account_repository_interface = $wallet_account_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all wallet account catalogue
    */
    public function getAll($maskWalletAccountNumber = true)
    {
        try {

            $wallet_accounts = $this->wallet_account_repository_interface->getAll($maskWalletAccountNumber);

            return $wallet_accounts;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new wallet account catalogue
    */
    public function save(array $data)
    {
        try {

            $wallet_account = $this->wallet_account_repository_interface->save($data);

            return $wallet_account;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | updated wallet account catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $wallet_account = $this->wallet_account_repository_interface->update($id, $data);

            return $wallet_account;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get wallet account by id
    */
    public function getByAccountNumber($account_number, $maskWalletAccountNumber = true)
    {
        try {

            $wallet_account = $this->wallet_account_repository_interface->getByAccountNumber($account_number, $maskWalletAccountNumber);

            return $wallet_account;

        } catch (\Exception $e) {

            throw $e;

        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get wallet account by id
    */
    public function getByUser($id, $maskWalletAccountNumber = true)
    {
        try {

            $wallet_accounts_by_user = $this->wallet_account_repository_interface->getByUser($id, $maskWalletAccountNumber);

            $wallet_accounts_by_season_ticket = $this->wallet_account_repository_interface->getBySeasonTicketUser($id, $maskWalletAccountNumber);

            $all_wallet_accounts = $wallet_accounts_by_user->concat($wallet_accounts_by_season_ticket);

            return $all_wallet_accounts;

        } catch (\Exception $e) {

            throw $e;

        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get wallet account by id
    */
    public function getHistoryByAccountNumber(string $account_number)
    {
        try {

            $wallet_account = $this->wallet_account_repository_interface->getHistoryByAccountNumber($account_number);

            return $wallet_account;

        } catch (\Exception $e) {

            throw $e;

        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get wallet account by id
    */
    public function generateUniqueAccountNumber()
    {
        try {

            $unique_account_number = $this->wallet_account_repository_interface->generateUniqueAccountNumber();

            return $unique_account_number;

        } catch (\Exception $e) {

            throw $e;

        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get wallet account by id
    */
    public function prepareWalletAccountData(WalletAccount $wallet_account, $maskWalletAccountNumber)
    {
        try {

            return $this->wallet_account_repository_interface->prepareWalletAccountData($wallet_account, $maskWalletAccountNumber);

        } catch (\Exception $e) {

            throw $e;

        }
    }
}
