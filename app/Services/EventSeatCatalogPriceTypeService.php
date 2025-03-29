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

            return $event_seat_catalog_price_type;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
