<?php

namespace App\Repositories;

use App\Interfaces\WalletAccountRepositoryInterface;
use App\Models\GlobalCardPaymentType;
use App\Models\SeasonTicket;
use App\Models\WalletAccount;

class WalletAccountRepository implements WalletAccountRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */

    public function getAll($maskWalletAccountNumber)
    {
        return WalletAccount::select(
            'id',
            'user_id',
            'account_number',
            'created_at'
        )
        ->with([
            'walletAccountTypes' => function($walletAccountTypes){
                $walletAccountTypes->select(
                    'wallet_account_types.id',
                    'wallet_account_types.wallet_currency_id',
                    'wallet_account_types.name',
                    'wallet_account_types.color'
                )->with(['walletCurrency' => function($walletCurrency){
                    $walletCurrency->select(
                        'id',
                        'name',
                        'symbol'
                    );
                }]);
            },
            'seasonTickets' => function($seasonTickets){
                $seasonTickets->select(
                    'season_tickets.id',
                    'season_tickets.user_id',
                    'season_tickets.seat_catalogue_id',
                    'season_tickets.global_season_id',
                    'season_tickets.serie_id',
                    'season_tickets.holder_name',
                    'season_tickets.holder_middle_name',
                    'season_tickets.holder_last_name',
                    'season_tickets.holder_email',
                    'season_tickets.holder_phone',
                )->with(['seatCatalogue']);
            },
            'user'
        ])
        ->get()
        ->map(function (WalletAccount $wallet_account) use ($maskWalletAccountNumber){
            return $this->prepareWalletAccountData($wallet_account, $maskWalletAccountNumber);
        });
    }

    public function save(array $data)
    {
        return WalletAccount::create($data);
    }

    public function update(int $id, array $data)
    {
        return WalletAccount::whereId($id)->update($data);
    }

    public function getByAccountNumber($account_number, $maskWalletAccountNumber)
    {
        $wallet_account = WalletAccount::select(
            'id',
            'user_id',
            'account_number',
            'created_at'
        )
        ->with([
            'walletAccountTypes' => function($walletAccountTypes){
                $walletAccountTypes->select(
                    'wallet_account_types.id',
                    'wallet_account_types.wallet_currency_id',
                    'wallet_account_types.name',
                    'wallet_account_types.color'
                )->with(['walletCurrency' => function($walletCurrency){
                    $walletCurrency->select(
                        'id',
                        'name',
                        'symbol'
                    );
                }]);
            },
            'seasonTickets' => function($seasonTickets){
                $seasonTickets->select(
                    'season_tickets.id',
                    'season_tickets.user_id',
                    'season_tickets.seat_catalogue_id',
                    'season_tickets.global_season_id',
                    'season_tickets.serie_id',
                    'season_tickets.holder_name',
                    'season_tickets.holder_middle_name',
                    'season_tickets.holder_last_name',
                    'season_tickets.holder_email',
                    'season_tickets.holder_phone',
                )->with(['seatCatalogue']);
            },
            'user',
            'walletAccountPrivilegeHistory'
        ])->where('account_number', $account_number)->where('is_active', true)->first();

        if(is_null($wallet_account)){
            return $wallet_account;
        }

        return $this->prepareWalletAccountData($wallet_account, $maskWalletAccountNumber);
    }

    public function getByUser($id, $maskWalletAccountNumber)
    {
        $wallet_accounts = WalletAccount::select(
            'id',
            'user_id',
            'account_number',
            'created_at'
        )
        ->with([
            'walletAccountTypes' => function($walletAccountTypes){
                $walletAccountTypes->select(
                    'wallet_account_types.id',
                    'wallet_account_types.wallet_currency_id',
                    'wallet_account_types.name',
                    'wallet_account_types.color'
                )->with(['walletCurrency' => function($walletCurrency){
                    $walletCurrency->select(
                        'id',
                        'name',
                        'symbol'
                    );
                }]);
            },
            'seasonTickets' => function($seasonTickets){
                $seasonTickets->select(
                    'season_tickets.id',
                    'season_tickets.user_id',
                    'season_tickets.seat_catalogue_id',
                    'season_tickets.global_season_id',
                    'season_tickets.serie_id',
                    'season_tickets.holder_name',
                    'season_tickets.holder_middle_name',
                    'season_tickets.holder_last_name',
                    'season_tickets.holder_email',
                    'season_tickets.holder_phone',
                )->with(['seatCatalogue']);
            },
            'user',
            'walletAccountPrivilegeHistory'
        ])->where('user_id', $id)->where('is_active', true)->get();

        return $wallet_accounts->map(function ($wallet_account) use ($maskWalletAccountNumber) {
            return $this->prepareWalletAccountData($wallet_account, $maskWalletAccountNumber);
        });
    }

    public function getBySeasonTicketUser($id, $maskWalletAccountNumber)
    {
        $seasonTicket =  SeasonTicket::select(
            'season_tickets.id',
            'season_tickets.is_active'
        )
        ->with([
            'walletAccounts' => function ($walletAccounts) {
                $walletAccounts->select(
                    'wallet_accounts.id',
                    'wallet_accounts.user_id',
                    'wallet_accounts.account_number',
                    'wallet_accounts.created_at'
                )
                ->with([
                    'walletAccountTypes' => function ($walletAccountTypes) {
                        $walletAccountTypes->select(
                            'wallet_account_types.id',
                            'wallet_account_types.wallet_currency_id',
                            'wallet_account_types.name',
                            'wallet_account_types.color'
                        )->with(['walletCurrency' => function ($walletCurrency) {
                            $walletCurrency->select(
                                'id',
                                'name',
                                'symbol'
                            );
                        }]);
                    },
                    'seasonTickets' => function ($seasonTickets) {
                        $seasonTickets->select(
                            'season_tickets.id',
                            'season_tickets.user_id',
                            'season_tickets.seat_catalogue_id',
                            'season_tickets.global_season_id',
                            'season_tickets.serie_id',
                            'season_tickets.holder_name',
                            'season_tickets.holder_middle_name',
                            'season_tickets.holder_last_name',
                            'season_tickets.holder_email',
                            'season_tickets.holder_phone',
                        )->with(['seatCatalogue']);
                    },
                    'user',
                    'walletAccountPrivilegeHistory'
                ])
                ->where('is_active', true);
            },
            'seatCatalogue'
        ])
        ->where('user_id', $id)
        ->where('is_active', true)
        ->get();

        $walletAccounts = $seasonTicket->pluck('walletAccounts')->collapse();

        return $walletAccounts->map(function ($wallet_account) use ($maskWalletAccountNumber) {
            return $this->prepareWalletAccountData($wallet_account, $maskWalletAccountNumber);
        });
    }

    public function getHistoryByAccountNumber(string $account_number)
    {
        $walletAccount = WalletAccount::with(['walletAccountTypes','walletTransaction' => function($walletTransaction) {
            $walletTransaction->with([
                'walletTransactionStatus',
                'walletTransactionType',
                'walletRechargeAmount',
                'globalPaymentTypes'
            ]);
        }])->where('account_number', $account_number)->first();

        foreach ($walletAccount->walletTransaction as $transaction) {

            $cardTypeIds = $transaction->globalPaymentTypes->pluck('pivot.global_card_payment_type_id')->filter()->unique();

            $cardTypes = GlobalCardPaymentType::whereIn('id', $cardTypeIds)->get()->keyBy('id');

            foreach ($transaction->globalPaymentTypes as $type) {
                $cardTypeId = $type->pivot->global_card_payment_type_id;
                if ($cardTypeId && $cardTypes->has($cardTypeId)) {
                    $type->name .= " ({$cardTypes[$cardTypeId]->name})";
                }
            }
        }

        return $walletAccount->walletTransaction;
    }

    public function prepareWalletAccountData(WalletAccount $wallet_account, $maskWalletAccountNumber){

        if($maskWalletAccountNumber){
            $wallet_account->account_number = maskWalletAccountNumber($wallet_account->account_number);
        }

        $data_user = null;

        if ($wallet_account->user) {
            $data_user = [
                'full_name' => $wallet_account->user->full_name,
                'email' => $wallet_account->user->email,
                'phone_number' => $wallet_account->user->phone_number,
            ];
        }else if($wallet_account->seasonTickets && $wallet_account->seasonTickets->isNotEmpty()){

            $first_season_ticket = $wallet_account->seasonTickets->first();
            $data_user = [
                'full_name' => $first_season_ticket->full_name,
                'email' => $first_season_ticket->holder_email,
                'phone_number' => $first_season_ticket->holder_phone,
                'code' => $first_season_ticket->seatCatalogue->code,
            ];
        }

        $wallet_account->user_information = $data_user;

        return $wallet_account;
    }

    /*
    * Generate unique account number by Christoper Patiño
    */
    public function generateUniqueAccountNumber()
    {
        do {
            $accountNumber = 'HW-' . str_pad(rand(0, pow(10, 12)-1), 12, '0', STR_PAD_LEFT);
        } while (WalletAccount::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }
}
