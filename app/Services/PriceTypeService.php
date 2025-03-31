<?php

namespace App\Services;

use App\Interfaces\PriceTypeRepositoryInterface;

class PriceTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | PriceTypeService the repository services for global module by Christoper Patiño
    */

    protected $price_type_repository_interface;

    public function __construct(PriceTypeRepositoryInterface $price_type_repository_interface)
    {
        $this->price_type_repository_interface = $price_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all prices type catalogues
    */
    public function getAll()
    {
        $prices_type = $this->price_type_repository_interface->getAll();

        return $prices_type;
    }
}
