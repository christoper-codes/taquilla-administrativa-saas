<?php

namespace App\Services;

use App\Enums\PaymentInstallments;
use App\Enums\PurchaseTypes;
use App\Interfaces\EventRepositoryInterface;
use App\Models\CashRegisterMovement;
use App\Models\CashRegisterMovementType;
use App\Models\EventSeatCatalog;
use App\Models\EventSeatCatalogPriceType;
use App\Models\PriceTypeSeatCatalogue;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
use App\Models\GlobalCardPaymentType;
use App\Models\OnlinePaymentTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Facades\Cache;

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
    protected $season_ticket_service;
    protected $sale_debtor_service;
    protected $installment_payment_history_service;
    protected $online_payment_transaction_service;

    public function __construct(EventRepositoryInterface $event_repository, GlobalPaymentTypeService $global_payment_type_service,
                    GlobalCardPaymentTypeService $global_card_payment_type_service, CashRegisterService $cash_register_service,
                    SeasonTicketService $season_ticket_service, SaleDebtorService $sale_debtor_service,
                    InstallmentPaymentHistoryService $installment_payment_history_service,
                    OnlinePaymentTransactionService $online_payment_transaction_service)
    {
        $this->event_repository = $event_repository;
        $this->global_payment_type_service = $global_payment_type_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
        $this->cash_register_service = $cash_register_service;
        $this->season_ticket_service = $season_ticket_service;
        $this->sale_debtor_service = $sale_debtor_service;
        $this->installment_payment_history_service = $installment_payment_history_service;
        $this->online_payment_transaction_service = $online_payment_transaction_service;
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
    * | Get all active events
    */
    public function getAllActive()
    {
        try {
            $events = $this->event_repository->getAllActive();
            return $events;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all events with traffic
    */
    public function getAllWithTraffic()
    {
        try {
            $events = $this->event_repository->getAllWithTraffic();
            return $events;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Release reserved seats
    */
    public function releaseReservedSeats(int $event_id)
    {
        try {
            return $this->event_repository->releaseReservedSeats($event_id);
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
            $event->serie->globalSeason;
            $payment_installments = [];
            /*
            * Group seats by zone
            */
            // Agrupar asientos por zona
            $zones = $event->eventSeatCatalogues->groupBy(function ($item) {
                return $item->seatCatalogue->zone;
            });

            // Inicializar zonas
            $c_zone = $zones->get('C', collect());
            $a_zone = $zones->get('A', collect());
            $b_zone = $zones->get('B', collect());
            $e_zone = $zones->get('E', collect());
            $f_zone = $zones->get('F', collect());
            $h_zone = $zones->get('H', collect());

            /*
            * purchase types available
            */
            $purchase_types = [PurchaseTypes::MATCH->value];
            $events_by_serie = $this->event_repository->getEventsBySerie($event->serie_id);
            if ($events_by_serie->count() > 1) {
                $purchase_types[] = PurchaseTypes::SERIE->value;
            }
            if($event->enabled_for_season_tickets){
                $purchase_types[] = PurchaseTypes::SEASON_TICKET->value;
                $payment_installments = PaymentInstallments::toArray();
            }

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
                'c_zone' => $c_zone,
                'a_zone' => $a_zone,
                'b_zone' => $b_zone,
                'e_zone' => $e_zone,
                'f_zone' => $f_zone,
                'h_zone' => $h_zone,
                'user_roles' => $user_roles,
                'global_payment_types' => $global_payment_types,
                'global_card_payment_types' => $global_card_payment_types,
                'purchase_types' => $purchase_types,
                'payment_installments' => $payment_installments
            ];

            return $reponse;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get event by id with only event
    */
    public function getOnlyEvent($id)
    {
        try {
            return $this->event_repository->getOnlyEvent($id);
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
            $seat_catalogue_ids = collect($data['seats'])->pluck('seat_catalogue_id')->toArray();

            if ($data['purchase_type'] == PurchaseTypes::SERIE->value) {
                return $this->handleSerieReservation($data, $seat_catalogue_ids);
            } else {
                return $this->handleSingleEventReservation($data, $seat_catalogue_ids);
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function handleSerieReservation($data, $seat_catalogue_ids)
    {
        $events_by_serie = $this->event_repository->getEventsBySerie($data['serie_id']);

        if ($events_by_serie->count() === 1) {
            throw new \Exception('No se puede realizar la compra de una serie de eventos con un solo evento');
        }

        foreach ($events_by_serie as $event) {
            $event_seats = EventSeatCatalog::where('event_id', $event->id)
                ->whereIn('seat_catalogue_id', $seat_catalogue_ids)
                ->with([
                    'seatCatalogue:id,code,zone',
                    'seatCatalogueStatus:id,name'
                ])
                ->get()
                ->keyBy('seat_catalogue_id');

            foreach ($data['seats'] as $seat) {
                $event_seat_catalogue = $event_seats->get($seat['seat_catalogue_id']);

                if (!$event_seat_catalogue || !in_array($event_seat_catalogue->seatCatalogueStatus->name, ['disponible', 'reservado'])) {
                    $seat_code = $event_seat_catalogue ? $event_seat_catalogue->seatCatalogue->code : $seat['seat_catalogue_id'];
                    throw new \Exception("El asiento {$seat_code} no está disponible para comprar en el evento {$event->name} del día {$event->start_date}");
                }
            }

            $this->event_repository->reserveSeatsToBuyBatch($event->id, $seat_catalogue_ids, $data['seller_user_id']);
        }

        return !$data['is_online'] ? $this->confirmSeatsPurchase($data) : true;
    }


    private function handleSingleEventReservation($data, $seat_catalogue_ids)
    {
        $event_seats = EventSeatCatalog::where('event_id', $data['event_id'])
            ->whereIn('seat_catalogue_id', $seat_catalogue_ids)
            ->with([
                'seatCatalogue:id,code,zone',
                'seatCatalogueStatus:id,name'
            ])
            ->get()
            ->keyBy('seat_catalogue_id');

        foreach ($data['seats'] as $seat) {
            $event_seat_catalogue = $event_seats->get($seat['seat_catalogue_id']);

            if (!$event_seat_catalogue || !in_array($event_seat_catalogue->seatCatalogueStatus->name, ['disponible', 'reservado'])) {
                $seat_code = $event_seat_catalogue ? $event_seat_catalogue->seatCatalogue->code : $seat['seat_catalogue_id'];
                throw new \Exception("El asiento {$seat_code} no está disponible para comprar");
            }
        }

        $this->event_repository->reserveSeatsToBuyBatch($data['event_id'], $seat_catalogue_ids, $data['seller_user_id']);

        return !$data['is_online'] ? $this->confirmSeatsPurchase($data) : true;
    }

    /*
    * |--------------------------------------------------------------------------
    * | print subscriber
    */
    public function printSubscriber(Int $id_ticket)
    {
            try {
                /*
                * Create sale ticket
                */
                $saleTicket = SaleTicket::find($id_ticket);

                $saleTicket->globalPaymentTypes->each(function ($global_payment_type) {

                    $global_payment_type->pivot->amount;

                    if ($global_payment_type->pivot->global_card_payment_type_id) {

                        $globalCardPaymentType = GlobalCardPaymentType::find($global_payment_type->pivot->global_card_payment_type_id);

                        if ($globalCardPaymentType) {
                            $global_payment_type->name .= " (".$globalCardPaymentType->name.")";
                        }
                    }
                });

                /**
                 * Load debtor and details
                 */
                $saleTicket->saleDebtor;
                $saleTicket->installmentPaymentHistories;

                if ($saleTicket->promotion) {
                    $saleTicket->promotion->loadMissing('promotionType');
                }

                $event = $saleTicket->events->first();

                $pdf_data = [];

                $saleTicket->EventSeatCatalogues->each(function ($data) use ($saleTicket, $event, &$pdf_data){

                    /**
                     * Load Promotion
                     */
                    if ($saleTicket->promotion) {
                        $data->load(['promotions' => function ($query) use ($saleTicket) {
                            $query->where('promotion_id', $saleTicket->promotion_id);
                        }]);
                    }

                    /**
                     * Load Original Price
                     */
                    $data->load(['priceTypes' => function ($query) {
                        $query->where('name', "abonado");
                    }]);

                     /*
                    * create qr
                    */
                    $builder = new Builder(
                        writer: new PngWriter(),
                        writerOptions: [],
                        validateResult: false,
                        data: $data->qr,
                        encoding: new Encoding('UTF-8'),
                        errorCorrectionLevel: ErrorCorrectionLevel::High,
                        size: 300,
                        margin: 10,
                        roundBlockSizeMode: RoundBlockSizeMode::Margin,
                        labelText: 'Asiento ' . $data->seatCatalogue->code,
                        labelFont: new OpenSans(20),
                        labelAlignment: LabelAlignment::Center
                    );

                    $result = $builder->build();

                    $qr_img = $result->getDataUri();

                    $data->seasonTicket;


                    $pdf_data[] = [
                        'event_name' => $event->name,
                        'event_start_date' => $event->start_date,
                        'seat_code' => $data->seatCatalogue->code,
                        'zone_type' => $data->seatCatalogue->zone,
                        'row' => $data->seatCatalogue->row,
                        'seat' => $data->seatCatalogue->seat,
                        'seat_type' => $data->seatCatalogue->seatType->name,
                        'percentage_cashback' => $data->seatCatalogue->seatType->percentage_cashback,
                        'qr_img' => $qr_img,
                        'qr' => $data->qr,
                        'final_price' => $data->price,
                        'ticket_id' => $saleTicket->id,
                        'seller_user' => $saleTicket->sellerUser,
                        'ticket_created_at' => $saleTicket->created_at,
                        'cash_register_type' => $saleTicket->cashRegister->cash_register_type_id,
                        'is_owner' => $data->seasonTicket->is_owner ?? false,
                        'description' => $data->seasonTicket->description ?? null,
                        'holder_name' => $data->seasonTicket->holder_name ?? null,
                        'holder_last_name' => $data->seasonTicket->holder_last_name ?? null,
                        'holder_middle_name' => $data->seasonTicket->holder_middle_name ?? null,
                        'holder_zip_code' => $data->seasonTicket->holder_zip_code ?? null,
                        'holder_phone' => $data->seasonTicket->holder_phone ?? null,
                        'holder_email' => $data->seasonTicket->holder_email ?? null,
                        'holder_jersey_type' => $data->seasonTicket->holder_jersey_type ?? null,
                        'holder_jersey_size' => $data->seasonTicket->holder_jersey_size ?? null,
                        'global_payment_types' => $saleTicket->globalPaymentTypes,
                        'payment_in_installments' => $saleTicket->payment_in_installments,
                        'promotion_ticket' => $saleTicket->promotion,
                        'promotion' => $event->promotions,
                        'original_price' => $data->priceTypes,
                        'sale_debtor' => $saleTicket->saleDebtor,
                        'installment_payment_histories'=>$saleTicket->installmentPaymentHistories,
                        'sale_ticket' =>$saleTicket
                    ];
                });

                return $pdf_data;

            } catch (\Exception $e) {
                throw $e;
            }
    }

    /*
    * |--------------------------------------------------------------------------
    * | print subscriber
    */
    public function printSubscriberInstallmentReceipt(Int $id)
    {
            try {
                /*
                * Create sale ticket
                */
                $saleTicket = SaleTicket::with([
                    'saleDebtor',
                    'installmentPaymentHistories.globalPaymentTypes',
                    'installmentPaymentHistories.cashRegister.sellerUserOpening',
                    'promotion.promotionType',
                    'EventSeatCatalogues.seatCatalogue.seatType',
                    'events'
                ])->find($id);

                $globalCardPaymentType = GlobalCardPaymentType::all();

                $saleTicket->installmentPaymentHistories->each(function ($installment_payment_history) use ($globalCardPaymentType){
                    $installment_payment_history->globalPaymentTypes->each(function ($global_payment_type) use ($globalCardPaymentType) {
                        if ($global_payment_type->pivot->global_card_payment_type_id) {
                            $globalCardPaymentType = $globalCardPaymentType->where('id', $global_payment_type->pivot->global_card_payment_type_id)->first();
                            if ($globalCardPaymentType) {
                                $global_payment_type->name .= " (".$globalCardPaymentType->name.")";
                            }
                        }
                    });
                });

                $saleTicket->setRelation('EventSeatCatalogues',
                    $saleTicket->EventSeatCatalogues
                        ->sortBy('created_at')
                        ->unique('seat_catalogue_id')
                        ->values()
                );

                $saleTicket->EventSeatCatalogues->each(function ($data) use ($saleTicket){
                    /**
                     * Load Promotion
                     */
                    if ($saleTicket->promotion) {
                        $data->load(['promotions' => function ($query) use ($saleTicket) {
                            $query->where('promotion_id', $saleTicket->promotion_id);
                        }]);
                    }

                    /**
                     * Load Original Price
                     */
                    $data->load(['priceTypes' => function ($query) {
                        $query->where('name', "abonado");
                    }]);

                    $data->seasonTicket;
                });

                $owner = $saleTicket->EventSeatCatalogues->filter(fn($item) => $item->seasonTicket->is_owner)->first();

                $total_amount_installment_payment_histories = $saleTicket->installmentPaymentHistories->sum('total_amount');

                return  [
                    'folio' => $saleTicket->id,
                    'sale_date' => Carbon::now()->format('d/m/Y'),
                    'status' => $saleTicket->total_amount > $total_amount_installment_payment_histories ? 'Pendiente' : 'Pagado',
                    'event_name' => $saleTicket->events->first()->name,
                    'owner' => $owner,
                    'seller_user' => $saleTicket->sellerUser,
                    'promotion_ticket' => $saleTicket->promotion,
                    'sale_debtor' => $saleTicket->saleDebtor,
                    'seats' => $saleTicket->EventSeatCatalogues,
                    'payment_in_installments' => $saleTicket->payment_in_installments,
                    'installment_payment_histories'=>$saleTicket->installmentPaymentHistories,
                    'total_amount' => $saleTicket->total_amount,
                    'total_amount_installment_payment_histories' => $total_amount_installment_payment_histories,
                    'remaining_total' => $saleTicket->total_amount - $total_amount_installment_payment_histories
                ];

            } catch (\Exception $e) {
                throw $e;
            }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get seat availablility by zone
    */
    public function getAvailability(array $data)
    {
        try {

            $event = $this->event_repository->getOnlyEvent($data['event_id']);

            $availability = $event->eventSeatCatalogues->filter(function ($item) {
                return $item->seatCatalogueStatus->name === 'disponible';
            })->groupBy(function ($item) {
                return $item->seatCatalogue->zone;
            })->map(function ($items, $zone) {
                return [
                    'zone' => $zone,
                    'available_seats' => $items->count()
                ];
            })->values()->toArray();

            return $availability;

        } catch(\Exception $e){
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
            * Handle sale debtor
            */
            $sale_debtor_id = null;
            if (isset($data['sale_debtor']) && isset($data['sale_debtor']['id'])) {
                $sale_debtor = $this->sale_debtor_service->getById($data['sale_debtor']['id']);
                if ($sale_debtor) {
                    if ($sale_debtor->first_name == 'nuevo' && $sale_debtor->last_name == 'deudor') {
                        if (!empty($data['sale_debtor']['first_name']) && !empty($data['sale_debtor']['last_name']) && !empty($data['sale_debtor']['phone_number'])) {
                            $new_sale_debtor = $this->sale_debtor_service->save($data['sale_debtor']);
                            $sale_debtor_id = $new_sale_debtor->id;
                        } else {
                            throw new \Exception('Debe ingresar los datos del nuevo deudor');
                        }
                    } else {
                        $sale_debtor_id = $data['sale_debtor']['id'];
                    }
                } else {
                    throw new \Exception('El deudor proporcionado no existe');
                }
            }

            /*
            * Create sale ticket
            */
            $status_id = $sale_debtor_id
                ? Cache::remember('sale_ticket_status_pendiente_id', 3600, fn() => SaleTicketStatus::where('name', 'pendiente')->value('id'))
                : Cache::remember('sale_ticket_status_pagado_id', 3600, fn() => SaleTicketStatus::where('name', 'pagado')->value('id'));

            $saleTicket = SaleTicket::create([
                'event_id' => $data['event_id'],
                'stadium_id' => $data['stadium_id'],
                'ticket_office_id' => $data['ticket_office_id'],
                'seller_user_id' => $data['seller_user_id'],
                'cash_register_id' => $data['cash_register_id'],
                'sale_ticket_status_id' => $status_id,
                'price_type_id' => null,
                'sale_debtor_id' => $sale_debtor_id,
                'amount_received' => $data['amount_received'],
                'total_amount' => $data['total_amount'],
                'total_returned' => $data['total_returned'],
                'payment_in_installments' => $data['payment_in_installments'] ?? null,
                'promotion_id' => $data['final_promotion']['id'] ?? null,
                'promotion_quantity' => $data['final_promotion']['quantity'] ?? null,
                'purchase_type' => $data['purchase_type'],
                'is_online' => $data['is_online'],
                'is_transfer' => $data['is_transfer'] ?? false,
            ]);


            /*
            * Create relationship between installment payment history service and sale ticket
            */
            $installment_payment_history_service = null;
            if($sale_debtor_id){
                $installment_payment_history_service = $this->installment_payment_history_service->save([
                    'sale_ticket_id' => $saleTicket->id,
                    'amount_received' => $data['amount_received'],
                    'total_amount' => $data['amount_received'] - $data['total_returned'],
                    'total_returned' => $data['total_returned'],
                    'is_active' => true,
                    'cash_register_id' => $data['cash_register_id']
                ]);
            }

            /*
            * Assign payment types to the sale ticket and installment payment history if is needed
            */
            $total_actual_paid = 0;
            $payment_data = [];
            $installment_payment_data = [];
            foreach ($data['global_payment_types'] as $global_payment_type) {
                $amount = $global_payment_type['amount'];
                $total_actual_paid += $amount;

                $payment_data[$global_payment_type['id']] = [
                    'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'] ?? null,
                    'reason_agreement_id' => $global_payment_type['reason_agreement_id'] ?? null,
                    'amount' => $amount,
                    'original_amount' => $amount,
                    'reason_courtesy' => $global_payment_type['reason_agreement'] ?? null,
                ];

                if($sale_debtor_id){
                    $installment_payment_data[$global_payment_type['id']] = [
                        'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'] ?? null,
                        'amount' => $amount,
                        'original_amount' => $amount,
                    ];
                }
            }

            $saleTicket->globalPaymentTypes()->attach($payment_data);
            if($sale_debtor_id){
                $installment_payment_history_service->globalPaymentTypes()->attach($installment_payment_data);
            }

            /*
            * Get events by serie if purchase type is serie
            */
            $events = ($data['purchase_type'] == PurchaseTypes::SERIE->value)
                ? $this->event_repository->getEventsBySerie($data['serie_id'])
                : collect([$this->event_repository->getOnlyById($data['event_id'])]);

            if ($events->count() === 1 && $data['purchase_type'] == PurchaseTypes::SERIE->value) {
                throw new \Exception('No se puede realizar la compra de una serie de eventos con un solo evento');
            }

            /*
            * Generate QR codes for seats if purchase type is serie
            */
            $seat_qrs = [];
            if ($data['purchase_type'] == PurchaseTypes::SERIE->value) {
                foreach ($data['seats'] as $seat) {
                    $seat_qrs[$seat['seat_catalogue']['code']] = 'qr_serie_' . $data['serie_id'] . '_asiento_' . $seat['seat_catalogue']['code'] . '_ticket_' . $saleTicket->id . '_key_' . uniqid();
                }
            }


            $pdf_data = [];
            /*
            * Assign seats to the sale ticket for each event
            */
            $globalCardPaymentTypes = GlobalCardPaymentType::all()->keyBy('id');

            // Preparar datos para inserción en lote
            $event_attachments = [];
            $seat_attachments = [];
            $seat_catalogue_ids = collect($data['seats'])->pluck('seat_catalogue_id')->toArray();

            // Procesar tipos de pago de tarjeta UNA SOLA VEZ
            $saleTicket->globalPaymentTypes->each(function ($global_payment_type) use ($globalCardPaymentTypes) {
                if ($global_payment_type->pivot->global_card_payment_type_id) {
                    $cardType = $globalCardPaymentTypes->get($global_payment_type->pivot->global_card_payment_type_id);
                    if ($cardType) {
                        $global_payment_type->name .= " ({$cardType->name})";
                    }
                }
            });

            // Cargar relaciones fuera del loop
            if ($saleTicket->promotion) {
                $saleTicket->promotion->loadMissing('promotionType');
            }

            // Cargar deudor e historiales UNA SOLA VEZ
            if (!$saleTicket->relationLoaded('saleDebtor')) {
                $saleTicket->load('saleDebtor');
            }
            if (!$saleTicket->relationLoaded('installmentPaymentHistories')) {
                $saleTicket->load('installmentPaymentHistories');
            }

            foreach ($events as $event) {
                // Preparar datos para attach en lote
                $event_attachments[$event->id] = [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Obtener asientos del evento con una sola consulta optimizada
                $event_seats = $event->eventSeatCatalogues
                    ->whereIn('seat_catalogue_id', $seat_catalogue_ids)
                    ->keyBy('seat_catalogue_id');

                foreach ($data['seats'] as $seat) {
                    $event_seat_catalogue = $event_seats->get($seat['seat_catalogue_id']);

                    if (!$event_seat_catalogue) {
                        throw new \Exception("Asiento {$seat['seat_catalogue_id']} no encontrado");
                    }

                    // Validar asiento
                    if ($event_seat_catalogue->seatCatalogueStatus->name !== 'transito') {
                        throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra en tránsito');
                    }

                    if ($event_seat_catalogue->user_id !== $data['seller_user_id']) {
                        throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra reservado para el mismo usuario');
                    }

                    // Generar QR
                    $qr = $data['purchase_type'] == PurchaseTypes::SERIE->value
                        ? $seat_qrs[$seat['seat_catalogue']['code']]
                        : 'qr_evento_' . $event->id . '_asiento_' . $seat['seat_catalogue']['code'] . '_ticket_' . $saleTicket->id . '_key_' . uniqid();

                    // Confirmar compra del asiento
                    $this->event_repository->confirmSeatsPurchase($event->id, $seat['seat_catalogue_id'], $data['member_user_id'], $saleTicket->id, $qr, $seat['final_price'], $seat['original_price'], $seat['is_gift'], $data['purchase_type']);

                    // Preparar datos para attach batch
                    $seat_attachments[] = [
                        'event_seat_catalog_id' => $event_seat_catalogue->id,
                        'user_id' => $data['member_user_id'],
                        'promotion_id' => $seat['promotion_id'],
                        'agreement_promotion_id' => $seat['agreement_promotion_id'] ?? null,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Cargar promociones si existen
                    if ($saleTicket->promotion) {
                        $event_seat_catalogue->load(['promotions' => function ($query) use ($saleTicket) {
                            $query->where('promotion_id', $saleTicket->promotion_id);
                        }]);
                    }

                    // Procesar abono
                    if($data['purchase_type'] == PurchaseTypes::SEASON_TICKET->value){
                        $seat['is_owner'] = $seat['is_owner'] == 'Si';
                        $season_ticket = $this->season_ticket_service->save($seat);
                        $event_seat_catalogue->update(['season_ticket_id' => $season_ticket->id]);
                    }

                    // Cargar precios originales
                    $event_seat_catalogue->load(['priceTypes' => function ($query) use ($data) {
                        $query->where('name', $data['purchase_type']);
                    }]);

                    // Generar QR imagen
                    $builder = new Builder(
                        writer: new PngWriter(),
                        writerOptions: [],
                        validateResult: false,
                        data: $qr,
                        encoding: new Encoding('UTF-8'),
                        errorCorrectionLevel: ErrorCorrectionLevel::High,
                        size: 300,
                        margin: 10,
                        roundBlockSizeMode: RoundBlockSizeMode::Margin,
                        labelText: 'Asiento ' . $event_seat_catalogue->seatCatalogue->code,
                        labelFont: new OpenSans(20),
                        labelAlignment: LabelAlignment::Center
                    );

                    $qr_img = $builder->build()->getDataUri();

                    // Estructura PDF
                    $pdf_data[] = [
                        'event_name' => $event->name,
                        'event_start_date' => $event->start_date,
                        'seat_code' => $event_seat_catalogue->seatCatalogue->code,
                        'zone_type' => $event_seat_catalogue->seatCatalogue->zone,
                        'row' => $event_seat_catalogue->seatCatalogue->row,
                        'seat' => $event_seat_catalogue->seatCatalogue->seat,
                        'seat_type' => $event_seat_catalogue->seatCatalogue->seatType->name,
                        'percentage_cashback' => $event_seat_catalogue->seatCatalogue->seatType->percentage_cashback,
                        'qr_img' => $qr_img,
                        'qr' => $qr,
                        'final_price' => $seat['final_price'],
                        'ticket_id' => $saleTicket->id,
                        'seller_user' => $saleTicket->sellerUser,
                        'ticket_created_at' => $saleTicket->created_at,
                        'cash_register_type' => $cash_register->cash_register_type_id,
                        'is_owner' => $seat['is_owner'] ?? false,
                        'description' => $seat['description'] ?? null,
                        'holder_name' => $seat['holder_name'] ?? null,
                        'holder_last_name' => $seat['holder_last_name'] ?? null,
                        'holder_middle_name' => $seat['holder_middle_name'] ?? null,
                        'holder_zip_code' => $seat['holder_zip_code'] ?? null,
                        'holder_phone' => $seat['holder_phone'] ?? null,
                        'holder_email' => $seat['holder_email'] ?? null,
                        'holder_jersey_type' => $seat['holder_jersey_type'] ?? null,
                        'holder_jersey_size' => $seat['holder_jersey_size'] ?? null,
                        'global_payment_types' => $saleTicket->globalPaymentTypes,
                        'payment_in_installments' => $saleTicket->payment_in_installments,
                        'promotion_ticket' => $saleTicket->promotion,
                        'promotion' => $event_seat_catalogue->promotions,
                        'original_price' => $event_seat_catalogue->priceTypes,
                        'sale_debtor' => $saleTicket->saleDebtor,
                        'installment_payment_histories' => $saleTicket->installmentPaymentHistories
                    ];
                }
            }

            // Insertar todas las relaciones en lote (UNA SOLA CONSULTA CADA UNA)
            $saleTicket->events()->attach($event_attachments);

            // Insertar todas las relaciones de asientos en lote
            foreach ($seat_attachments as $attachment) {
                $saleTicket->eventSeatCatalogs()->attach($attachment['event_seat_catalog_id'], [
                    'user_id' => $attachment['user_id'],
                    'promotion_id' => $attachment['promotion_id'],
                    'agreement_promotion_id' => $attachment['agreement_promotion_id'],
                    'is_active' => $attachment['is_active'],
                    'created_at' => $attachment['created_at'],
                    'updated_at' => $attachment['updated_at']
                ]);
            }
            /*
            * Create cash register movement
            */
           $movement_type_id = Cache::remember('cash_register_movement_type_venta_id', 3600, fn() => CashRegisterMovementType::where('name', 'venta')->value('id'));
            $cash_register_movement = CashRegisterMovement::create([
                'cash_register_id' => $data['cash_register_id'],
                'cash_register_movement_type_id' => $movement_type_id,
                'sale_ticket_id' => $saleTicket->id,
                'previous_balance' => $cash_register->current_balance,
                'movement_amount' => $total_actual_paid,
                'new_balance' => $cash_register->current_balance + $total_actual_paid,
            ]);

            /*
            * Update cash register balance
            */
            $cash_register->update(['current_balance' => $cash_register_movement->new_balance]);


            if(!$data['is_online']){
                return $pdf_data;
            }

            $data['sale_ticket_id'] = $saleTicket->id;
            $this->online_payment_transaction_service->save($data);

            return true;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | Print sale tciket
    */
    public function printSaleTicket($sale_ticket_id)
    {
        try {
            $sale_ticket = SaleTicket::find($sale_ticket_id);
            $cash_register_type = $sale_ticket->cashRegister->cash_register_type_id;
            $event_seat_catalogues = $sale_ticket->EventSeatCatalogues;
            if ($sale_ticket->promotion) {
                $sale_ticket->promotion->loadMissing('promotionType');
            }
            $pdf_data = [];

            $event_seat_catalogues->each(function($event_seat_catalogue) use (&$sale_ticket, &$cash_register_type, &$pdf_data) {
                $qr = $event_seat_catalogue->qr;

                /*
                * create qr
                */
                $builder = new Builder(
                    writer: new PngWriter(),
                    writerOptions: [],
                    validateResult: false,
                    data: $qr,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    labelText: 'Asiento ' . $event_seat_catalogue->seatCatalogue->code,
                    labelFont: new OpenSans(20),
                    labelAlignment: LabelAlignment::Center
                );

                $result = $builder->build();

                $qr_img = $result->getDataUri();

                /*
                * pdf structure
                */
                $pdf_data[] = [
                    'event_name' => $event_seat_catalogue->event->name,
                    'event_start_date' => $event_seat_catalogue->event->start_date,
                    'seat_code' => $event_seat_catalogue->seatCatalogue->code,
                    'seller_user' => $sale_ticket->sellerUser,
                    'qr_img' => $qr_img,
                    'qr' => $event_seat_catalogue->qr,
                    'final_price' => $event_seat_catalogue->price,
                    'ticket_id' => $sale_ticket->id,
                    'ticket_created_at' => $sale_ticket->created_at,
                    'cash_register_type' => $cash_register_type,
                    'promotion_ticket' => $sale_ticket->promotion
                ];

            });

            return $pdf_data;

        } catch(\Exception $e) {
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

    public function getUsersEventForSaleTickets($id)
    {
        try {
            $event = $this->event_repository->getUsersEventForSaleTickets($id);
            return $event;
        } catch (\Exception $e) {

            throw $e;
        }
    }

}
