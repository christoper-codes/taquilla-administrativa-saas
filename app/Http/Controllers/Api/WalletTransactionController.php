<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletTransactionService;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    protected $wallet_transaction_service;

    public function __construct(WalletTransactionService $wallet_transaction_service)
    {
        $this->wallet_transaction_service = $wallet_transaction_service;
    }

    public function storePurchase(Request $request)
    {

        $request->validate([
            'wallet_account_id' => 'required|integer|exists:wallet_accounts,id',
            'wallet_account_number' => 'required|string|exists:wallet_accounts,account_number',
            'wallet_transaction_type_id' => 'required|integer|exists:wallet_transaction_types,id',
            'pos_ticket_id' => 'nullable|integer',
            'pos_cash_register_id' => 'nullable|integer',
            'seller_id' => 'nullable|integer',
            'movement_amount' => 'required|numeric',
            'cash_back' => 'required|numeric',
            'payment_type_is_cashback' => 'required|boolean',
            'compound_payment' => 'nullable|boolean',
            'movement_amount_compound_payment' => 'nullable|numeric',
            'paid_with_cashless' => 'required|boolean'
        ]);

        try {

            $wallet_transaction = $this->wallet_transaction_service->storePurchase($request->only(
                'wallet_account_id',
                'wallet_account_number',
                'wallet_transaction_type_id',
                'pos_ticket_id',
                'pos_cash_register_id',
                'seller_id',
                'movement_amount',
                'cash_back',
                'payment_type_is_cashback',
                'compound_payment',
                'movement_amount_compound_payment',
                'paid_with_cashless'
            ));

            return response()->json([
                'data' => $wallet_transaction,
                'message' => 'transaccion registrada correctamente.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeRecharge(Request $request)
    {

        $request->validate([
            'wallet_account_id' => 'required|integer|exists:wallet_accounts,id',
            'wallet_account_number' => 'required|string|exists:wallet_accounts,account_number',
            'wallet_transaction_type_id' => 'required|integer|exists:wallet_transaction_types,id',
            'wallet_recharge_amount_id' => 'required|integer|exists:wallet_recharge_amounts,id',
            'seller_id' => 'required|integer',
            'pos_cash_register_id' => 'required|integer',
            'movement_amount' => 'required|numeric',
            'global_payment_type_id' => 'required|integer',
            'global_type_card_payment_id' => 'nullable|integer',
        ]);

        try {

            $wallet_transaction = $this->wallet_transaction_service->storeRecharge($request->only(
                'wallet_account_id',
                'wallet_account_number',
                'wallet_transaction_type_id',
                'wallet_recharge_amount_id',
                'seller_id',
                'pos_cash_register_id',
                'movement_amount',
                'global_payment_type_id',
                'global_type_card_payment_id'
            ));

            return response()->json([
                'data' => $wallet_transaction,
                'message' => 'transaccion registrada correctamente.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeTransactionCancel(Request $request)
    {
        $request->validate([
            'wallet_account_number' => 'required|string|exists:wallet_accounts,account_number',
            'pos_ticket_id' => 'nullable|integer',
            'pos_cash_register_id' => 'nullable|integer',
            'wallet_transaction_type_id' => 'nullable|integer',
            'cashback_to_remove' => 'required|numeric',
            'cashback_to_add' => 'required|numeric',
        ]);

        try {

            $wallet_transaction = $this->wallet_transaction_service->storeTransactionCancel($request->only(
                'wallet_account_number',
                'pos_ticket_id',
                'pos_cash_register_id',
                'wallet_transaction_type_id',
                'cashback_to_remove',
                'cashback_to_add'
            ));

            return response()->json([
                'data' => $wallet_transaction,
                'message' => 'transaccion registrada correctamente.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getRechargeAmountHistoryByCashRegister(Request $request)
    {
        $request->validate([ 'pos_cash_register_id' => 'required']);

        try {

            $wallet_transaction = $this->wallet_transaction_service->getRechargeAmountHistoryByCashRegister($request->pos_cash_register_id);

            if($wallet_transaction->count()){
                return response()->json([
                    'data' => $wallet_transaction,
                    'message' => 'Tipos de transacciones encontradas con exito!',
                ], 200);
            }

            return response()->json([
                'data' => $wallet_transaction,
                'message' => 'No se encontro tipos de transacciones!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
