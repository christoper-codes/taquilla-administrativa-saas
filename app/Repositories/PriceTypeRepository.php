<?php

namespace App\Repositories;

use App\Interfaces\PriceTypeRepositoryInterface;
use App\Models\PriceType;

class PriceTypeRepository implements PriceTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return PriceType::all();
    }
}
