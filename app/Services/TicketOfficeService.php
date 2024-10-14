<?php

namespace App\Services;

use App\Interfaces\TicketOfficeRepositoryInterface;

class TicketOfficeService
{
    /*
    * |--------------------------------------------------------------------------
    * | TicketOfficeService the repository services for global module by Christoper Patiño
    */
    protected $ticket_office_repository;

    public function __construct(TicketOfficeRepositoryInterface $ticket_office_repository)
    {
        $this->ticket_office_repository = $ticket_office_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all ticket offices
    */
    public function getAll()
    {
        try {
            $ticket_offices = $this->ticket_office_repository->getAll();

            return $ticket_offices;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get ticket office by id
    */
    public function getById(int $id)
    {
        try {
            $ticket_office = $this->ticket_office_repository->getById($id);

            return $ticket_office;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new ticket office
    */
    public function save(array $data)
    {

        try {
            $ticket_office = $this->ticket_office_repository->save($data);

            return $ticket_office;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
