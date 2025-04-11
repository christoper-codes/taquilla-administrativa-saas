<?php

namespace App\Services;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;
use App\Models\CashRegisterType;
use App\Models\GlobalPaymentType;
use App\Models\SeatCatalogueStatus;
use App\Models\GlobalCardPaymentType;
use Illuminate\Support\Str;

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

            $globalCardPaymentType = GlobalCardPaymentType::all();
            $status = collect(['pagado', 'pendiente', 'parcialmente cancelado']);

            $type_payments = [];

            /*
            * Get all sale tickets associated with the cash register
            */
            $sale_tickets = $cash_register->saleTickets()->orderBy('created_at', 'desc')->get();

            $sale_tickets->each(function ($sale_ticket) use (&$type_payments, $status, $globalCardPaymentType) {

                    $sale_ticket->loadMissing(['saleTicketStatus', 'globalPaymentTypes', 'EventSeatCatalogues.seatCatalogue', 'saleDebtor', 'installmentPaymentHistories', 'promotion.promotionType']);
                    /**
                     * Add remaining amount if it is a payment in installments
                     */

                    $sale_ticket->setAttribute('remaining_amount', $sale_ticket->saleDebtor ? ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sum('total_amount')) : 0);

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

                                $type_payments[$global_payment_type->name]['amount'] += ($sale_ticket->total_amount - $sale_ticket->installmentPaymentHistories->sum('total_amount'));

                                $dataTemp = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);

                                if (!isset($type_payments[$dataTemp])) {
                                    $type_payments[$dataTemp] = ['amount' => 0];
                                }

                                $type_payments[$dataTemp]['amount'] += $sale_ticket->installmentPaymentHistories->sum('total_amount');

                            }else{
                                $type_payments[$global_payment_type->name]['amount'] += $global_payment_type->pivot->amount;
                            }
                        }

                        $global_payment_type->name = Str::replace(" - Deuda a plazos", '', $global_payment_type->name);

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
            $cash_register->is_open = false;
            $cash_register->confirmed_closure = true;
            $cash_register->seller_user_closing_id = $data['seller_user_closing_id'];
            $cash_register->closing_balance = $cash_register->current_balance;
            $cash_register->closing_time = now();
            $cash_register->save();
            $cash_register->sellerUserOpening;

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

            $sale_tickets->each(function ($sale_ticket) use (&$type_payments, $global_card_payment_type,$status,&$seat_catalogue_status_id, &$global_payment_type_id, &$type_sales, $partially_canceled, &$installment_sale, &$canceled) {

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

                    $payment_types = $sale_ticket->globalPaymentTypes;
                    $payment_count = $payment_types->count();


                    if ($payment_count == 1) {

                        $global_payment_type = $payment_types->first();
                        $name = $global_payment_type->name;
                        $has_debt = $sale_ticket->saleDebtor;
                        $total_amount = $sale_ticket->total_amount;
                        $amount = $global_payment_type->pivot->amount;

                        $type_payments[$name] = $type_payments[$name] ?? [
                                'initial_amount' => 0,
                                'amount' => 0,
                                'remaining_amount' => 0
                                // 'transactions' => 0,
                                // 'seats' => 0
                        ];

                        $type_payments[$name]['initial_amount'] += $amount;
                        $type_payments[$name]['amount'] += $has_debt ? $total_amount :  $amount;
                        $type_payments[$name]['remaining_amount'] += $has_debt ? ($total_amount - $amount) : ($amount - $amount);
                        // $type_payments[$name]['transactions']++;
                        // $type_payments[$name]['seats'] += $sale_ticket->EventSeatCatalogues->count();

                    }else {

                        // $name = 'pago compuesto'.($sale_ticket->payment_in_installments ? " a ".$sale_ticket->payment_in_installments." meses" : '');

                        // $type_payments[$name] = $type_payments[$name] ?? [
                        //     'remaining_amount_list' => [],
                        //     'initial_amount_list' => [],
                        //     'amountList' => [],
                        //     'transactions' => 0,
                        //     'seats' => 0
                        // ];

                        foreach ($payment_types as $global_payment_type) {

                            $name = $global_payment_type->name;
                            $has_debt = $sale_ticket->saleDebtor;
                            $total_amount = $sale_ticket->total_amount;
                            $amount = $global_payment_type->pivot->amount;

                            $type_payments[$name] = $type_payments[$name] ?? [
                                    'initial_amount' => 0,
                                    'amount' => 0,
                                    'remaining_amount' => 0,
                                    // 'transactions' => 0,
                                    // 'seats' => 0
                            ];

                            $type_payments[$name]['initial_amount'] += $amount;
                            $type_payments[$name]['amount'] += $has_debt ? $total_amount :  $amount;
                            $type_payments[$name]['remaining_amount'] += $has_debt ? ($total_amount - $amount) : ($amount - $amount);
                            // $type_payments[$name]['transactions']++;
                            // $type_payments[$name]['seats'] += $sale_ticket->EventSeatCatalogues->count();

                            // $type_name = $global_payment_type->name;

                            // $type_payments[$name]['remaining_amount_list'][$type_name] = $type_payments[$name]['remaining_amount_list'][$type_name] ?? ['amount' => 0];
                            // $type_payments[$name]['initial_amount_list'][$type_name] = $type_payments[$name]['initial_amount_list'][$type_name] ?? ['amount' => 0];
                            // $type_payments[$name]['amountList'][$type_name] = $type_payments[$name]['amountList'][$type_name] ?? ['amount' => 0];

                            // $amount = $global_payment_type->pivot->amount;

                            // $type_payments[$name]['initial_amount_list'][$type_name]['amount'] += $amount;
                            // $type_payments[$name]['amountList'][$type_name]['amount'] +=  $sale_ticket->saleDebtor ? $sale_ticket->total_amount : $amount;
                            // $type_payments[$name]['remaining_amount_list'][$type_name]['amount'] += $sale_ticket->saleDebtor ? ($sale_ticket->total_amount - $amount) : ($amount - $amount);
                        }

                        // $type_payments[$name]['transactions']++;
                        // $type_payments[$name]['seats'] += $sale_ticket->EventSeatCatalogues->count();
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

            $response = [
                'ticket_office' => $cash_register->ticketOffice,
                'cash_register' => $cash_register,
                'sale_tickets' => $sale_tickets,
                'type_payments' => $type_payments,
                'type_sales' => $type_sales,
                'partially_canceled'=> $partially_canceled,
                'installment_sale' => $installment_sale,
                'canceled'=> $canceled
            ];

            return $response;


        } catch(\Exception $e){
            throw $e;
        }
    }

}
