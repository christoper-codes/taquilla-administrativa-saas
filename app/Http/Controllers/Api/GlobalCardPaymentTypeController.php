<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GlobalCardPaymentTypeService;

class GlobalCardPaymentTypeController extends Controller
{
    protected $global_card_payment_type_service;

    public function __construct(GlobalCardPaymentTypeService $global_card_payment_type_service)
    {
        $this->global_card_payment_type_service = $global_card_payment_type_service;
    }

    public function getAll()
    {
        try {

            $wallet_transaction_type = $this->global_card_payment_type_service->getAll();

            if($wallet_transaction_type->count()){
                return response()->json([
                    'data' => $wallet_transaction_type,
                    'message' => 'Tipos de tarjetas encontrados con éxito!',
                ], 200);
            }

            return response()->json([
                'data' => $wallet_transaction_type,
                'message' => 'No se encontro tipos de tarjetas!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
