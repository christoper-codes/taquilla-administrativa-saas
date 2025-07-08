<?php

namespace App\Services;

use App\Enums\PurchaseTypes;
use App\Models\CashRegisterMovement;
use App\Models\CashRegisterMovementType;
use App\Models\Event;
use App\Models\GlobalCardPaymentType;
use App\Models\GlobalPaymentType;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
use App\Models\SeatCatalogueStatus;
use App\Repositories\SaleTicketRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOption\Some;

class SaleTicketService
{
     /*
    * |--------------------------------------------------------------------------
    * | SaleTicketService the repository services for global module by Christoper Patiño
    */
    protected $sale_ticket_repository;

    public function __construct(SaleTicketRepository $sale_ticket_repository)
    {
        $this->sale_ticket_repository = $sale_ticket_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all SaleTickets
    */
    public function getAll()
    {
        try {

            $sale_tickets = $this->sale_ticket_repository->getAll();
            return $sale_tickets;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get SaleTicket by id
    */
    public function getByIdWithRelations($id)
    {
        try {
            return $this->sale_ticket_repository->getByIdWithRelations($id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

     /*
    * |--------------------------------------------------------------------------
    * | Cacellation of tickets
    */
    public function cancellationSaleTickets(array $data)
    {
        try {
            /*
            * Update sale ticket status
            */
            $sale_ticket = SaleTicket::find($data['sale_ticket_id']);

            /*
            * Get cash register
            */
            $cash_register = $sale_ticket->cashRegister;
            $balance_to_returned = 0;
            $cash_register_movement_type_id = null;

            if($data['is_partial_cancel']){
                $sale_ticket->sale_ticket_status_id = SaleTicketStatus::where('name', 'parcialmente cancelado')->first()->id;
                $cash_register_movement_type_id = CashRegisterMovementType::where('name', 'cancelacion parcial de venta')->first()->id;
            } else {
                $sale_ticket->sale_ticket_status_id = SaleTicketStatus::where('name', 'cancelado')->first()->id;
                $cash_register_movement_type_id = CashRegisterMovementType::where('name', 'cancelacion total de venta')->first()->id;
            }
            $sale_ticket->save();

            /*
            * Update pivot beetwen sale tickets and event seat catalog
            */
            $seat_catalogue_status_id = SeatCatalogueStatus::where('name', 'disponible')->first()->id;
            foreach($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                if($data['is_partial_cancel']){
                    if (in_array($event_seat_catalog->seatCatalogue->code, $data['cancel_seat_codes'])) {
                        $balance_to_returned += $event_seat_catalog->price;
                       $this->updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $data['payment_types_selected_keys']);
                    }

                } else {
                    $balance_to_returned += $event_seat_catalog->price;
                    $this->updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $data['payment_types_selected_keys']);
                }
            }

            /*
            * Intance a new cash register movement
            */
            $cash_register_movement = new CashRegisterMovement();
            $cash_register_movement->cash_register_id = $cash_register->id;
            $cash_register_movement->cash_register_movement_type_id = $cash_register_movement_type_id;
            $cash_register_movement->sale_ticket_id = $sale_ticket->id;
            $cash_register_movement->previous_balance = $cash_register->current_balance;
            $cash_register_movement->movement_amount = $balance_to_returned;
            $cash_register_movement->new_balance = ($cash_register->current_balance - $balance_to_returned);
            $cash_register_movement->is_active = true;
            $cash_register_movement->save();

            /*
            * Update cash register
            */
            $cash_register->current_balance = ($cash_register->current_balance - $balance_to_returned);
            $cash_register->save();

            return true;
        } catch(\Exception $e){
            throw $e;
        }
    }

    private function updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $payment_types_selected_keys)
    {
        /*
        * Update sale ticket
        */
        $sale_ticket->total_amount = ($sale_ticket->total_amount - $event_seat_catalog->price);
        $sale_ticket->total_returned = ($sale_ticket->total_returned + $event_seat_catalog->price);
        $sale_ticket->save();

        /*
        * Update pivot beetwen sale ticket and global payment types
        */
        $remainingAmount = $event_seat_catalog->price;
        foreach ($payment_types_selected_keys as $payment_type_key) {
            $global_payment_type = GlobalPaymentType::where('name', $payment_type_key)->first();

            if ($global_payment_type) {
                $currentAmount = $sale_ticket->globalPaymentTypes()->where('global_payment_type_id', $global_payment_type->id)->first()->pivot->amount;

                if ($remainingAmount > 0) {
                    if ($currentAmount >= $remainingAmount) {
                        $sale_ticket->globalPaymentTypes()->updateExistingPivot($global_payment_type->id, [
                            'amount' => $currentAmount - $remainingAmount
                        ]);
                        $remainingAmount = 0;
                    } else {
                        $sale_ticket->globalPaymentTypes()->updateExistingPivot($global_payment_type->id, [
                            'amount' => 0
                        ]);
                        $remainingAmount -= $currentAmount;
                    }
                } else {
                    break;
                }
            }
        }

        /*
        * Update event seat catalog
        */
        $event_seat_catalog->user_id = null;
        $event_seat_catalog->seat_catalogue_status_id = $seat_catalogue_status_id;
        $event_seat_catalog->sale_ticket_id = null;
        $event_seat_catalog->qr = null;
        $event_seat_catalog->price = 0.00;
        $event_seat_catalog->is_verified = false;
        $event_seat_catalog->save();

        $sale_ticket->eventSeatCatalogs()->updateExistingPivot($event_seat_catalog->id, [
            'is_active' => false
        ]);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get sale tickets per week in a month
    */
    public function saleTicketsPerWeekInMonth(array $data)
    {
        try {

            $type_payments = [];
            $type_sales = [
                'total' => ['sales' => 0],
                'promocion' => ['sales' => 0],
                'convenio' => ['sales' => 0],
                'cortesia' => ['sales' => 0],
            ];
            $seat_catalogue_status_id = SeatCatalogueStatus::where('name', 'vendido')->first()->id;
            $global_payment_type_id = GlobalPaymentType::where('name', 'cortesia')->first()->id;


            $current_date = Carbon::now();
            $start_date = $current_date->copy()->startOfMonth();
            $end_date = $current_date->copy()->endOfMonth();
            $weeks = [];

            while ($start_date->lte($end_date)) {
                $total_seats_sold = 0;
                $week_start = $start_date->copy();
                $week_end = $start_date->copy()->addDays(6); // Sumar 6 días para completar la semana de 7 días

                if ($week_end->gt($end_date)) {
                    $week_end = $end_date; // Asegurarse de que week_end no se extienda más allá de end_date
                }

                // Si la semana comienza después del 22 y week_end aún no ha alcanzado el final del mes
                if ($week_start->day >= 22) {
                    $week_end = $end_date; // Asegurarse de que esta sea la última semana hasta el final del mes
                }

                $sale_tickets = SaleTicket::whereBetween('created_at', [$week_start, $week_end])
                    ->where(function ($query) {
                            $pagado_status_id = SaleTicketStatus::where('name', 'pagado')->first()->id;
                            $pendiente_status_id = SaleTicketStatus::where('name', 'pendiente')->first()->id;
                            $query->where('sale_ticket_status_id', $pagado_status_id)
                                ->orWhere('sale_ticket_status_id', $pendiente_status_id);
                    })
                    ->where('stadium_id', $data['stadium_id'])
                    ->get()->each(function($sale_ticket) use (&$type_payments, &$seat_catalogue_status_id, &$global_payment_type_id, &$type_sales, &$total_seats_sold) {
                        if($sale_ticket->saleTicketStatus->name == 'pagado' || $sale_ticket->saleTicketStatus->name == 'parcialmente cancelado' || $sale_ticket->saleTicketStatus->name == 'pendiente'){

                            foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                                 if($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_id){
                                    $total_seats_sold++;
                                     $type_sales['total']['sales']++;

                                     $has_agreement_promotion = $sale_ticket->eventSeatCatalogs->contains(function ($eventSeatCatalog) {
                                         return $eventSeatCatalog->pivot->agreement_promotion_id !== null; });
                                     if ($has_agreement_promotion) {
                                         $type_sales['convenio']['sales']++;
                                     }

                                     if($sale_ticket->promotion_id && !$has_agreement_promotion){
                                         $type_sales['promocion']['sales']++;
                                     }

                                     $has_cortesia = $sale_ticket->globalPaymentTypes->contains(function ($global_payment_type) use ($global_payment_type_id) {
                                         return $global_payment_type->pivot->global_payment_type_id == $global_payment_type_id;
                                     });

                                     if ($has_cortesia){
                                         $type_sales['cortesia']['sales']++;
                                     }
                                 }
                             }
                         }


                        /**
                         * Remplace the global payment types with the ones from the installment payment history
                         * to get the correct amount of each payment type
                         */
                        if($sale_ticket->saleDebtor){
                            $sale_ticket->setRelation('globalPaymentTypes',  $sale_ticket->installmentPaymentHistories->flatMap(function ($installment_payment_history) {
                                return $installment_payment_history->globalPaymentTypes;
                            }));
                        }

                         /*
                        * Get all global payment types associated with the sale ticket
                        */
                        $payment_types = $sale_ticket->globalPaymentTypes;
                        $payment_count = $payment_types->count();

                        if ($payment_count == 1) {
                            /*
                            * case with only one type payment
                            */
                            $global_payment_type = $payment_types->first();
                            if (!isset($type_payments[$global_payment_type->name])) {
                                $type_payments[$global_payment_type->name] = [
                                    'amount' => 0,
                                    'transactions' => 0,
                                ];
                            }
                            $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                            $type_payments[$global_payment_type->name]['transactions']++;
                        } else {
                            /*
                            * case with multiple type payments
                            */
                            $composite_key = 'pago compuesto';
                            if (!isset($type_payments[$composite_key])) {
                                $type_payments[$composite_key] = [
                                    'amount' => 0,
                                    'transactions' => 0,
                                ];
                            }
                            foreach ($payment_types as $global_payment_type) {
                                if($global_payment_type->name == 'tarjeta'){
                                    if(!isset($type_payments['tarjeta'])){
                                        $type_payments['tarjeta'] = [
                                            'amount' => 0,
                                            'transactions' => 0,
                                        ];
                                    }
                                    $type_payments['tarjeta']['amount'] += $global_payment_type->pivot->amount;
                                } else if($global_payment_type->name == 'efectivo'){
                                    if(!isset($type_payments['efectivo'])){
                                        $type_payments['efectivo'] = [
                                            'amount' => 0,
                                            'transactions' => 0,
                                        ];
                                    }
                                    $type_payments['efectivo']['amount'] += $global_payment_type->pivot->amount;
                                }

                            }
                            $type_payments[$composite_key]['transactions']++;
                        }
                    });

                $weeks[] = [
                    'week_start' => $week_start->toDateString(),
                    'week_end' => $week_end->toDateString(),
                    'tickets' => $total_seats_sold,
                ];

                $start_date = $week_end->addDay(); // Avanzar al primer día de la siguiente semana

                // Asegurarse de que el start_date no se pase al siguiente mes
                if ($start_date->month != $current_date->month) {
                    break;
                }
            }

            // Eliminar la última semana si no comienza después del día 22
            if (isset($weeks[4]) && $weeks[4]['week_start'] >= '2025-01-22') {
                $last_week = array_pop($weeks);
                $previous_week = &$weeks[count($weeks) - 1];
                $previous_week['week_end'] = $last_week['week_end'];
                $previous_week['tickets'] = $previous_week['tickets']->merge($last_week['tickets']);
            }

            $response = [
                'weeks' => $weeks,
                'type_payments' => $type_payments,
                'type_sales' => $type_sales,
                'events' => Event::where('stadium_id', $data['stadium_id'])->get(),
            ];

            return $response;

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function getHistoryPerEvent(array $data)
    {
        try {
            $event = Event::find($data['event_id']);
            $event->globalImage;
            $event->serie->globalSeason;

            $seat_catalogue_status_vendido_id = SeatCatalogueStatus::where('name', 'vendido')->first()->id;
            $global_payment_type_cortesia_id = GlobalPaymentType::where('name', 'cortesia')->first()->id;
            $type_payments = [];
            $total_seats_sold = 0;
            $type_sales = [
                'total' => ['sales' => 0],
                'promocion' => ['sales' => 0],
                'convenio' => ['sales' => 0],
                'cortesia' => ['sales' => 0],
                'abonado' => ['sales' => 0],
                'regular' => ['sales' => 0],
                'online' => ['sales' => 0],
            ];

            $current_date = Carbon::now();
            $start_date = $current_date->copy()->startOfMonth();
            $end_date = $current_date->copy()->endOfMonth();
            $days = [];

            while ($start_date->lte($end_date)) {
                $total_seats_sold_day = 0;
                $day_start = $start_date->copy()->startOfDay();
                $day_end = $start_date->copy()->endOfDay();

                $sale_tickets = $event->saleTickets()
                    ->whereBetween('sale_tickets.created_at', [$day_start, $day_end])
                    ->where('sale_tickets.stadium_id', 1)
                    ->where(function ($query) {
                        $pagado_status_id = SaleTicketStatus::where('name', 'pagado')->first()->id;
                        $pendiente_status_id = SaleTicketStatus::where('name', 'pendiente')->first()->id;
                        $query->where('sale_tickets.sale_ticket_status_id', $pagado_status_id)
                            ->orWhere('sale_tickets.sale_ticket_status_id', $pendiente_status_id);
                    })
                    ->get();

                $sale_tickets->each(function ($sale_ticket) use (&$type_payments, &$seat_catalogue_status_vendido_id, &$total_seats_sold, &$total_seats_sold_day, &$type_sales, &$global_payment_type_cortesia_id) {
                    /*
                    * Get all global payment types associated with the sale ticket
                    */

                    if($sale_ticket->saleDebtor){
                        $sale_ticket->setRelation('globalPaymentTypes',  $sale_ticket->installmentPaymentHistories->flatMap(function ($installment_payment_history) {
                            return $installment_payment_history->globalPaymentTypes;
                        }));
                    }

                    $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) use (&$type_payments) {
                        if (!isset($type_payments[$global_payment_type->name])) {
                            $type_payments[$global_payment_type->name] = [
                                'amount' => 0,
                                'transactions' => 0,
                            ];
                        }
                        $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                        $type_payments[$global_payment_type->name]['transactions']++;
                    });

                    if ($sale_ticket->saleTicketStatus->name == 'pagado' || $sale_ticket->saleTicketStatus->name == 'parcialmente cancelado' || $sale_ticket->saleTicketStatus->name == 'pendiente') {
                        foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                            if ($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_vendido_id) {
                                $total_seats_sold++;
                                $total_seats_sold_day++;
                                $type_sales['total']['sales']++;

                                $has_agreement_promotion = $sale_ticket->eventSeatCatalogs->contains(function ($eventSeatCatalog) {
                                    return $eventSeatCatalog->pivot->agreement_promotion_id !== null;
                                });

                                $has_cortesia = $sale_ticket->globalPaymentTypes->contains(function ($global_payment_type) use ($global_payment_type_cortesia_id) {
                                    return $global_payment_type->pivot->global_payment_type_id == $global_payment_type_cortesia_id;
                                });

                                if ($has_agreement_promotion) {
                                    $type_sales['convenio']['sales']++;
                                } else if ($sale_ticket->promotion_id && !$has_agreement_promotion) {
                                    $type_sales['promocion']['sales']++;
                                } else if ($has_cortesia) {
                                    $type_sales['cortesia']['sales']++;
                                } else if($event_seat_catalog->purchase_type == PurchaseTypes::SEASON_TICKET->value){
                                    $type_sales['abonado']['sales']++;
                                } else {
                                    $type_sales['regular']['sales']++;
                                }

                                if($sale_ticket->is_online){
                                    $type_sales['online']['sales']++;
                                }
                            }
                        }
                    }
                });

                $days[] = [
                    'day' => $day_start->toDateString(),
                    'tickets' => $total_seats_sold_day,
                ];

                $start_date->addDay();
            }

            $new_data = $this->getHistoryPerEventTotal($data);

            $response = [
                'event' => $event,
                'total_seats_sold' => $new_data['total_seats_sold'],
                'type_payments' => $new_data['type_payments'],
                'type_sales' => $new_data['type_sales'],
                'sales_per_day' => $days,
                'new_data' => $new_data['event'],
                'availability' => $new_data['availability'],
            ];

            return $response;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getHistoryPerEventTotal(array $data)
    {
        try {
            $event = Event::find($data['event_id']);
            $event->saleTickets->each(function ($sale_ticket) {
                $sale_ticket->installmentPaymentHistories;
                $sale_ticket->eventSeatCatalogs;
                $sale_ticket->globalPaymentTypes;
                $sale_ticket->globalPaymentTypes->each(function ($global_payment_type) {
                    if ($global_payment_type->pivot->global_card_payment_type_id) {
                        $global_payment_type->pivot->card_type_name = GlobalCardPaymentType::find($global_payment_type->pivot->global_card_payment_type_id)->name;
                    }
                });
                $sale_ticket->promotion;
                $sale_ticket->cashRegister;
                $sale_ticket->saleTicketStatus;
                $sale_ticket->sellerUser;
                $sale_ticket->saleDebtor;
                $sale_ticket->eventSeatCatalogs->each(function ($event_seat_catalog) {
                    $event_seat_catalog->seatCatalogue->seatCatalogueStatus;
                    $event_seat_catalog->seasonTicket;
                });
            });
            $seat_catalogue_status_vendido_id = SeatCatalogueStatus::where('name', 'vendido')->first()->id;
            $global_payment_type_cortesia_id = GlobalPaymentType::where('name', 'cortesia')->first()->id;

            $type_payments = [];
            $total_seats_sold = 0;
            $type_sales = [
                'total' => ['sales' => 0],
                'online' => ['sales' => 0],
                'taquilla' => ['sales' => 0],
                'taquilla cortesias' => ['sales' => 0],
                'promocion' => ['sales' => 0],
                'convenio' => ['sales' => 0],
                'cortesia' => ['sales' => 0],
                'abonado' => ['sales' => 0],
                'regular' => ['sales' => 0],
            ];

            $sale_tickets = $event->saleTickets()
                ->where('sale_tickets.stadium_id', 1)
                ->where(function ($query) {
                    $pagado_status_id = SaleTicketStatus::where('name', 'pagado')->first()->id;
                    $pendiente_status_id = SaleTicketStatus::where('name', 'pendiente')->first()->id;
                    $query->where('sale_tickets.sale_ticket_status_id', $pagado_status_id)
                        ->orWhere('sale_tickets.sale_ticket_status_id', $pendiente_status_id);
                })
                ->get();

            $sale_tickets->each(function ($sale_ticket) use (
                &$type_payments,
                &$seat_catalogue_status_vendido_id,
                &$total_seats_sold,
                &$type_sales,
                &$global_payment_type_cortesia_id
            ) {
                $sale_ticket->load('globalPaymentTypes');

                /**
                 * Remplace the global payment types with the ones from the installment payment history
                 * to get the correct amount of each payment type
                 */
                if($sale_ticket->saleDebtor){
                    $sale_ticket->setRelation('globalPaymentTypes',  $sale_ticket->installmentPaymentHistories->flatMap(function ($installment_payment_history) {
                        return $installment_payment_history->globalPaymentTypes;
                    }));
                }

                foreach ($sale_ticket->globalPaymentTypes as $global_payment_type) {
                    $name = $global_payment_type->name;

                    if (!isset($type_payments[$name])) {
                        $type_payments[$name] = [
                            'amount' => 0,
                            'transactions' => 0,
                        ];
                    }

                    $amount = $global_payment_type->pivot->amount ?? 0;

                    $type_payments[$name]['amount'] += $amount;
                    $type_payments[$name]['transactions']++;
                }

                if (in_array($sale_ticket->saleTicketStatus->name, ['pagado', 'parcialmente cancelado', 'pendiente'])) {
                    foreach ($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                        if ($event_seat_catalog->seat_catalogue_status_id == $seat_catalogue_status_vendido_id) {
                            $total_seats_sold++;
                            $type_sales['total']['sales']++;

                            $has_agreement_promotion = $sale_ticket->eventSeatCatalogs->contains(function ($esc) {
                                return $esc->pivot->agreement_promotion_id !== null;
                            });

                            $has_cortesia = $sale_ticket->globalPaymentTypes->contains(function ($global_payment_type) use ($global_payment_type_cortesia_id) {
                                return $global_payment_type->pivot->global_payment_type_id == $global_payment_type_cortesia_id;
                            });

                            if ($has_agreement_promotion) {
                                $type_sales['convenio']['sales']++;
                            } elseif ($sale_ticket->promotion_id && !$has_agreement_promotion) {
                                $type_sales['promocion']['sales']++;
                            } elseif ($has_cortesia) {
                                $type_sales['cortesia']['sales']++;
                            } elseif ($event_seat_catalog->purchase_type == PurchaseTypes::SEASON_TICKET->value) {
                                $type_sales['abonado']['sales']++;
                            } else {
                                $type_sales['regular']['sales']++;
                            }

                            if($sale_ticket->is_online){
                                $type_sales['online']['sales']++;
                            } elseif(!$sale_ticket->is_online && $has_cortesia){
                                $type_sales['taquilla cortesias']['sales']++;
                            } else {
                                $type_sales['taquilla']['sales']++;
                            }
                        }
                    }
                }
            });

            $status_ids = [
                'disponible' => SeatCatalogueStatus::where('name', 'disponible')->first()->id,
                'reservado' => SeatCatalogueStatus::where('name', 'reservado')->first()->id,
                'inhabilitado' => SeatCatalogueStatus::where('name', 'inhabilitado')->first()->id,
                'transito' => SeatCatalogueStatus::where('name', 'transito')->first()->id,
            ];

            $availability = [
                'disponibles' => 0,
                'inhabilitados' => 0,
                'abonados' => 0,
            ];

            $event->EventSeatCatalogues->each(function ($event_seat_catalog) use (&$availability, $status_ids) {
                if ($event_seat_catalog->seat_catalogue_status_id == $status_ids['disponible'] || $event_seat_catalog->seat_catalogue_status_id == $status_ids['transito'] || $event_seat_catalog->seat_catalogue_status_id == $status_ids['reservado']) {
                    $availability['disponibles']++;
                }
                if ($event_seat_catalog->seat_catalogue_status_id == $status_ids['inhabilitado']) {
                    $availability['inhabilitados']++;
                }
                if($event_seat_catalog->season_ticket_id){
                    $availability['abonados']++;
                }
            });

            return [
                'event' => $event,
                'total_seats_sold' => $total_seats_sold,
                'type_payments' => $type_payments,
                'type_sales' => $type_sales,
                'availability' => $availability,
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /*
    * |--------------------------------------------------------------------------
    * | Get all pending sale tickets
    */
    public function saleTicketsSaleDebtor($status, $stadium_id)
    {
        try {

            $pending_status_id = SaleTicketStatus::where('name', $status)->first()->id;
            $global_card_payment_type = GlobalCardPaymentType::all();

            return $this->sale_ticket_repository->getAll()->filter(function ($sale_ticket) use ($pending_status_id, $stadium_id) {
                return $sale_ticket->sale_ticket_status_id == $pending_status_id && $sale_ticket->sale_debtor_id != null && $sale_ticket->stadium_id == $stadium_id;
            })->each(function ($sale_ticket) use ($global_card_payment_type) {

                $sale_ticket->loadMissing([
                    'sellerUser','saleTicketStatus', 'globalPaymentTypes', 'EventSeatCatalogues.seatCatalogue', 'saleDebtor',
                    'installmentPaymentHistories.globalPaymentTypes','installmentPaymentHistories.cashRegister.sellerUserOpening','promotion.promotionType']);

                $sale_ticket->setRelation('EventSeatCatalogues',
                    $sale_ticket->EventSeatCatalogues
                        ->sortBy('created_at')
                        ->unique('seat_catalogue_id')
                        ->values()
                );

                $sale_ticket->installmentPaymentHistories->each(function ($installment_payment_history) use ($global_card_payment_type){
                    $installment_payment_history->globalPaymentTypes->each(function ($global_payment_type) use ($global_card_payment_type) {
                        if ($global_payment_type->pivot->global_card_payment_type_id) {
                            $global_card_payment_type_temp = $global_card_payment_type->where('id', $global_payment_type->pivot->global_card_payment_type_id)->first();
                            if ($global_card_payment_type_temp) {
                                $global_payment_type->name .= " (".$global_card_payment_type_temp->name.")";
                            }
                        }
                    });
                });

                /**
                 * Add remaining amount if it is a payment in installments
                 */

                $sale_ticket->setAttribute('remaining_amount', $sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sum('total_amount'));

            })->values();

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all pending sale tickets
    */
    public function saleTicketStatusPendingDebtor($stadium_id)
    {
        try {

            return $this->saleTicketsSaleDebtor('pendiente', $stadium_id);

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all pending sale tickets
    */
    public function saleTicketStatusPaidDebtor($stadium_id)
    {
        try {

            return $this->saleTicketsSaleDebtor('pagado', $stadium_id);

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update sale ticket status
    */
    public function updatedSaleTicketStatusForInstallmentPaymentHistories($ticket_id)
    {
        try {

            $sale_ticket = $this->sale_ticket_repository->getById($ticket_id);

            $sale_ticket->loadMissing(['installmentPaymentHistories.globalPaymentTypes']);

            $isPaid = $sale_ticket->total_amount == $sale_ticket->installmentPaymentHistories->sum('total_amount');

            if ($isPaid) {
                $sale_ticket->sale_ticket_status_id = SaleTicketStatus::where('name', 'pagado')->first()->id;
                $sale_ticket->save();
            }

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
