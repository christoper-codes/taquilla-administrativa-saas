<?php

namespace App\Services;

use App\Interfaces\EventTypeRepositoryInterface;

class EventTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | EventService the repository services for global module by Christoper Patiño
    */

    protected $event_type_repository;

    public function __construct(EventTypeRepositoryInterface $event_type_repository)
    {
        $this->event_type_repository = $event_type_repository;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get all types of event
    */
    public function getAll()
    {

        $event = $this->event_type_repository->getAll();

        return $event;
    }
}
