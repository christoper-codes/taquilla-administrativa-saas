<?php

namespace App\Services;

use App\Interfaces\GlobalCardPaymentTypeRepositoryInterface;

class GlobalCardPaymentTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | GlobalCardPaymentTypeService the repository services for global module by Christoper Patiño
    */

    protected $global_card_payment_type_repository;

    public function __construct(GlobalCardPaymentTypeRepositoryInterface $global_card_payment_type_repository)
    {
        $this->global_card_payment_type_repository = $global_card_payment_type_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all global card payment types
    */
    public function getAll()
    {
        $global_card_payment_type = $this->global_card_payment_type_repository->getAll();

        return $global_card_payment_type;
    }

}
