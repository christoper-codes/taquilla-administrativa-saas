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
    public function getAllActive();
    public function reserveSeatsToBuy($event_id, $seat_catalogue_id, $member_user_id);
    public function confirmSeatsPurchase($event_id, $seat_catalogue_id, $member_user_id = null, $sale_ticket_id = null, $qr = null, $price = null, $original_pricde = null, $is_gift = null, $purchase_type = null);
    public function getEventsBySerie($serie_id);
    public function getOnlyEvent($id);
    public function getUsersEventForSaleTickets($id);
    public function getAllWithTraffic();
    public function releaseReservedSeats($event_id);
    public function getOnlyById($id);
    public function reserveSeatsToBuyBatch($event_id, $seat_catalogue_ids, $member_user_id);
}
