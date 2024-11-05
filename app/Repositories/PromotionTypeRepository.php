<?php

namespace App\Repositories;

use App\Interfaces\PromotionTypeRepositoryInterface;
use App\Models\PromotionType;

class PromotionTypeRepository implements PromotionTypeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return PromotionType::all();
    }
}
