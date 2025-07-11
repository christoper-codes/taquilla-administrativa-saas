<?php

namespace App\Services;

use App\Interfaces\WalletTransactionRepositoryInterface;
use App\Models\WalletTransactionStatus;

class WalletTransactionService
{
    /*
    * |--------------------------------------------------------------------------
    * | WalletTransactionService the repository services for global module
    */
    protected $wallet_transaction_repository_interface;
    protected $wallet_transaction_type_service;
    protected $wallet_account_service;
    protected $wallet_recharge_amount_service;
    protected $global_card_payment_type_service;

    public function __construct(WalletTransactionRepositoryInterface $wallet_transaction_repository_interface, WalletTransactionTypeService $wallet_transaction_type_service, WalletAccountService $wallet_account_service,
                                WalletRechargeAmountService $wallet_recharge_amount_service, GlobalCardPaymentTypeService $global_card_payment_type_service)
    {
        $this->wallet_transaction_repository_interface = $wallet_transaction_repository_interface;
        $this->wallet_transaction_type_service = $wallet_transaction_type_service;
        $this->wallet_account_service = $wallet_account_service;
        $this->wallet_recharge_amount_service = $wallet_recharge_amount_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
    }

    /**
     * Store a purchase transaction in the wallet.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function storePurchase(array $data)
    {
        try {
            $wallet_account = $this->wallet_account_service->getByAccountNumber($data['wallet_account_number'], false);

            $cashbackBalanceBeforeTransaction = $wallet_account->walletAccountTypes->where('name', 'cashback')->first()->pivot->current_balance;
            $cashlessBalanceBeforeTransaction = optional($wallet_account->walletAccountTypes->where('name', 'cashless')->first())->pivot->current_balance ?? 0;

            if($data['paid_with_cashless']){
                if($cashlessBalanceBeforeTransaction < $data['movement_amount']){
                    throw new \Exception('El saldo cashless no es suficiente para realizar la transaccion.');
                }else{
                    $cashlessBalanceAfterTransaction = ($cashlessBalanceBeforeTransaction - $data['movement_amount']);
                }
            } else {
                $cashlessBalanceAfterTransaction = $cashlessBalanceBeforeTransaction;
            }

            if($data['payment_type_is_cashback']){
                $cashbackBalanceAccountAfterTransaction = ($cashbackBalanceBeforeTransaction - $data['movement_amount']);
            } else if($data['compound_payment']) {
                $cashbackBalanceAccountAfterTransaction = ($cashbackBalanceBeforeTransaction - $data['movement_amount_compound_payment']) + $data['cash_back'];
            } else {
                $cashbackBalanceAccountAfterTransaction = $cashbackBalanceBeforeTransaction+ $data['cash_back'];
            }


            $cashlessType = $wallet_account->walletAccountTypes->firstWhere('name', 'cashless');
            $cashbackType = $wallet_account->walletAccountTypes->firstWhere('name', 'cashback');

            if ($cashlessType) {
                $wallet_account->walletAccountTypes()->updateExistingPivot(
                    $cashlessType->id,
                    ['current_balance' => $cashlessBalanceAfterTransaction]
                );
            }

            if ($cashbackType) {
                $wallet_account->walletAccountTypes()->updateExistingPivot(
                    $cashbackType->id,
                    ['current_balance' => $cashbackBalanceAccountAfterTransaction]
                );
            }

            $data['wallet_transaction_status_id'] = WalletTransactionStatus::where('name', 'pagado')->first()->id;
            $data['description'] = 'Compra de productos';
            $data['cash_back_generated'] = $data['cash_back'];
            $data['paid_with_cashback'] = $data['payment_type_is_cashback'];
            $data['paid_with_cashless'] = $data['paid_with_cashless'];
            $data['cashless_balance_account_before_transaction'] = $cashlessBalanceBeforeTransaction;
            $data['cashless_balance_account_after_transaction'] = $cashlessBalanceAfterTransaction;
            $data['cashback_balance_account_before_transaction'] = $cashbackBalanceBeforeTransaction;
            $data['cashback_balance_account_after_transaction'] = $cashbackBalanceAccountAfterTransaction;

            return $this->wallet_transaction_repository_interface->store($data);

        } catch (\Exception $e) {

            throw $e;
        }
    }


    /**
     * Store a purchase transaction in the wallet.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function storeRecharge(array $data)
    {
        try {
            $wallet_account = $this->wallet_account_service->getByAccountNumber($data['wallet_account_number'], false);

            $cashbackBalanceBeforeTransaction = $wallet_account->walletAccountTypes->where('name', 'cashback')->first()->pivot->current_balance;
            $cashlessBalanceBeforeTransaction = $wallet_account->walletAccountTypes->where('name', 'cashless')->first()->pivot->current_balance;

            $wallet_recharge_amount = $this->wallet_recharge_amount_service->find($data['wallet_recharge_amount_id']);

            $cashlessBalanceAfterTransaction = $cashlessBalanceBeforeTransaction + $wallet_recharge_amount->amount;
            $cashbackBalanceAccountAfterTransaction = $cashbackBalanceBeforeTransaction;

            $cashlessType = $wallet_account->walletAccountTypes->firstWhere('name', 'cashless');

            if ($cashlessType) {
                $wallet_account->walletAccountTypes()->updateExistingPivot(
                    $cashlessType->id,
                    ['current_balance' => $cashlessBalanceAfterTransaction]
                );
            }

            $data['wallet_transaction_status_id'] = WalletTransactionStatus::where('name', 'pagado')->first()->id;
            $data['description'] = 'Recarga de billetera';
            $data['cashless_balance_account_before_transaction'] = $cashlessBalanceBeforeTransaction;
            $data['cashless_balance_account_after_transaction'] = $cashlessBalanceAfterTransaction;
            $data['cashback_balance_account_before_transaction'] = $cashbackBalanceBeforeTransaction;
            $data['cashback_balance_account_after_transaction'] = $cashbackBalanceAccountAfterTransaction;

            $wallet_transaction = $this->wallet_transaction_repository_interface->store($data);

            $wallet_transaction->globalPaymentTypes()->attach($data['global_payment_type_id'], [
                'global_card_payment_type_id' => $data['global_type_card_payment_id'] ?? null,
                'amount' => $wallet_recharge_amount->amount,
                'original_amount' => $wallet_recharge_amount->amount,
            ]);

            $globalCardPaymentType = $this->global_card_payment_type_service->getAll();

            $wallet_transaction->globalPaymentTypes->each(function ($global_payment_type) use ($globalCardPaymentType) {
                if ($global_payment_type->pivot->global_card_payment_type_id) {
                    $globalCardPaymentType = $globalCardPaymentType->where('id', $global_payment_type->pivot->global_card_payment_type_id)->first();
                    if ($globalCardPaymentType) {
                        $global_payment_type->name .= " (".$globalCardPaymentType->name.")";
                    }
                }
            });

            return $wallet_transaction;

        } catch (\Exception $e) {

            throw $e;
        }
    }


    /**
     * Store a purchase transaction in the wallet.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function storeTransactionCancel(array $data)
    {
        try {

            $wallet_transaction = $this->wallet_transaction_repository_interface->findByPosTicketId($data['pos_ticket_id']);
            $wallet_account = $this->wallet_account_service->getByAccountNumber($data['wallet_account_number'], false);

            $cashlessBalanceBeforeTransaction = optional($wallet_account->walletAccountTypes->where('name', 'cashless')->first())->pivot->current_balance ?? 0;
            $cashbackBalanceBeforeTransaction = $wallet_account->walletAccountTypes->where('name', 'cashback')->first()->pivot->current_balance;

            $cashbackBalanceAfterTransaction = ($cashbackBalanceBeforeTransaction - $data['cashback_to_remove']) + $data['cashback_to_add'];

            $cashbackType = $wallet_account->walletAccountTypes->firstWhere('name', 'cashback');

            if ($cashbackType) {
                $wallet_account->walletAccountTypes()->updateExistingPivot(
                    $cashbackType->id,
                    ['current_balance' => $cashbackBalanceAfterTransaction]
                );
            }

            $data['wallet_account_id'] = $wallet_account->id;
            $data['wallet_transaction_status_id'] = WalletTransactionStatus::where('name', 'cancelado')->first()->id;
            $data['description'] = 'Cancelacion de transaccion';
            $data['cashless_balance_account_before_transaction'] = $cashlessBalanceBeforeTransaction;
            $data['cashless_balance_account_after_transaction'] = $cashlessBalanceBeforeTransaction;
            $data['cashback_balance_account_before_transaction'] = $cashbackBalanceBeforeTransaction;
            $data['cashback_balance_account_after_transaction'] = $cashbackBalanceAfterTransaction;
            $data['related_transaction_id'] = $wallet_transaction->id;

            $wallet_transaction = $this->wallet_transaction_repository_interface->store($data);

            return $wallet_transaction;

        } catch (\Exception $e) {

            throw $e;
        }
    }


    /**
     * Get recharge amount history by cash register.
     *
     * @param int $pos_cash_register_id
     * @return mixed
     * @throws \Exception
     */
    public function getRechargeAmountHistoryByCashRegister(int $pos_cash_register_id)
    {
        try {

            $wallet_transaction = $this->wallet_transaction_repository_interface->getRechargeAmountHistoryByCashRegister($pos_cash_register_id);

            $wallet_transaction->each(function ($transaction) {
                $transaction->wallet_account = $this->wallet_account_service->prepareWalletAccountData($transaction->walletAccount, false);
            });

            return $wallet_transaction;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
