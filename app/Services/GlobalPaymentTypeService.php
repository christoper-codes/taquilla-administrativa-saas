<?php

namespace App\Services;

use App\Interfaces\GlobalPaymentTypeRepositoryInterface;

class GlobalPaymentTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | GlobalPaymentTypeService the repository services for global module by Christoper Patiño
    */

    protected $global_payment_type_repository;

    public function __construct(GlobalPaymentTypeRepositoryInterface $global_payment_type_repository)
    {
        $this->global_payment_type_repository = $global_payment_type_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all global payment types
    */
    public function getAll()
    {
        $global_payment_type = $this->global_payment_type_repository->getAll();

        return $global_payment_type;
    }
}
