<?php

namespace App\Services;

use App\Enums\PaymentInstallments;
use App\Interfaces\EventRepositoryInterface;
use App\Models\CashRegisterMovement;
use App\Models\CashRegisterMovementType;
use App\Models\EventSeatCatalogPriceType;
use App\Models\PriceTypeSeatCatalogue;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
use App\Models\GlobalCardPaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;

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

    public function __construct(EventRepositoryInterface $event_repository, GlobalPaymentTypeService $global_payment_type_service,
                    GlobalCardPaymentTypeService $global_card_payment_type_service, CashRegisterService $cash_register_service,
                    SeasonTicketService $season_ticket_service, SaleDebtorService $sale_debtor_service,
                    InstallmentPaymentHistoryService $installment_payment_history_service )
    {
        $this->event_repository = $event_repository;
        $this->global_payment_type_service = $global_payment_type_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
        $this->cash_register_service = $cash_register_service;
        $this->season_ticket_service = $season_ticket_service;
        $this->sale_debtor_service = $sale_debtor_service;
        $this->installment_payment_history_service = $installment_payment_history_service;
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
            $purchase_types = ['partido'];
            $events_by_serie = $this->event_repository->getEventsBySerie($event->serie_id);
            if ($events_by_serie->count() > 1) {
                $purchase_types[] = 'serie';
            }
            if($event->enabled_for_season_tickets){
                $purchase_types[] = 'abonado';
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
    * | Reserve seats to buy
    */
    public function reserveSeatsToBuy($data)
    {
        try {

            /*
            * Verify if the purchase types is 'partido' or 'serie'
            */
            if($data['purchase_type'] === 'serie') {
                $events_by_serie = $this->event_repository->getEventsBySerie($data['serie_id']);
                if ($events_by_serie->count() === 1) {
                    throw new \Exception('No se puede realizar la compra de una serie de eventos con un solo evento');
                }

                foreach ($events_by_serie as $event) {
                    foreach ($data['seats'] as $seat) {
                        /*
                        * Verificar si el asiento está disponible para comprar
                        */
                        $event_seat_catalogue = $event->eventSeatCatalogues->where('seat_catalogue_id', $seat['seat_catalogue_id'])->first();
                        if (!in_array($event_seat_catalogue->seatCatalogueStatus->name, ['disponible', 'reservado'])) {
                            throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar en el evento ' . $event->name . ' del dia ' . $event->start_date . ' ya que esta comprado o reservado');
                        }

                        /*
                        * Reservar asiento para comprar
                        */
                        $this->event_repository->reserveSeatsToBuy($event->id, $seat['seat_catalogue_id'], $data['seller_user_id']);
                    }
                }
            }  else {
                foreach ($data['seats'] as $seat) {
                    /*
                    * Verificar si el asiento está disponible para comprar
                    */
                    $event_seat_catalogue = $this->event_repository->getById($data['event_id'])->eventSeatCatalogues->where('seat_catalogue_id', $seat['seat_catalogue_id'])->first();
                    if (!in_array($event_seat_catalogue->seatCatalogueStatus->name, ['disponible', 'reservado'])) {
                        throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar');
                    }

                    /*
                    * Reservar asiento para comprar
                    */
                    $this->event_repository->reserveSeatsToBuy($data['event_id'], $seat['seat_catalogue_id'], $data['seller_user_id']);
                }
            }

            if(!$data['is_online']){
                $pdf_data = $this->confirmSeatsPurchase($data);

                return $pdf_data;
            }

            return true;

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
            $saleTicket = new SaleTicket();
            $saleTicket->stadium_id = $data['stadium_id'];
            $saleTicket->ticket_office_id = $data['ticket_office_id'];
            $saleTicket->seller_user_id = $data['seller_user_id'];
            $saleTicket->cash_register_id = $data['cash_register_id'];
            $saleTicket->sale_ticket_status_id = $sale_debtor_id ? SaleTicketStatus::where('name', 'pendiente')->first()->id : SaleTicketStatus::where('name', 'pagado')->first()->id;
            $saleTicket->price_type_id = null;
            $saleTicket->sale_debtor_id = $sale_debtor_id ?? null;
            $saleTicket->amount_received = $data['amount_received'];
            $saleTicket->total_amount = $data['total_amount'];
            $saleTicket->total_returned = $data['total_returned'];
            $saleTicket->payment_in_installments = $data['payment_in_installments'] ?? null;
            $saleTicket->promotion_id = $data['final_promotion']['id'] ?? null;
            $saleTicket->promotion_quantity = $data['final_promotion']['quantity'] ?? null;
            $saleTicket->is_online = $data['is_online'];
            $saleTicket->is_transfer = $data['is_transfer'] ?? false;
            $saleTicket->save();

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
                ]);
            }

            /*
            * Assign payment types to the sale ticket and installment payment history if is needed
            */
            $total_actual_paid = 0;
            foreach ($data['global_payment_types'] as $global_payment_type) {
                $saleTicket->globalPaymentTypes()->attach($global_payment_type['id'], [
                    'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'] ?? null,
                    'reason_agreement_id' => $global_payment_type['reason_agreement_id'] ?? null,
                    'amount' => $global_payment_type['amount'],
                    'original_amount' => $global_payment_type['amount'],
                    'reason_courtesy' => $global_payment_type['reason_agreement'] ?? null,
                ]);
                $total_actual_paid += $global_payment_type['amount'];
                if($sale_debtor_id){
                    $installment_payment_history_service->globalPaymentTypes()->attach($global_payment_type['id'], [
                        'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'] ?? null,
                        'amount' => $global_payment_type['amount'],
                        'original_amount' => $global_payment_type['amount'],
                    ]);
                }
            }

            /*
            * Get events by serie if purchase type is serie
            */
            $events = ($data['purchase_type'] === 'serie')
                ? $this->event_repository->getEventsBySerie($data['serie_id'])
                : collect([$this->event_repository->getById($data['event_id'])]);

            if ($events->count() === 1 && $data['purchase_type'] === 'serie') {
                throw new \Exception('No se puede realizar la compra de una serie de eventos con un solo evento');
            }

            /*
            * Generate QR codes for seats if purchase type is serie
            */
            $seat_qrs = [];
            if ($data['purchase_type'] === 'serie') {
                foreach ($data['seats'] as $seat) {
                    $seat_qrs[$seat['seat_catalogue']['code']] = 'qr_serie_' . $data['serie_id'] . '_asiento_' . $seat['seat_catalogue']['code'] . '_ticket_' . $saleTicket->id . '_key_' . uniqid();
                }
            }


            $event_seat_catalogues = [];
            $pdf_data = [];

            /*
            * Assign seats to the sale ticket for each event
            */
            foreach ($events as $event) {
                /*
                * RelationShip between sale ticket and event
                */
                $saleTicket->events()->attach($event->id, [
                    'is_active' => true,
                ]);

                /**
                 * Add Card Payment Type
                 */
                $saleTicket->globalPaymentTypes->each(function ($global_payment_type) {

                    $global_payment_type->pivot->amount;

                    if ($global_payment_type->pivot->global_card_payment_type_id) {

                        $globalCardPaymentType = GlobalCardPaymentType::find($global_payment_type->pivot->global_card_payment_type_id);

                        if ($globalCardPaymentType) {
                            $global_payment_type->name .= " (".$globalCardPaymentType->name.")";
                        }
                    }
                });

                foreach ($data['seats'] as $seat) {
                    $qr = $data['purchase_type'] === 'serie' ? $seat_qrs[$seat['seat_catalogue']['code']] : 'qr_evento_' . $event->id . '_asiento_' . $seat['seat_catalogue']['code'] . '_ticket_' . $saleTicket->id . '_key_' . uniqid();

                    /*
                    * Verify if the seat is available to buy
                    */
                    $event_seat_catalogue = $event->eventSeatCatalogues->where('seat_catalogue_id', $seat['seat_catalogue_id'])->first();
                    $event_seat_catalogues[] = $event_seat_catalogue;

                    if ($event_seat_catalogue->seatCatalogueStatus->name !== 'transito') {
                        throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra en tránsito');
                    }

                    if ($event_seat_catalogue->user_id !== $data['seller_user_id']) {
                        throw new \Exception('El asiento ' . $event_seat_catalogue->seatCatalogue->code . ' no está disponible para comprar ya que no se encuentra reservado para el mismo usuario');
                    }

                    /*
                    * Confirm seat purchase
                    */
                    $this->event_repository->confirmSeatsPurchase($event->id, $seat['seat_catalogue_id'], $data['member_user_id'], $saleTicket->id, $qr, $seat['final_price'], $seat['is_gift'], $data['purchase_type']);

                    /*
                    * Create relationship between sale ticket and eventSeatCatalogs
                    */
                    $saleTicket->eventSeatCatalogs()->attach($event_seat_catalogue->id, [
                        'user_id' => $data['member_user_id'],
                        'promotion_id' => $seat['promotion_id'],
                        'agreement_promotion_id' => $seat['agreement_promotion_id'] ?? null,
                        'is_active' => true,
                    ]);

                    /*
                    * Validate if the sale is abonado
                    */
                    if($data['purchase_type'] === 'abonado'){
                        $seat['is_owner'] = $seat['is_owner'] == 'Si' ? true : false;
                        $this->season_ticket_service->save($seat);
                    }

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
                        'payment_in_installments' => $saleTicket->payment_in_installments
                    ];
                }
            }

            /*
            * Create cash register movement
            */
            $cash_register_movement = new CashRegisterMovement();
            $cash_register_movement->cash_register_id = $data['cash_register_id'];
            $cash_register_movement->cash_register_movement_type_id = CashRegisterMovementType::where('name', 'venta')->first()->id;
            $cash_register_movement->sale_ticket_id = $saleTicket->id;
            $cash_register_movement->previous_balance = $cash_register->current_balance;
            $cash_register_movement->movement_amount = $total_actual_paid;
            $cash_register_movement->new_balance = $cash_register->current_balance + $total_actual_paid;
            $cash_register_movement->save();

            /*
            * Update cash register balance
            */
            $cash_register->current_balance = $cash_register_movement->new_balance;
            $cash_register->save();

            if(!$data['is_online']){
                return $pdf_data;
            }

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
                    'cash_register_type' => $cash_register_type
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
