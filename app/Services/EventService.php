<?php

namespace App\Services;

use App\Interfaces\EventRepositoryInterface;
use App\Models\CashRegisterMovement;
use App\Models\CashRegisterMovementType;
use App\Models\EventSeatCatalogPriceType;
use App\Models\PriceTypeSeatCatalogue;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
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
    protected $cash_register_service;

    public function __construct(EventRepositoryInterface $event_repository, GlobalPaymentTypeService $global_payment_type_service, GlobalCardPaymentTypeService $global_card_payment_type_service, CashRegisterService $cash_register_service)
    {
        $this->event_repository = $event_repository;
        $this->global_payment_type_service = $global_payment_type_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
        $this->cash_register_service = $cash_register_service;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get all events
    */
    public function getAll()
    {
        try {

            $event = $this->event_repository->getAll();

            return $event;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get event by id
    */
    public function getById($id)
    {

        try {

            $event = $this->event_repository->getById($id);
            /*
            * Group seats by zone
            */
            $a_zone = [];
            $b_zone = [];
            $c_zone = [];
            $f_zone = [];

            /*
            * purchase types available
            */
            $purchase_types = ['partido'];
            $events_by_serie = $this->event_repository->getEventsBySerie($event->serie_id);
            if ($events_by_serie->count() > 1) {
                $purchase_types[] = 'serie';
            }

            $event->eventSeatCatalogues->groupBy(function ($item) {
                /*
                * access the price of each seat
                */
                $item->priceTypes->each(function ($priceType) {
                    $priceCatalogue = EventSeatCatalogPriceType::where('event_seat_catalog_id', $priceType->pivot->event_seat_catalog_id)
                    ->where('price_type_id', $priceType->pivot->price_type_id)
                    ->first()
                    ->priceCatalogue;

                    $priceType->price = $priceCatalogue->price;
                });

                return $item->seatCatalogue->zone;
            })->each(function ($item, $key) use (&$a_zone, &$b_zone, &$c_zone, &$f_zone) {
                if ($key === 'A') {
                    $a_zone = $item;
                } elseif ($key === 'B') {
                    $b_zone = $item;
                } elseif ($key === 'C') {
                    $c_zone = $item;
                } elseif ($key === 'F') {
                    $f_zone = $item;
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
                'f_zone' => $f_zone,
                'user_roles' => $user_roles,
                'global_payment_types' => $global_payment_types,
                'global_card_payment_types' => $global_card_payment_types,
                'purchase_types' => $purchase_types,
            ];

            return $reponse;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Reserve seats to buy
    */
    public function reserveSeatsToBuy($data)
    {
        try {

            foreach ($data['seats'] as $seat) {
                /*
                * Vefify if the seat is available to buy
                */
                $event_seat_catalogue = $this->event_repository->getById($data['event_id'])->eventSeatCatalogues->where('seat_catalogue_id', $seat['seat_catalogue_id'])->first();
                if ($event_seat_catalogue->seatCatalogueStatus->name !== 'disponible') {
                    throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar');
                }

                /*
                * Reserve seat to buy
                */
                $this->event_repository->reserveSeatsToBuy($data['event_id'], $seat['seat_catalogue_id'], $data['member_user_id']);
            }

            return true;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Confirm seats purchase
    */
    public function confirmSeatsPurchase($data)
    {
        try {

            /*
            * Validate if cash register is open
            */
            $cash_register = $this->cash_register_service->getById($data['cash_register_id']);
            if (!$cash_register->is_open) {
                throw new \Exception('La caja registradora no está abierta para realizar la venta en esta taquilla');
            }

            /*
            * Create sale ticket
            */
            $saleTicket = new SaleTicket();
            $saleTicket->event_id = $data['event_id'];
            $saleTicket->seller_user_id = $data['seller_user_id'];
            $saleTicket->cash_register_id = $data['cash_register_id'];
            $saleTicket->sale_ticket_status_id = SaleTicketStatus::where('name', 'pagado')->first()->id;
            $saleTicket->price_type_id = $data['price_type_id'];
            $saleTicket->amount_received = $data['amount_received'];
            $saleTicket->total_amount = $data['total_amount'];
            $saleTicket->total_returned = $data['total_returned'];
            $saleTicket->is_online = $data['is_online'];
            $saleTicket->save();

            /*
            * Assign payment types to the sale ticket
            */
            foreach($data['global_payment_types'] as $global_payment_type){
                $saleTicket->globalPaymentTypes()->attach($global_payment_type['id'], [
                    'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'] ?? null,
                    'amount' => $global_payment_type['amount'],
                ]);
            }

            /*
            * Assign seats to the sale ticket
            */
            foreach ($data['seats'] as $seat) {

                $qr = 'qr_evento_' . $data['event_id'] . '_asiento_' . $seat['seat_catalogue']['code'] . '_ticket_' . $saleTicket->id . '_key_' . uniqid();

                /*
                * Vefify if the seat is available to buy
                */
                $event_seat_catalogue = $this->event_repository->getById($data['event_id'])->eventSeatCatalogues->where('seat_catalogue_id', $seat['seat_catalogue_id'])->first();
                if ($event_seat_catalogue->seatCatalogueStatus->name !== 'transito') {
                    throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra en tránsito');
                }

                if($event_seat_catalogue->user_id !== $data['member_user_id']){
                    throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra reservado para el mismo usuario');
                }

                /*
                * Confirm seat purchase
                */
                $this->event_repository->confirmSeatsPurchase($data['event_id'], $seat['seat_catalogue_id'], $data['member_user_id'], $saleTicket->id, $qr, $seat['final_price']);

                /*
                * Create relationship between sale ticket and eventSeatCatalogs
                */
                $saleTicket->eventSeatCatalogs()->attach( $event_seat_catalogue->id, [
                    'user_id' => $data['member_user_id'],
                    'promotion_id' => null,
                    'agreement_promotion_id' => null,
                    'is_active' => true,
                ]);
            }

            /*
            * Create cash register movement
            */
            $cash_register_movement = new CashRegisterMovement();
            $cash_register_movement->cash_register_id = $data['cash_register_id'];
            $cash_register_movement->cash_register_movement_type_id = CashRegisterMovementType::where('name', 'venta')->first()->id;
            $cash_register_movement->sale_ticket_id = $saleTicket->id;
            $cash_register_movement->previous_balance = $cash_register->current_balance;
            $cash_register_movement->movement_amount = $data['total_amount'];
            $cash_register_movement->new_balance = $cash_register->current_balance + $data['total_amount'];
            $cash_register_movement->save();

            /*
            * Update cash register balance
            */
            $cash_register->current_balance = $cash_register_movement->new_balance;
            $cash_register->save();

            return true;

        } catch (\Exception $e) {

            throw $e;
        }
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
