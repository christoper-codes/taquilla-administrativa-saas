<?php

namespace App\Services;

use App\Interfaces\CardPaymentDetailRepositoryInterface;
use App\Models\CardPaymentDetail;

class CardPaymentDetailService
{

    protected $card_payment_detail_repository;

    public function __construct(CardPaymentDetailRepositoryInterface $card_payment_detail_repository)
    {
        $this->card_payment_detail_repository = $card_payment_detail_repository;
    }



    public function getAll()
    {
        try {
            $card_payment_detail = $this->card_payment_detail_repository->getAll();
            return $card_payment_detail;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $card_payment_detail = $this->card_payment_detail_repository->create($data);
            return $card_payment_detail;
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
