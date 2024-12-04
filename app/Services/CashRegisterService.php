<?php

namespace App\Services;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;
use App\Models\CashRegisterType;

class CashRegisterService
{
     /*
    * |--------------------------------------------------------------------------
    * | CashRegisterService the repository services for global module by Christoper Patiño
    */

    protected $cash_register_repository;

    public function __construct(CashRegisterRepositoryInterface $cash_register_repository)
    {
        $this->cash_register_repository = $cash_register_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all cash registers
    */

    public function getAll()
    {
        try {
            $cash_register = $this->cash_register_repository->getAll();
            return $cash_register;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by id
    */
    public function getById($id)
    {
        try {
            $cash_register = $this->cash_register_repository->getById($id);
            return $cash_register;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Cash register general history
    */
    public function getCashRegisterGeneralHistory($cash_register_id)
    {
        try {
            $cash_register = $this->cash_register_repository->getById($cash_register_id);
            $type_payments = [];

            /*
            * Get all sale tickets associated with the cash register
            */
            $sale_tickets = $cash_register->saleTickets()->orderBy('created_at', 'desc')->get();

            $sale_tickets->each(function ($sale_ticket) use (&$type_payments) {
                $sale_ticket->saleTicketStatus;
                $sale_ticket->globalPaymentTypes;
                $sale_ticket->EventSeatCatalogues->map(function ($event_seat_catalogue) {
                    $event_seat_catalogue->seatCatalogue;
                });
                /*
                * Get all global payment types associated with the sale ticket
                */
                $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments) {
                    if (!isset($type_payments[$global_payment_type->name])) {
                        $type_payments[$global_payment_type->name] = [
                            'amount' => 0,
                        ];
                    }
                    $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                });

            });

            $response = [
                'cash_register' => $cash_register,
                'sale_tickets' => $sale_tickets,
                'type_payments' => $type_payments,
            ];

            return $response;

        } catch (\Exception $e) {
            throw $e;
        }
    }
    /*
    * |--------------------------------------------------------------------------
    * | Open cash register
    */
    public function openCashRegister(array $data)
    {
        try {

                /*
                * Determine if the user who is opening the cash register has any asscociated and active cash register
                */
                $user_has_cash_register = CashRegister::where('seller_user_opening_id', $data['seller_user_opening_id'])
                                    ->where('is_open', 1)
                                    ->first();
                if ($user_has_cash_register) {
                    throw new \Exception('El usuario ya tiene una caja abierta');
                }

                /*
                * Validate the types of cash register tha the store has
                */
                $cash_register_type = CashRegisterType::where('id', $data['cash_register_type_id'])
                                    ->whereHas('ticketOffices', function ($query) use ($data) {
                                        $query->where('ticket_office_id', $data['ticket_office_id']);
                                    })->first();
                if (!$cash_register_type) {
                    throw new \Exception('El tipo de caja no es válido para la taquilla seleccionada');
                }

                /*
                * Verify tha the cash register is not already open for the selected ticket office
                */
                $cash_register_open = CashRegister::where('cash_register_type_id', $data['cash_register_type_id'])
                                    ->where('ticket_office_id', $data['ticket_office_id'])
                                    ->where('is_open', 1)
                                    ->first();
                if ($cash_register_open) {
                    throw new \Exception('La caja ya se encuentra abierta');
                }

                /*
                * Verify if there are any cash register open for different day than the current day
                */
                $cash_register_open_different_day = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->where('is_open', 1)
                                    ->whereDate('created_at', '!=', now()->toDateString())
                                    ->first();
                if ($cash_register_open_different_day) {
                    throw new \Exception('Hay otras cajas registradoras abiertas que fueron creadas en un día diferente y diferente lote. para abrir nuenvas cajas, cierre las cajas existentes para concluir el lote');
                }

                /*
                * Verify if there are any cash register open for today
                */
                $cash_register_open = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->where('is_open', 1)
                                    ->first();
                if ($cash_register_open) {
                    /*
                    * Get all cash register open that the same batch code
                    */
                    $cash_register_open_batch_codes = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->where('batch_cash_register', $cash_register_open->batch_cash_register)
                                    ->where('batch_code', $cash_register_open->batch_code)
                                    ->get();

                    /*
                    * Validay if any cash register open has the same batch code
                    */
                    if($cash_register_open_batch_codes->count() != 0) {
                        foreach($cash_register_open_batch_codes as $cash_register) {
                            if($cash_register->cash_register_type_id == $data['cash_register_type_id']) {
                                throw new \Exception('Esta caja ya habia sido abierta en el mismo dia y mismo lote');
                            }
                        }
                    }
                }

                /*
                * Vefify if there was any cash register opened today for the same ticket office
                */
                $cash_registers_open_today = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->whereDate('created_at', now()->toDateString())
                                    ->get();

                if($cash_registers_open_today->count() != 0) {
                    /*
                    * Validate if any cash register is not confirmed clousure, then create a new cash register in the same batch code
                    */
                    $new_cash_register_in_same_batch = false;
                    foreach($cash_registers_open_today as $cash_register) {
                        if(!$cash_register->confirmed_closure) {
                            $data['batch_cash_register'] = $cash_register->batch_cash_register;
                            $data['batch_code'] = $cash_register->batch_code;
                            $new_cash_register = $this->cash_register_repository->save($data);
                            $new_cash_register_in_same_batch = true;
                            break;
                        }
                    }

                    /*
                    * If all cash register in the same batch code are confirmed closure, then create a new batch code
                    */
                    if(!$new_cash_register_in_same_batch) {
                        $last_catch_cash_register = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->whereDate('created_at', now()->toDateString())
                                    ->orderBy('batch_cash_register', 'desc')
                                    ->first();
                        $data['batch_cash_register'] = $last_catch_cash_register->batch_cash_register + 1;
                        $data['batch_code'] = uniqid();

                        $new_cash_register = $this->cash_register_repository->save($data);
                    }
                } else {
                    /*
                    * If there are no cash register open for today, then create a new batch code and cash register
                    */
                    $data['batch_cash_register'] = 1;
                    $data['batch_code'] = uniqid();
                    $new_cash_register = $this->cash_register_repository->save($data);
                }

                return $new_cash_register;

        } catch (\Exception $e) {
            throw $e;
        }
    }



}
