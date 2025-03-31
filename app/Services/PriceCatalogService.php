<?php

namespace App\Services;

use App\Interfaces\PriceCatalogRepositoryInterface;

class PriceCatalogService
{
    /*
    * |--------------------------------------------------------------------------
    * | PriceCatalogService the repository services for global module by Christoper Patiño
    */

    protected $price_catalog_repository_interface;

    public function __construct(PriceCatalogRepositoryInterface $price_catalog_repository_interface)
    {
        $this->price_catalog_repository_interface = $price_catalog_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all prices catalogues
    */
    public function getAllForStadium(int $stadium_id)
    {
        $prices_catalog = $this->price_catalog_repository_interface->getAllForStadium($stadium_id);

        return $prices_catalog;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all prices catalogues
    */
    public function firstOrCreate(array $data)
    {
        $price_catalog = $this->price_catalog_repository_interface->firstOrCreate($data);

        return $price_catalog;
    }
}
