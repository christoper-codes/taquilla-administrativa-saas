<?php

namespace App\Repositories;

use App\Interfaces\SeasonTicketWalletAccountRepositoryInterface;
use App\Models\WalletAccount;

class SeasonTicketWalletAccountRepository implements SeasonTicketWalletAccountRepositoryInterface
{
    public function saveForSubscriber(int $wallet_account_id, array $data)
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
            'seasonTickets',
            'user'
        ])
        ->find($wallet_account_id);

        if ($wallet_account) {

            $wallet_account->account_number = maskWalletAccountNumber($wallet_account->account_number);
        }

        $wallet_account->seasonTickets()->attach($data);

        return $wallet_account;
    }

    public function findForSubscriber(int $season_ticket_id)
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
            'seasonTickets',
            'user'
        ])
        ->get()->filter(function ($wallet_account) use ($season_ticket_id) {
            return $wallet_account->seasonTickets->contains(function ($season_ticket) use ($season_ticket_id) {
                return $season_ticket->pivot->season_ticket_id === $season_ticket_id;
            });
        });

        return $wallet_account;
    }

    public function findForUser(int $user_id)
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
            'seasonTickets',
            'user'
        ])
        ->where('user_id', $user_id)
        ->get();

        return $wallet_account;
    }
}
