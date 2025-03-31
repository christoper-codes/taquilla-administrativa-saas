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
        return TicketOffice::where('is_active', true)->get();
    }

    public function getById($id)
    {
        $ticketOffice = TicketOffice::with('cashRegisterTypes')->findOrFail($id);

        $cashRegisterTypesNoActives = $ticketOffice->cashRegisterTypesNoActives();

        $ticketOffice->cash_register_types_no_actives = $cashRegisterTypesNoActives;

        return $ticketOffice;
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
