<?php

namespace App\Interfaces;

use App\Models\WalletAccount;

interface WalletAccountRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll($maskWalletAccountNumber);
    public function save(array $data);
    public function update(int $id, array $data);
    public function getByAccountNumber(string $account_number, $maskWalletAccountNumber);
    public function getByUser(int $id, $maskWalletAccountNumber);
    public function getBySeasonTicketUser(int $id, $maskWalletAccountNumber);
    public function getHistoryByAccountNumber(string $account_number);
    public function prepareWalletAccountData(WalletAccount $wallet_account, $maskWalletAccountNumber);
    public function generateUniqueAccountNumber();
}
