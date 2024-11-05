<?php

namespace App\Interfaces;

interface EventSeatCatalogPromotionRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function addPromotionToSeat(int $id, array $promotions);
}
