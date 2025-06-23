<?php

namespace App\Services;

use App\Interfaces\SeasonTicketWalletAccountRepositoryInterface;

class SeasonTicketWalletAccountService
{
    /*
    * |--------------------------------------------------------------------------
    * | SeasonTicketWalletAccountService the repository services for global module
    */

    protected $season_ticket_wallet_account_repository_interface;

    public function __construct(SeasonTicketWalletAccountRepositoryInterface $season_ticket_wallet_account_repository_interface)
    {
        $this->season_ticket_wallet_account_repository_interface = $season_ticket_wallet_account_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all season tickets
    */
    public function saveForSubscriber(int $wallet_account_id, array $data)
    {
        $season_tickets = $this->season_ticket_wallet_account_repository_interface->saveForSubscriber($wallet_account_id, $data);

        return $season_tickets;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all season tickets
    */
    public function findForSubscriber(int $season_ticket_id)
    {
        $season_tickets = $this->season_ticket_wallet_account_repository_interface->findForSubscriber($season_ticket_id);

        return $season_tickets;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all season tickets
    */
    public function findForUser(int $user_id)
    {
        $season_tickets = $this->season_ticket_wallet_account_repository_interface->findForUser($user_id);

        return $season_tickets;
    }
}
