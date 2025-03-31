<?php

namespace App\Repositories;

use App\Interfaces\PriceCatalogRepositoryInterface;
use App\Models\PriceCatalogue;

class PriceCatalogRepository implements PriceCatalogRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAllForStadium(int $stadium_id)
    {
        return PriceCatalogue::where('stadium_id', $stadium_id)->get();
    }

    public function firstOrCreate(array $data)
    {
        return  PriceCatalogue::firstOrCreate(
            [
                'stadium_id' => $data['stadium_id'],
                'price' => number_format((float) $data['price'], 4, '.', ''),
            ],
            $data
        );
    }
}
