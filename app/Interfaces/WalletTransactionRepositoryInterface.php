<?php

namespace App\Interfaces;

interface WalletTransactionRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function store(array $data);
    public function findByPosTicketId(int $id);
    public function getRechargeAmountHistoryByCashRegister(int $pos_cash_register_id);
}
