<?php

namespace App\Services;

use App\Interfaces\InstallmentPaymentHistoryRepositoryInterface;

class InstallmentPaymentHistoryService
{
    /*
     * |--------------------------------------------------------------------------
     * | InstallmentPaymentHistoryService the repository services for global module by Christoper Patiño
     */

    protected $installment_payment_history_repository_interface;

    public function __construct(InstallmentPaymentHistoryRepositoryInterface $installment_payment_history_repository_interface)
    {
        $this->installment_payment_history_repository_interface = $installment_payment_history_repository_interface;
    }


    /*
     * |--------------------------------------------------------------------------
     * | Get all installment payment history catalogues
     */
    public function getAll()
    {
        $installment_payment_histories = $this->installment_payment_history_repository_interface->getAll();

        return $installment_payment_histories;
    }

    /*
     * |--------------------------------------------------------------------------
     * | Save new installment payment history catalogue
     */
    public function save(array $data)
    {
        try {

            $installment_payment_history = $this->installment_payment_history_repository_interface->save($data);

            return $installment_payment_history;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
     * |--------------------------------------------------------------------------
     * | update installment payment history catalogue
     */
    public function update(int $id, array $data)
    {
        try {

            $installment_payment_history = $this->installment_payment_history_repository_interface->update($id, $data);

            return $installment_payment_history;

        } catch (\Exception $e) {

            throw $e;
        }
    }
    /*
     * |--------------------------------------------------------------------------
     * | delete installment payment history catalogue
     */
    public function delete(int $id)
    {
        try {

            $installment_payment_history = $this->installment_payment_history_repository_interface->delete($id);

            return $installment_payment_history;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
