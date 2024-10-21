<?php

namespace App\Services;

use App\Interfaces\EventRepositoryInterface;
use App\Models\PriceTypeSeatCatalogue;
use Illuminate\Support\Facades\Auth;

class EventService
{
    /*
    * |--------------------------------------------------------------------------
    * | EventService the repository services for global module by Christoper Patiño
    */

    protected $event_repository;
    protected $global_payment_type_service;
    protected $global_card_payment_type_service;

    public function __construct(EventRepositoryInterface $event_repository, GlobalPaymentTypeService $global_payment_type_service, GlobalCardPaymentTypeService $global_card_payment_type_service)
    {
        $this->event_repository = $event_repository;
        $this->global_payment_type_service = $global_payment_type_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get all events
    */
    public function getAll()
    {

        $event = $this->event_repository->getAll();

        return $event;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get event by id
    */
    public function getById($id)
    {

        $event = $this->event_repository->getById($id);

        $a_zone = [];
        $b_zone = [];
        $c_zone = [];

        $event->eventSeatCatalogues->groupBy(function ($item) {
            /*
            * access the price of each seat
            */
            $item->seatCatalogue->priceTypes->each(function ($priceType) {
                $priceCatalogue = PriceTypeSeatCatalogue::where('seat_catalogue_id', $priceType->pivot->seat_catalogue_id)
                ->where('price_type_id', $priceType->pivot->price_type_id)
                ->first()
                ->priceCatalogue;

                $priceType->price = $priceCatalogue->price;
            });

            return $item->seatCatalogue->zone;
        })->each(function ($item, $key) use (&$a_zone, &$b_zone, &$c_zone) {
            if ($key === 'A') {
                $a_zone = $item;
            } elseif ($key === 'B') {
                $b_zone = $item;
            } elseif ($key === 'C') {
                $c_zone = $item;
            }
        });

        /*
        * Roles user
        */
        $user_roles = Auth::user()->userRoles;

        /*
        * payment types and card types
        */
        $global_payment_types = $this->global_payment_type_service->getAll();
        $global_card_payment_types = $this->global_card_payment_type_service->getAll();

        $reponse = [
            'event' => $event,
            'a_zone' => $a_zone,
            'b_zone' => $b_zone,
            'c_zone' => $c_zone,
            'user_roles' => $user_roles,
            'global_payment_types' => $global_payment_types,
            'global_card_payment_types' => $global_card_payment_types,
        ];

        return $reponse;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new event catalogue
    */
    public function save(array $data)
    {
        try {

            $event = $this->event_repository->save($data);

            return $event;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update event catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $serie = $this->event_repository->update($id, $data);

            return $serie;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete event catalogue
    */
    public function delete(int $id)
    {
        try {

            $event = $this->event_repository->delete($id);

            return $event;

        } catch (\Exception $e) {

            throw $e;
        }
    }


}
