<?php
namespace App\Repositories;

use App\Interfaces\CardPaymentDetailRepositoryInterface;
use App\Models\CardPaymentDetail;

class CardPaymentDetailRepository implements CardPaymentDetailRepositoryInterface
{

    public function getAll(){

        return CardPaymentDetail::all();
    }

    public function create(array $data){
        return CardPaymentDetail::create($data);
    }

}
