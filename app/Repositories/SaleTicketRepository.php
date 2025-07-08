<?php

namespace App\Repositories;
use App\Interfaces\SaleTicketRepositoryInterface;
use App\Models\SaleTicket;

class SaleTicketRepository implements SaleTicketRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return SaleTicket::all();
    }

    public function getById($id)
    {
        return SaleTicket::findOrfail($id);
    }

    public function save(array $data)
    {
        return SaleTicket::create($data);
    }

    public function update($id, array $data)
    {
        return SaleTicket::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return SaleTicket::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function cancelSaleTicket()
    {

    }

    public function getByIdWithRelations($id)
    {

    }
}
