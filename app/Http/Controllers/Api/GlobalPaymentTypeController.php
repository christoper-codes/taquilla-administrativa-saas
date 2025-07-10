<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GlobalPaymentTypeService;

class GlobalPaymentTypeController extends Controller
{
    protected $global_payment_type_service;

    public function __construct(GlobalPaymentTypeService $global_payment_type_service)
    {
        $this->global_payment_type_service = $global_payment_type_service;
    }

    public function getAll()
    {
        try {

            $wallet_transaction_type = $this->global_payment_type_service->getAll();

            if($wallet_transaction_type->count()){
                return response()->json([
                    'data' => $wallet_transaction_type,
                    'message' => 'Tipos de pagos encontrados con éxito!',
                ], 200);
            }

            return response()->json([
                'data' => $wallet_transaction_type,
                'message' => 'No se encontro tipos de pagos!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
