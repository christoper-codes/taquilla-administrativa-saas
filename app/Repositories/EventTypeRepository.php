<?php

namespace App\Repositories;

use App\Interfaces\EventTypeRepositoryInterface;
use App\Models\EventType;

class EventTypeRepository implements EventTypeRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return EventType::all();
    }


     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}
