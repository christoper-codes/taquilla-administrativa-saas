<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WebResponseHelper;
use App\Models\CardPaymentDetail;
use App\Services\CardPaymentDetailService;
use Inertia\Inertia;


class CardPaymentDetailController extends Controller
{
    protected $card_payment_detail_service;

    public function __construct(CardPaymentDetailService $card_payment_detail_service)
    {
        $this->card_payment_detail_service = $card_payment_detail_service;
    }

    public function index(){
        try {

            $card_payment_details = $this->card_payment_detail_service->getAll();

        } catch (\Throwable $th) {

        }
    }

    public function create(Resquest $request){

        try {

            $card_payment_detail = $this->card_payment_detail_service->create($request);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }


}
