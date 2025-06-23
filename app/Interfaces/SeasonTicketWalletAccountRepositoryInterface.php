<?php

namespace App\Interfaces;

interface SeasonTicketWalletAccountRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */

    public function saveForSubscriber(int $wallet_account_id, array $data);
    public function findForSubscriber(int $season_ticket_id);
    public function findForUser(int $user_id);
}
