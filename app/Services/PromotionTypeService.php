<?php

namespace App\Services;

use App\Interfaces\PromotionTypeRepositoryInterface;

class PromotionTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | PromotionService the repository services for global module by Christoper Patiño
    */

    protected $promotion_type_repository_interface;

    public function __construct(PromotionTypeRepositoryInterface $promotion_type_repository_interface)
    {
        $this->promotion_type_repository_interface = $promotion_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all promotion catalogues
    */
    public function getAll()
    {
        $promotion_type = $this->promotion_type_repository_interface->getAll();

        return $promotion_type;
    }
}
