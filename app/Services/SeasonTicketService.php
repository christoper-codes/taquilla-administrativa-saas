<?php

namespace App\Services;
use App\Repositories\SeasonTicketRepository;

class SeasonTicketService {

    protected $season_ticket_repository;

    public function __construct(SeasonTicketRepository $season_ticket_repository)
    {
        $this->season_ticket_repository = $season_ticket_repository;
    }

     /*
    * |--------------------------------------------------------------------------
    * | Get all SeasonTicketService
    */
    public function getAll()
    {
        try {

            $season_tickets = $this->season_ticket_repository->getAll();
            return $season_tickets;

        } catch (\Exception $e) {
            throw $e;
        }
    }

     /*
    * |--------------------------------------------------------------------------
    * | Save new SeasonTicketService
    */
    public function save(array $data)
    {
        try {

            $season_ticket = $this->season_ticket_repository->save($data);

            return $season_ticket;

        } catch (\Exception $e) {

            throw $e;
        }
    }


}