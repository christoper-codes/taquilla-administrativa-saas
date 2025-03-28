<?php

namespace App\Repositories;

use App\Interfaces\InstallmentPaymentHistoryRepositoryInterface;
use App\Models\InstallmentPaymentHistory;

class InstallmentPaymentHistoryRepository implements InstallmentPaymentHistoryRepositoryInterface
{
    /*
     * |--------------------------------------------------------------------------
     * | Primaries methods for the repository interface
     */
    public function getAll()
    {
        return InstallmentPaymentHistory::with('saleTicket', 'globalPaymentTypes')->get();
    }

    public function getById($id)
    {
        return InstallmentPaymentHistory::findOrfail($id);
    }

    public function save(array $data)
    {
        return InstallmentPaymentHistory::create($data);
    }

    public function update($id, array $data)
    {
        return InstallmentPaymentHistory::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return InstallmentPaymentHistory::destroy($id);
    }

    /*
     * |--------------------------------------------------------------------------
     * | Custom methods for the repository interface
     */

}
