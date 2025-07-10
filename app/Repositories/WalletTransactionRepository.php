<?php

namespace App\Repositories;

use App\Interfaces\WalletTransactionRepositoryInterface;
use App\Models\WalletTransaction;

class WalletTransactionRepository implements WalletTransactionRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function store(array $data)
    {
        return WalletTransaction::create($data);
    }

    public function findByPosTicketId(int $id)
    {
        return WalletTransaction::where('pos_ticket_id', $id)->first();
    }

    public function getRechargeAmountHistoryByCashRegister(int $pos_cash_register_id)
    {
        return  WalletTransaction::with('walletAccount')->where('pos_cash_register_id', $pos_cash_register_id)->get();
    }

}
