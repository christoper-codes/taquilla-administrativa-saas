<?php

namespace App\Interfaces;

interface SaleDebtorRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll($id = null);
    public function getById($id);
    public function save(array $data);
    public function update($id, array $data);
    public function delete($id);

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function cancelSaleDebtor();
}
