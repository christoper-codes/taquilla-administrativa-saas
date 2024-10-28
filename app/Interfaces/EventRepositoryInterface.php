<?php

namespace App\Interfaces;

interface EventRepositoryInterface
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
    public function reserveSeatsToBuy($event_id, $seat_catalogue_id, $member_user_id);
    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null);
    public function getEventsBySerie($serie_id);
}
