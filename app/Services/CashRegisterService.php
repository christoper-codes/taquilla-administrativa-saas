<?php

namespace App\Services;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;
use App\Models\CashRegisterType;
use App\Models\GlobalPaymentType;
use App\Models\SeatCatalogueStatus;
use App\Models\GlobalCardPaymentType;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CashRegisterService
{
     /*
    * |--------------------------------------------------------------------------
    * | CashRegisterService the repository services for global module by Christoper Patiño
    */

    protected $cash_register_repository;
    protected $cash_register_movement_service;
    protected $cash_register_movement_type_Service;

    public function __construct(CashRegisterRepositoryInterface $cash_register_repository, CashRegisterMovementService $cash_register_movement_service, CashRegisterMovementTypeService $cash_register_movement_type_Service)
    {
        $this->cash_register_repository = $cash_register_repository;
        $this->cash_register_movement_service = $cash_register_movement_service;
        $this->cash_register_movement_type_Service = $cash_register_movement_type_Service;
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

            $globalCardPaymentType = GlobalCardPaymentType::all();
            $status = collect(['pagado', 'pendiente', 'parcialmente cancelado']);

            $type_payments = [];

            /*
            * Get all sale tickets associated with the cash register
            */
            $sale_tickets = $cash_register->saleTickets()->orderBy('created_at', 'desc')->get();

            $sale_tickets_installment_payment_to_ignore = collect([]);

            $sale_tickets->each(function ($sale_ticket) use (&$type_payments, $status, $globalCardPaymentType, &$sale_tickets_installment_payment_to_ignore) {

                    if($sale_ticket->globalPaymentTypes->first()->installment_payment_history_id){
                        $sale_tickets_installment_payment_to_ignore->push($sale_ticket->globalPaymentTypes->first()->installment_payment_history_id);
                    }
                    if($sale_ticket->installmentPaymentHistories->count() === 1){
                        $sale_tickets_installment_payment_to_ignore->push($sale_ticket->installmentPaymentHistories->first()->id);
                    }

                    $sale_ticket->loadMissing(['saleTicketStatus', 'globalPaymentTypes', 'EventSeatCatalogues.seatCatalogue', 'saleDebtor', 'installmentPaymentHistories', 'promotion.promotionType']);
                    /**
                     * Add remaining amount if it is a payment in installments
                     */

                    $sale_ticket->setAttribute('remaining_amount', $sale_ticket->saleDebtor ? ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount) : 0);

                    if($sale_ticket->saleTicketStatus->name == 'pagado' && $sale_ticket->remaining_amount > 0){
                        $sale_ticket->setRelation('saleTicketStatus',  SaleTicketStatus::where('name', 'pendiente')->first());
                    }


                    /*
                    * Get all global payment types associated with the sale ticket
                    */

                    $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments, &$sale_ticket, $globalCardPaymentType, $status) {

                        if ($global_payment_type->pivot->global_card_payment_type_id) {

                            $globalCardPaymentType = $globalCardPaymentType->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);

                            $global_payment_type->name .= " (".$globalCardPaymentType->name.")".($sale_ticket->saleDebtor ? " - Deuda a plazos" : '');

                        }else{
                            $global_payment_type->name .= ($sale_ticket->saleDebtor ? " - Deuda a plazos" : '');
                        }

                        if (!isset($type_payments[$global_payment_type->name])) {
                            $type_payments[$global_payment_type->name] = [
                                'amount' => 0,
                            ];
                        }


                        /**
                         * sum the amount of the sale ticket for payments
                         */
                        if($status->contains($sale_ticket->saleTicketStatus->name)){
                            if ($sale_ticket->saleDebtor) {

                                $type_payments[$global_payment_type->name]['amount'] += ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount);

                                $dataTemp = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);

                                if (!isset($type_payments[$dataTemp])) {
                                    $type_payments[$dataTemp] = ['amount' => 0];
                                }
                                $type_payments[$dataTemp]['amount'] += $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount;

                            }else{
                                $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                            }
                        }

                        $global_payment_type->name = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);

                    });
            });


            /*
            * Get all sale tickets associated with the cash register
            */

            $cash_register->loadMissing(['installmentPaymentHistories.saleTicket', 'installmentPaymentHistories.globalPaymentTypes']);

            $sale_tickets_intallment_payment = $cash_register->installmentPaymentHistories->whereNotIn('id',$sale_tickets_installment_payment_to_ignore)->map(function ($installmentPaymentHistory) use (&$type_payments, $globalCardPaymentType) {

                $saleTicket =  clone $installmentPaymentHistory->saleTicket;

                $saleTicket->loadMissing([
                    'saleTicketStatus',
                    'globalPaymentTypes',
                    'EventSeatCatalogues.seatCatalogue',
                    'saleDebtor',
                    'installmentPaymentHistories',
                    'promotion.promotionType'
                ]);


                /**
                 * Add remaining amount if it is a payment in installments
                 */
                $saleTicket->setRelation('globalPaymentTypes',  $installmentPaymentHistory->globalPaymentTypes);
                $saleTicket->setAttribute('remaining_amount',  $saleTicket->total_amount - $saleTicket->installmentPaymentHistories->sum('total_amount') );
                $saleTicket->setAttribute('amount_received',  $installmentPaymentHistory->total_amount);
                $saleTicket->setAttribute('total_amount',  $saleTicket->total_amount);
                $saleTicket->setAttribute('total_returned',  0);
                $saleTicket->setAttribute('created_at',  $installmentPaymentHistory->created_at);

                /*
                * Get all global payment types associated with the sale ticket
                */
                $saleTicket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments, $globalCardPaymentType) {

                    if ($global_payment_type->pivot->global_card_payment_type_id) {

                        $globalCardPaymentTypeTemp = $globalCardPaymentType->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);

                        $global_payment_type->name .= " (".$globalCardPaymentTypeTemp->name.")"." - Abonos a Deuda";
                    }else{
                        $global_payment_type->name .= " - Abonos a Deuda";
                    }

                    if (!isset($type_payments[$global_payment_type->name])) {
                        $type_payments[$global_payment_type->name] = [
                            'amount' => 0,
                        ];
                    }

                    /**
                     * sum the amount of the sale ticket for payments
                     */
                    $type_payments[$global_payment_type->name]['amount'] += $global_payment_type['pivot']['amount'];
                });

                return $saleTicket;
            });

            $totals = [
                'efectivo' => [
                    'keys' => ['efectivo', 'efectivo - Abonos a Deuda'],
                    'total_key' => 'efectivo - Total',
                ],
                'tarjeta ' => [
                    'keys' => ['tarjeta (credito)', 'tarjeta (credito) - Abonos a Deuda', 'tarjeta (debito)', 'tarjeta (debito) - Abonos a Deuda'],
                    'total_key' => 'tarjeta - Total',
                ],
            ];

            foreach ($totals as $group) {
                $type_payments[$group['total_key']] = ['amount' => 0];
                foreach ($group['keys'] as $key) {
                    if (isset($type_payments[$key])) {
                        $type_payments[$group['total_key']]['amount'] += $type_payments[$key]['amount'];
                    }
                }
            }

            $last_two = array_slice($type_payments, -2, 2, true);
            $rest = array_slice($type_payments, 0, -2, true);

            $type_payments = $last_two + $rest;

            $type_payments = collect($type_payments)->filter(function ($value, $key) {
                return !str_contains($key, ' - Deuda a plazos');
            });

            $response = [
                'cash_register' => $cash_register,
                'sale_tickets' => $sale_tickets->concat($sale_tickets_intallment_payment)->sortByDesc('created_at')->values(),
                'type_payments' => $type_payments,
            ];

            return $response;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get sale ticket detail history with seats
    */
    public function getSaleTicketDetailHistory($sale_ticket_id)
    {
        try {
            // Obtener el ticket específico
            $sale_ticket = SaleTicket::with([
                'saleTicketStatus',
                'globalPaymentTypes',
                'EventSeatCatalogues.seatCatalogue',
                'saleDebtor',
                'installmentPaymentHistories.globalPaymentTypes',
                'promotion.promotionType',
                'cashRegister',
                'sellerUser',
                'events'
            ])->findOrFail($sale_ticket_id);

            $globalCardPaymentType = GlobalCardPaymentType::all();
            $status = collect(['pagado', 'pendiente', 'parcialmente cancelado']);

            $type_payments = [];
            $seat_details = [];

            // Calcular monto restante si es pago a plazos
            $remaining_amount = $sale_ticket->saleDebtor ?
                ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount) : 0;

            $sale_ticket->setAttribute('remaining_amount', $remaining_amount);

            // Actualizar status si está pagado pero tiene deuda pendiente
            if($sale_ticket->saleTicketStatus->name == 'pagado' && $sale_ticket->remaining_amount > 0){
                $sale_ticket->setRelation('saleTicketStatus', SaleTicketStatus::where('name', 'pendiente')->first());
            }

            // Procesar tipos de pago del ticket principal
            $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments, &$sale_ticket, $globalCardPaymentType, $status) {

                if ($global_payment_type->pivot->global_card_payment_type_id) {
                    $cardType = $globalCardPaymentType->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);
                    $global_payment_type->name .= " (".$cardType->name.")".($sale_ticket->saleDebtor ? " - Deuda a plazos" : '');
                } else {
                    $global_payment_type->name .= ($sale_ticket->saleDebtor ? " - Deuda a plazos" : '');
                }

                if (!isset($type_payments[$global_payment_type->name])) {
                    $type_payments[$global_payment_type->name] = ['amount' => 0];
                }

                // Sumar montos solo si el ticket está en status válido
                if($status->contains($sale_ticket->saleTicketStatus->name)){
                    if ($sale_ticket->saleDebtor) {
                        $type_payments[$global_payment_type->name]['amount'] += ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount);

                        $dataTemp = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);
                        if (!isset($type_payments[$dataTemp])) {
                            $type_payments[$dataTemp] = ['amount' => 0];
                        }
                        $type_payments[$dataTemp]['amount'] += $sale_ticket->installmentPaymentHistories->sortBy('created_at')->first()->total_amount;
                    } else {
                        $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                    }
                }

                $global_payment_type->name = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);
            });

            // Procesar historial de pagos a plazos
            $installment_payments = $sale_ticket->installmentPaymentHistories->map(function ($installmentPaymentHistory) use (&$type_payments, $globalCardPaymentType, $sale_ticket) {

                $installmentPayment = clone $installmentPaymentHistory;
                $installmentPayment->setAttribute('sale_ticket_id', $sale_ticket->id);
                $installmentPayment->setAttribute('remaining_amount', $sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sum('total_amount'));

                $installmentPaymentHistory->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments, $globalCardPaymentType) {

                    if ($global_payment_type->pivot->global_card_payment_type_id) {
                        $cardType = $globalCardPaymentType->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);
                        $global_payment_type->name .= " (".$cardType->name.")"." - Abonos a Deuda";
                    } else {
                        $global_payment_type->name .= " - Abonos a Deuda";
                    }

                    if (!isset($type_payments[$global_payment_type->name])) {
                        $type_payments[$global_payment_type->name] = ['amount' => 0];
                    }

                    $type_payments[$global_payment_type->name]['amount'] += $global_payment_type['pivot']['amount'];
                });

                return $installmentPayment;
            });

            // Obtener detalles de los asientos asociados
            $sale_ticket->EventSeatCatalogues->each(function ($eventSeatCatalogue) use (&$seat_details) {
                $seat_details[] = [
                    'seat_id' => $eventSeatCatalogue->id,
                    'seat_code' => $eventSeatCatalogue->seatCatalogue->code,
                    'zone' => $eventSeatCatalogue->seatCatalogue->zone,
                    'row' => $eventSeatCatalogue->seatCatalogue->row,
                    'seat' => $eventSeatCatalogue->seatCatalogue->seat,
                    'seat_type' => $eventSeatCatalogue->seatCatalogue->seatType->name ?? 'N/A',
                    'status' => $eventSeatCatalogue->seatCatalogueStatus->name ?? 'N/A',
                    'final_price' => $eventSeatCatalogue->final_price,
                    'original_price' => $eventSeatCatalogue->original_price,
                    'qr_code' => $eventSeatCatalogue->qr,
                    'is_gift' => $eventSeatCatalogue->is_gift,
                    'purchase_type' => $eventSeatCatalogue->purchase_type,
                    'is_verified' => $eventSeatCatalogue->is_verified,
                ];
            });

            // Calcular totales por tipo de pago
            $totals = [
                'efectivo' => [
                    'keys' => ['efectivo', 'efectivo - Abonos a Deuda'],
                    'total_key' => 'efectivo - Total',
                ],
                'tarjeta' => [
                    'keys' => ['tarjeta (credito)', 'tarjeta (credito) - Abonos a Deuda', 'tarjeta (debito)', 'tarjeta (debito) - Abonos a Deuda'],
                    'total_key' => 'tarjeta - Total',
                ],
            ];

            foreach ($totals as $group) {
                $type_payments[$group['total_key']] = ['amount' => 0];
                foreach ($group['keys'] as $key) {
                    if (isset($type_payments[$key])) {
                        $type_payments[$group['total_key']]['amount'] += $type_payments[$key]['amount'];
                    }
                }
            }

            // Reordenar totales al final
            $last_two = array_slice($type_payments, -2, 2, true);
            $rest = array_slice($type_payments, 0, -2, true);
            $type_payments = $last_two + $rest;

            // Filtrar pagos de deuda a plazos del resultado final
            $type_payments = collect($type_payments)->filter(function ($value, $key) {
                return !str_contains($key, ' - Deuda a plazos');
            });

            $response = [
                'sale_ticket' => $sale_ticket,
                'seats' => $seat_details,
                'installment_payments' => $installment_payments,
                'type_payments' => $type_payments,
                'total_seats' => count($seat_details),
                'payment_summary' => [
                    'total_amount' => $sale_ticket->total_amount,
                    'amount_received' => $sale_ticket->amount_received,
                    'remaining_amount' => $sale_ticket->remaining_amount,
                    'total_returned' => $sale_ticket->total_returned,
                    'is_installment' => $sale_ticket->payment_in_installments ? true : false,
                    'installment_months' => $sale_ticket->payment_in_installments,
                ]
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

     /*
    * |--------------------------------------------------------------------------
    * | Close cash register
    */
    public function closeCashRegister(array $data)
    {
        try {
            /*
            * Check that the cash register is open
            */
            $cash_register = $this->cash_register_repository->getById($data['cash_register_id']);
            if(!$cash_register->is_open){
                throw new \Exception('La caja actual ya ha sido cerrada');
            }

            /*
            * close cash register
            */
            $this->cash_register_repository->close($data, $cash_register);

            /*
            * Verify if there is any cash register open for the ticket office
            */
            $someCashRegisterIsOpen = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                        ->where('is_open', true)
                        ->first();

            if(!$someCashRegisterIsOpen){
                // send emails and confimed closure
            }

            return $this->getCashRegisterSummary($data);

        } catch(\Exception $e){
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Close cash registers for ticket office
    */
    public function closeTicketOfficeCashRegisters(array $data)
    {
        try {
            /*
            * Get all cash registers open for the ticket office
            */
            $cash_registers = CashRegister::where('ticket_office_id', $data['ticket_office_id'])
                                    ->where('is_open', 1)
                                    ->whereDate('created_at', '!=', now()->toDateString())
                                    ->get();
            /*
            * Close all cash registers by ticket office
            */
            $this->cash_register_repository->closeAll($data, $cash_registers);

            return $cash_registers;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Close cash register
    */
    public function getCashRegisterSummary(array $data)
    {
        try {

            $cash_register = $this->cash_register_repository->getById($data['cash_register_id']);

            $global_card_payment_type = GlobalCardPaymentType::all();
            $seat_catalogue_status_id = SeatCatalogueStatus::where('name', 'vendido')->first()->id;
            $global_payment_type_id = GlobalPaymentType::where('name', 'cortesia')->first()->id;
            $status = collect(['pagado', 'pendiente', 'parcialmente cancelado']);


            $cash_register->seller_user_closing_id = $data['seller_user_closing_id'];
            $cash_register->closing_balance = $cash_register->current_balance;
            $cash_register->closing_time = now();
            $cash_register->sellerUserOpening;

            $type_payments = [];

            $type_sales = [
                'normal' => ['transaction' => 0,'sales' => 0],
                'promocion' => ['transaction' => 0,'sales' => 0],
                'convenio' => ['transaction' => 0,'sales' => 0],
                'cortesia' => ['transaction' => 0,'sales' => 0],
                'total' => ['transaction' => 0,'sales' => 0],
            ];

            $partially_canceled = [
                'normal' => ['transaction' => 0,'sales' => 0],
                'promocion' => ['transaction' => 0,'sales' => 0],
                'convenio' => ['transaction' => 0,'sales' => 0],
                'cortesia' => ['transaction' => 0,'sales' => 0],
                'total' => ['transaction' => 0,'sales' => 0],
            ];

            $installment_sale = [
                'normal' => ['transaction' => 0,'sales' => 0],
                'promocion' => ['transaction' => 0,'sales' => 0],
                'convenio' => ['transaction' => 0,'sales' => 0],
                'total' => ['transaction' => 0,'sales' => 0],
            ];

            $canceled = [
                'total' => ['transaction' => 0,'sales' => 0],
            ];


            function updateSales(&$sales, $type, $isFlag, &$isFlagVariable, $transactionType) {
                $sales[$type]['sales']++;
                if ($isFlag) {
                    $sales[$type][$transactionType]++;
                    $isFlagVariable = false;
                }
            }

            function agreement_promotion($sale_ticket) {
                return $sale_ticket->eventSeatCatalogs->contains(function ($eventSeatCatalog) {
                    return $eventSeatCatalog->pivot->agreement_promotion_id !== null;
                });
            }

            function has_cortesia($sale_ticket, $global_payment_type_id) {
                return $sale_ticket->globalPaymentTypes->contains(function ($global_payment_type) use ($global_payment_type_id) {
                    return $global_payment_type->pivot->global_payment_type_id == $global_payment_type_id;
                });
            }

            /*
            * Get all sale tickets associated with the cash register
            */
            $sale_tickets = $cash_register->saleTickets()->orderBy('created_at', 'desc')->get();

            $sale_tickets_installment_payment_to_ignore = collect([]);

            $sale_tickets->each(function ($sale_ticket) use (&$type_payments, $global_card_payment_type,$status,&$seat_catalogue_status_id, &$global_payment_type_id, &$type_sales, $partially_canceled, &$installment_sale, &$canceled, &$sale_tickets_installment_payment_to_ignore) {

                if($sale_ticket->globalPaymentTypes->first()->installment_payment_history_id){
                    $sale_tickets_installment_payment_to_ignore->push($sale_ticket->globalPaymentTypes->first()->installment_payment_history_id);
                }
                if($sale_ticket->installmentPaymentHistories->count() === 1){
                    $sale_tickets_installment_payment_to_ignore->push($sale_ticket->installmentPaymentHistories->first()->id);
                }

                $sale_ticket->loadMissing(['saleTicketStatus', 'globalPaymentTypes', 'EventSeatCatalogues']);

                $sale_ticket->setAttribute('remaining_amount', $sale_ticket->saleDebtor ? ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sum('total_amount')) : 0);

                /*
                * Get all global payment types associated with the sale ticket
                */

                if($status->contains($sale_ticket->saleTicketStatus->name)){

                    $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) use ($sale_ticket, $global_card_payment_type) {

                        if ($global_payment_type->pivot->global_card_payment_type_id) {

                            $globalCardPaymentType = $global_card_payment_type->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);

                            if ($globalCardPaymentType) {
                                $global_payment_type->name .= " (".$globalCardPaymentType->name.")".($sale_ticket->payment_in_installments ? " a ".$sale_ticket->payment_in_installments." meses" : '');
                            }
                        }
                    });

                    $sale_ticket->installmentPaymentHistories->each(function ($installmentPaymentHistory) use ($sale_ticket, $global_card_payment_type) {
                        $installmentPaymentHistory->globalPaymentTypes->each(function ($global_payment_type) use ($sale_ticket, $global_card_payment_type) {

                            if ($global_payment_type->pivot->global_card_payment_type_id) {

                                $globalCardPaymentType = $global_card_payment_type->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);

                                if ($globalCardPaymentType) {
                                    $global_payment_type->name .= " (".$globalCardPaymentType->name.")".($sale_ticket->payment_in_installments ? " a ".$sale_ticket->payment_in_installments." meses" : '');
                                }
                            }
                        });
                    });

                    $payment_types = $sale_ticket->globalPaymentTypes;
                    $payment_count = $payment_types->count();
                    $total_amount = $sale_ticket->total_amount;
                    $has_debt = $sale_ticket->saleDebtor;

                    if ($payment_count == 1) {

                        $global_payment_type = $payment_types->first();
                        $name = $global_payment_type->name;
                        $amount = $global_payment_type->pivot->amount;
                        $type_payments[$name] = $type_payments[$name] ?? [
                                'initial_amount' => 0,
                                'amount' => 0,
                        ];

                        $type_payments[$name]['initial_amount'] += $amount;
                        $type_payments[$name]['amount'] += $has_debt ? $total_amount :  $amount;

                    }else {

                        foreach ($payment_types as $global_payment_type) {

                            $name = $global_payment_type->name;
                            $amount = $global_payment_type->pivot->amount;
                            $type_payments[$name] = $type_payments[$name] ?? [
                                    'initial_amount' => 0,
                                    'amount' => 0,

                            ];

                            $type_payments[$name]['initial_amount'] += $amount;
                            $type_payments[$name]['amount'] += $has_debt ? $total_amount :  $amount;
                        }
                    }
                }

                $isAgreement = true;
                $isPromotion = true;
                $isCortesia = true;
                $isNormal = true;

                if($sale_ticket->saleTicketStatus->name == 'pagado'){

                    foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                        if($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_id){
                            $type_sales['total']['sales']++;

                            $has_agreement_promotion = agreement_promotion($sale_ticket);
                            $has_cortesia = has_cortesia($sale_ticket, $global_payment_type_id);

                            if ($has_agreement_promotion) {
                                updateSales($type_sales, "convenio", $isAgreement, $isAgreement, "transaction");
                            }else if($sale_ticket->promotion_id && !$has_agreement_promotion){
                                updateSales($type_sales, "promocion", $isPromotion, $isPromotion, "transaction");
                            }else if ($has_cortesia){
                                updateSales($type_sales, "cortesia", $isCortesia, $isCortesia, "transaction");
                            }else{
                                updateSales($type_sales, "normal", $isNormal, $isNormal, "transaction");
                            }
                        }
                    }
                }else if($sale_ticket->saleTicketStatus->name == 'parcialmente cancelado'){

                    foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                        if($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_id){
                            $partially_canceled['total']['sales']++;

                            $has_agreement_promotion = agreement_promotion($sale_ticket);
                            $has_cortesia = has_cortesia($sale_ticket, $global_payment_type_id);

                            if ($has_agreement_promotion) {
                                updateSales($partially_canceled, "convenio", $isAgreement, $isAgreement, "transaction");
                            }else if($sale_ticket->promotion_id && !$has_agreement_promotion){
                                updateSales($partially_canceled, "promocion", $isPromotion, $isPromotion, "transaction");
                            }else if ($has_cortesia){
                                updateSales($partially_canceled, "cortesia", $isCortesia, $isCortesia, "transaction");
                            }else{
                                updateSales($partially_canceled, "normal", $isNormal, $isNormal, "transaction");
                            }
                        }
                    }
                }else if($sale_ticket->saleTicketStatus->name == 'pendiente'){

                    foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                        if($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_id){
                            $installment_sale['total']['sales']++;

                            $has_agreement_promotion = agreement_promotion($sale_ticket);
                            $has_cortesia = has_cortesia($sale_ticket, $global_payment_type_id);

                            if ($has_agreement_promotion) {
                                updateSales($installment_sale, "convenio", $isAgreement, $isAgreement, "transaction");
                            }else if($sale_ticket->promotion_id && !$has_agreement_promotion){
                                updateSales($installment_sale, "promocion", $isPromotion, $isPromotion, "transaction");
                            }else if ($has_cortesia){
                                updateSales($installment_sale, "cortesia", $isCortesia, $isCortesia, "transaction");
                            }else{
                                updateSales($installment_sale, "normal", $isNormal, $isNormal, "transaction");
                            }
                        }
                    }
                }else{
                    $canceled['total']['transaction']++;
                }
            });

            $remaining_amount = $sale_tickets->sum('remaining_amount');

            $cash_register->loadMissing(['installmentPaymentHistories.saleTicket', 'installmentPaymentHistories.globalPaymentTypes']);

            $sale_tickets_intallment_payment = $cash_register->installmentPaymentHistories->whereNotIn('id',$sale_tickets_installment_payment_to_ignore)->map(function ($installmentPaymentHistory) use (&$type_payments, $global_card_payment_type) {

                $saleTicket =  clone $installmentPaymentHistory->saleTicket;

                $saleTicket->loadMissing([
                    'saleTicketStatus',
                    'globalPaymentTypes',
                    'EventSeatCatalogues.seatCatalogue',
                    'saleDebtor',
                    'installmentPaymentHistories',
                    'promotion.promotionType'
                ]);

                /**
                 * Add remaining amount if it is a payment in installments
                 */
                $saleTicket->setRelation('globalPaymentTypes',  $installmentPaymentHistory->globalPaymentTypes);
                $saleTicket->setAttribute('remaining_amount',  $saleTicket->total_amount - $saleTicket->installmentPaymentHistories->sum('total_amount') );
                $saleTicket->setAttribute('amount_received',  $installmentPaymentHistory->total_amount);
                $saleTicket->setAttribute('total_amount',  $saleTicket->total_amount);
                $saleTicket->setAttribute('total_returned',  0);
                $saleTicket->setAttribute('created_at',  $installmentPaymentHistory->created_at);

                /*
                * Get all global payment types associated with the sale ticket
                */
                $saleTicket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments, $global_card_payment_type, $saleTicket) {

                    if ($global_payment_type->pivot->global_card_payment_type_id) {

                        $globalCardPaymentType = $global_card_payment_type->firstWhere('id', $global_payment_type->pivot->global_card_payment_type_id);

                        $global_payment_type->name .= " (".$globalCardPaymentType->name.")"." - Abonos a Deuda";
                    }else{
                        $global_payment_type->name .= " - Abonos a Deuda";
                    }

                    $payment_types = $saleTicket->globalPaymentTypes;
                    $payment_count = $payment_types->count();

                    if ($payment_count == 1) {

                        $global_payment_type = $payment_types->first();
                        $name = $global_payment_type->name;
                        $amount = $global_payment_type->pivot->amount;
                        $type_payments[$name] = $type_payments[$name] ?? [
                                'initial_amount' => 0,
                                'amount' => 0,
                        ];

                        $type_payments[$name]['initial_amount'] += $amount;
                        $type_payments[$name]['amount'] += 0;

                    }else {

                        foreach ($payment_types as $global_payment_type) {

                            $name = $global_payment_type->name;
                            $amount = $global_payment_type->pivot->amount;
                            $type_payments[$name] = $type_payments[$name] ?? [
                                    'initial_amount' => 0,
                                    'amount' => 0,

                            ];

                            $type_payments[$name]['initial_amount'] += $amount;
                            $type_payments[$name]['amount'] += 0;
                        }
                    }
                });



                return $saleTicket;
            });

            $type_payments_default = [
                'tarjeta',
                'efectivo',
            ];

            $type_payments_sales = [];

            collect($type_payments)->each(function ($value, $key) use (&$type_payments_sales, $type_payments_default) {

                foreach ($type_payments_default as $default) {
                    if (Str::contains($key, $default)) {

                        $type_payments_sales[$default] = $type_payments_sales[$default] ?? [
                            'initial_amount' => 0,
                            'amount' => 0,
                        ];
                        $type_payments_sales[$default]['initial_amount'] += $value['initial_amount'] ?? 0;
                        $type_payments_sales[$default]['amount'] += $value['amount'] ?? 0;
                    }
                }
            });

            if (isset($type_payments_sales['tarjeta'])) {
                $type_payments_sales['tarjeta (debito + credito)'] = $type_payments_sales['tarjeta'];
                unset($type_payments_sales['tarjeta']);
            }

            $response = [
                'ticket_office' => $cash_register->ticketOffice,
                'cash_register' => $cash_register,
                'sale_tickets' => $sale_tickets->concat($sale_tickets_intallment_payment)->sortByDesc('created_at')->values(),
                'type_payments' => $type_payments,
                'type_payments_sales' => $type_payments_sales,
                'remaining_amount' => $remaining_amount,
                'type_sales' => $type_sales,
                'partially_canceled'=> $partially_canceled,
                'installment_sale' => $installment_sale,
                'installment_payment_sale' => $sale_tickets_intallment_payment->count(),
                'canceled'=> $canceled
            ];

            return $response;


        } catch(\Exception $e){
            throw $e;
        }
    }


    public function updateCashRegister(array $data)
    {
        try {
            $cash_register = $this->getById($data['cash_register_id']);

            $cash_register_movement = $this->cash_register_movement_service->save([
                'cash_register_id' => $data['cash_register_id'],
                'cash_register_movement_type_id' => $this->cash_register_movement_type_Service->getByName($data['cash_register_movement_type'])->id,
                'sale_ticket_id' => $data['sale_ticket_id'],
                'previous_balance' => $cash_register->current_balance,
                'movement_amount' => $data['total_amount'],
                'new_balance' => $cash_register->current_balance + $data['total_amount'],
            ]);

            /*
            * Update cash register balance
            */
            $cash_register->current_balance = $cash_register_movement->new_balance;
            $cash_register->save();

        } catch (\Exception $e) {
            throw $e;
        }
    }

}
