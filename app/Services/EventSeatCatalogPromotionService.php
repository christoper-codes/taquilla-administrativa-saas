<?php

namespace App\Services;

use App\Interfaces\EventSeatCatalogPromotionRepositoryInterface;

class EventSeatCatalogPromotionService
{
    /*
    * |--------------------------------------------------------------------------
    * | EventSeatCatalogPromotiontService the repository services for global module by Christoper Patiño
    */

    protected $event_seat_catalog_promotion_repository_interface;

    public function __construct(EventSeatCatalogPromotionRepositoryInterface $event_seat_catalog_promotion_repository_interface)
    {
        $this->event_seat_catalog_promotion_repository_interface = $event_seat_catalog_promotion_repository_interface;
    }

    public function addPromotionToSeat(array $seats)
    {
        try {

            $seats_updated = collect([]);

            collect($seats)->each(function ($seat) use ($seats_updated){

                $response = $this->event_seat_catalog_promotion_repository_interface->addPromotionToSeat($seat['id'], $seat['select_promotion']);

                $seats_updated->push($response);

            });

            $a_zone = [];
            $b_zone = [];
            $c_zone = [];
            $f_zone = [];
            $e_zone = [];
            $h_zone = [];

            $seats_updated->groupBy(function ($item) {

                return $item->seatCatalogue->zone;

            })->each(function ($item, $key) use (&$a_zone, &$b_zone, &$c_zone, &$f_zone, &$e_zone, &$h_zone) {
                if ($key === 'A') {
                    $a_zone = $item;
                } elseif ($key === 'B') {
                    $b_zone = $item;
                } elseif ($key === 'C') {
                    $c_zone = $item;
                } elseif ($key === 'F') {
                    $f_zone = $item;
                } elseif ($key === 'E') {
                    $e_zone = $item;
                } elseif ($key === 'H') {
                    $h_zone = $item;
                }
            });

            $response = [
                'a_zone' => $a_zone,
                'b_zone' => $b_zone,
                'c_zone' => $c_zone,
                'f_zone' => $f_zone,
                'e_zone' => $e_zone,
                'h_zone' => $h_zone
            ];

            return $response;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
