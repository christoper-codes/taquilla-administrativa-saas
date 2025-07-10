<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletTransactionTypeService;

class WalletTransactionTypeController extends Controller
{
    protected $wallet_transaction_type_service;

    public function __construct(WalletTransactionTypeService $wallet_transaction_type_service)
    {
        $this->wallet_transaction_type_service = $wallet_transaction_type_service;
    }

    public function getAll()
    {
        try {

            $wallet_transaction_type = $this->wallet_transaction_type_service->getAll();

            if($wallet_transaction_type->count()){
                return response()->json([
                    'data' => $wallet_transaction_type,
                    'message' => 'Tipos de transacciones encontradas con éxito!',
                ], 200);
            }

            return response()->json([
                'data' => $wallet_transaction_type,
                'message' => 'No se encontro tipos de transacciones!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
