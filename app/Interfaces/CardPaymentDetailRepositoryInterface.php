<?php

namespace App\Interfaces;

interface CardPaymentDetailRepositoryInterface
{
    //
    public function getAll();
    public function create(array $data);
}
