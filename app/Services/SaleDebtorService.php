<?php

namespace App\Services;

use App\Repositories\SaleDebtorRepository;

class SaleDebtorService
{
     /*
    * |--------------------------------------------------------------------------
    * | SaleDebtorService the repository services for global module by Christoper Patiño
    */
    protected $sale_debtor_repository;

    public function __construct(SaleDebtorRepository $sale_debtor_repository)
    {
        $this->sale_debtor_repository = $sale_debtor_repository;
    }

    public function getById($id)
    {
        return $this->sale_debtor_repository->getById($id);
    }

    public function getAll($id)
    {
        return $this->sale_debtor_repository->getAll($id);
    }

    public function save(array $data)
    {
        return $this->sale_debtor_repository->save($data);
    }

}
