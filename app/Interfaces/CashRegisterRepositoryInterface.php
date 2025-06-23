<?php

namespace App\Interfaces;

use App\Models\CashRegister;
use Illuminate\Support\Collection;

interface CashRegisterRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll();
    public function getById($id);
    public function save(array $data);
    public function update($id, array $data);
    public function delete($id);

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function close(array $data, CashRegister $cash_register);
    public function closeAll(array $data, Collection $cash_registers);
}
