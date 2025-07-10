<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletRechargeAmountService;

class WalletRechargeAmountController extends Controller
{
    protected $wallet_recharge_amount;

    public function __construct(WalletRechargeAmountService $wallet_recharge_amount)
    {
        $this->wallet_recharge_amount = $wallet_recharge_amount;
    }

    public function getAll()
    {
        try {

            $wallet_recharge_amount = $this->wallet_recharge_amount->getAll();

            if($wallet_recharge_amount->count()){
                return response()->json([
                    'data' => $wallet_recharge_amount,
                    'message' => 'Tipos de recargas encontradas con éxito!',
                ], 200);
            }

            return response()->json([
                'data' => $wallet_recharge_amount,
                'message' => 'No se encontro tipos de recargas!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
