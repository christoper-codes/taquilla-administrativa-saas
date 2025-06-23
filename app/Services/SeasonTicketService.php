<?php

namespace App\Services;
use App\Repositories\SeasonTicketRepository;
use App\Interfaces\SeasonTicketRepositoryInterface;

class SeasonTicketService {

    protected $season_ticket_repository;
    protected $season_ticket_repository_interface;

    public function __construct(SeasonTicketRepository $season_ticket_repository, SeasonTicketRepositoryInterface $season_ticket_repository_interface)
    {
        $this->season_ticket_repository = $season_ticket_repository;
        $this->season_ticket_repository_interface = $season_ticket_repository_interface;
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

    /*
    * |--------------------------------------------------------------------------
    * | Get all season tickets
    */
    public function getBySeason(int $id)
    {
        $season_tickets = $this->season_ticket_repository_interface->getBySeason($id);

        return $season_tickets;
    }


}
