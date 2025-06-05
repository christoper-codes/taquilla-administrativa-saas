<?php

namespace App\Services;

use App\Interfaces\EventSeatCatalogPriceTypeRepositoryInterface;

class EventSeatCatalogPriceTypeService
{
    /*
    * |--------------------------------------------------------------------------
    * | EventSeatCatalogPriceTypetService the repository services for global module by Christoper Patiño
    */

    protected $event_seat_catalog_price_type_repository_interface;

    public function __construct(EventSeatCatalogPriceTypeRepositoryInterface $event_seat_catalog_price_type_repository_interface)
    {
        $this->event_seat_catalog_price_type_repository_interface = $event_seat_catalog_price_type_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new event seat catalog promotion
    */
    public function saveInBulk(array $data)
    {
        try {

            $event_seat_catalog_price_type = $this->event_seat_catalog_price_type_repository_interface->saveInBulk($data);

            return $event_seat_catalog_price_type;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new event seat catalog promotion
    */
    public function updateInBulk(array $data)
    {
        try {

            $event_seat_catalog_price_type = $this->event_seat_catalog_price_type_repository_interface->updateInBulk($data);

            $a_zone = [];
            $b_zone = [];
            $c_zone = [];
            $f_zone = [];
            $e_zone = [];
            $h_zone = [];

            $event_seat_catalog_price_type->groupBy(function ($item) {
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
