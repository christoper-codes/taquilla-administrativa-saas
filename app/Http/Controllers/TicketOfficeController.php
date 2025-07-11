<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\EventSeatCatalog;
use App\Models\EventSeatCatalogUser;
use App\Models\TicketOffice;
use App\Models\User;
use App\Services\CashRegisterService;
use App\Services\EventService;
use App\Models\SalesTicketCancellationCode;
use App\Models\SaleTicket;
use App\Services\GlobalCardPaymentTypeService;
use App\Services\GlobalPaymentTypeService;
use App\Services\TicketOfficeService;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketOfficeController extends Controller
{

    protected $ticket_office_service;
    protected $event_service;
    protected $cash_register_service;
    protected $global_payment_type_service;
    protected $global_card_payment_type_service;

    public function __construct(TicketOfficeService $ticket_office_service, EventService $event_service, CashRegisterService $cash_register_service, GlobalPaymentTypeService $global_payment_type_service,
                                GlobalCardPaymentTypeService $global_card_payment_type_service)
    {
        $this->ticket_office_service = $ticket_office_service;
        $this->event_service = $event_service;
        $this->cash_register_service = $cash_register_service;
        $this->global_payment_type_service = $global_payment_type_service;
        $this->global_card_payment_type_service = $global_card_payment_type_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewVendorTopics', Auth::user());

        try {
            $ticket_offices = $this->ticket_office_service->getAll();

            return Inertia::render('App/Pos/TicketOffices', [
                'ticket_offices' => $ticket_offices,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar las taquillas');
        }
    }

    public function check()
    {
        try {

            return Inertia::render('App/Pos/CheckTickets');

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar');
        }
    }

    public function change(Request $request)
    {
        try {
            //Transferencia de tickets

            $list_ticket = $request->get('ticketListOfSender');
            $sender_user = $request->get('senderUserName');
            $receiver_user = $request->get('receiverUserName');


            foreach ($list_ticket as $key) {
                $event_seat_catalog = EventSeatCatalog::find($key);

                if ($event_seat_catalog) {
                    $event_seat_catalog->user_id = $receiver_user;
                    $event_seat_catalog->save();

                    $event_seat_catalog_user = EventSeatCatalogUser::create([
                        'event_seat_catalog_id' => $key,
                        'sender_user_id' => $sender_user,
                        'receiver_user_id' => $receiver_user
                    ]);
                }

            }

            //Lista de tickets y eventos, usuarios

            $user = Auth::user()->load('globalImages', 'userRoles');
            $users = User::all();

            $tickets = $user->EventSeatCatalogues()
                ->with('event', 'seatCatalogue', 'seatCatalogueStatus')
                ->whereHas('seatCatalogueStatus', function ($query) {
                    $query->where('name', 'vendido');
                })
                ->get();
            $events = $this->event_service->getAll();
            $eventsWithTickets = [];

            foreach ($events as $event) {
                $eventsWithTickets[$event->id] = [
                    'event' => $event,
                    'tickets' => $tickets->filter(function($ticket) use ($event) {
                        return $ticket->event_id == $event->id;
                    })->values()
                ];
            }

            return  WebResponseHelper::sendResponse($eventsWithTickets, "Transferencia con éxito", null, false);


        } catch (\Throwable $th) {
            WebResponseHelper::rollback($th, 'Opps! Algo salió mal al cargar');
        }
    }

    public function share()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');
            $users = User::all();

            $tickets = $user->EventSeatCatalogues()
                ->with('event', 'seatCatalogue', 'seatCatalogueStatus')
                ->whereHas('seatCatalogueStatus', function ($query) {
                    $query->where('name', 'vendido');
                })
                ->get();
            $events = $this->event_service->getAll();
            $eventsWithTickets = [];

            foreach ($events as $event) {
                $eventsWithTickets[$event->id] = [
                    'event' => $event,
                    'tickets' => $tickets->filter(function($ticket) use ($event) {
                        return $ticket->event_id == $event->id;
                    })->values()
                ];
            }

            return Inertia::render('App/Pos/ShareTickets', [
                'user' => $user,
                'users' => $users,
                'events_with_tickets' => $eventsWithTickets,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function search()
    {

        //Gate::authorize('viewVendorTopics', Auth::user());

        $events = $this->event_service->getAll();
        $users = User::all();
        $salesTicketCancellationCode = SalesTicketCancellationCode::findOrFail(1);

        return Inertia::render('App/Pos/SearchTickets', [
            'events' => $events,
            'users' => $users,
            'salesTicketCancellationCode' => $salesTicketCancellationCode
        ]);

    }

    public function searchWithEvent($idEvent)
    {
        try {

            $event = $this->event_service->getUsersEventForSaleTickets($idEvent);

            return response()->json(['datos' => $event], 200);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los usuarios');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketOffice $ticketOffice)
    {
        Gate::authorize('viewVendorTopics', Auth::user());

        try {

            $ticket_office = $this->ticket_office_service->getById($ticketOffice->id);
            $sale_tickets_cancellation_code = $ticket_office->saleTicketCancellationCodes()->where('is_active', true)->first();
            $events = $this->event_service->getAll();
            $auth_user = Auth::user();
            $active_cash_register = $auth_user->cashRegisterActive($ticketOffice->id);
            $cash_register_general_history = [];
            if($active_cash_register){
                $cash_register_general_history = $this->cash_register_service->getCashRegisterGeneralHistory($active_cash_register->id);
            }

            $global_payment_types = $this->global_payment_type_service->getAll()->whereNotIn('name', ['cortesia', 'plazos']);
            $global_card_payment_types = $this->global_card_payment_type_service->getAll();

            return Inertia::render('App/Pos/TicketOffice', [
                'ticket_office' => $ticket_office,
                'events' => $events,
                'auth_user' => $auth_user,
                'active_cash_register' => $active_cash_register,
                'cash_register_general_history' => $cash_register_general_history,
                'sale_tickets_cancellation_code' => $sale_tickets_cancellation_code,
                'global_payment_types' => $global_payment_types,
                'global_card_payment_types' => $global_card_payment_types
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar la taquilla');
        }
    }

    public function ticketDetails($id)
    {
        try {
            $ticket_details = $this->cash_register_service->getSaleTicketDetailHistory($id);

            return response()->json([
                'data' => $ticket_details,
                'message' => 'Detalles del ticket obtenidos correctamente',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketOffice $ticketOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketOffice $ticketOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketOffice $ticketOffice)
    {
        //
    }
}
