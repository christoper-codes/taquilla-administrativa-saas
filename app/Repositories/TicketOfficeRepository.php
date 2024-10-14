<?php

namespace App\Repositories;

use App\Interfaces\TicketOfficeRepositoryInterface;
use App\Models\TicketOffice;

class TicketOfficeRepository implements TicketOfficeRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return TicketOffice::all();
    }

    public function getById($id)
    {
        return TicketOffice::with('cashRegisterTypes', 'cashRegistersActives')->findOrFail($id);
    }

    public function save(array $data)
    {
        return TicketOffice::create($data);
    }

    public function update($id, array $data)
    {
        return TicketOffice::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return TicketOffice::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */

}
