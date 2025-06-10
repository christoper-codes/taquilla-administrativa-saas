<?php

namespace App\Services;

use App\Interfaces\OnlinePaymentTransactionRepositoryInterface;

class OnlinePaymentTransactionService
{
    protected $online_payment_transaction_repository;

    public function __construct(OnlinePaymentTransactionRepositoryInterface $online_payment_transaction_repository )
    {
        $this->online_payment_transaction_repository = $online_payment_transaction_repository;
    }

     /*
    * |--------------------------------------------------------------------------
    * | Save new online payment transaction
    */
    public function save(array $data)
    {
        try {
            $zanitized_data = [
                'sale_ticket_id' => $data['sale_ticket_id'],
                'data' => $data['online_payment_transaction'],
                'source' => $data['online_payment_transaction']['source'] ?? 'web',
            ];

            return $this->online_payment_transaction_repository->save($zanitized_data);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
