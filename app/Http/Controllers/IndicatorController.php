<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\Event;
use App\Services\EventService;
use App\Services\SaleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IndicatorController extends Controller
{

    /*
    * Define variables for services
    */
    protected $sale_ticket_service;
    protected $event_service;

    public function __construct(SaleTicketService $sale_ticket_service, EventService $event_service)
    {
        $this->event_service = $event_service;
        $this->sale_ticket_service = $sale_ticket_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user()->load('globalImages', 'userRoles');
            $events = $this->event_service->getAll();

            return Inertia::render('App/Administration/Indicators/Index', [
               'user' => $user,
               'events' => $events
            ]);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los indicadores');
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

    /**
     * Display the specified resource.
     */
    public function show($slug, $id)
    {
        try {
            $user = Auth::user()->load('globalImages', 'userRoles');
            $data = ['event_id' => $id];

            $history_per_event = $this->sale_ticket_service->getHistoryPerEvent($data);

            return Inertia::render('App/Administration/Indicators/Show', [
                'user' => $user,
                'historyPerEvent' => $history_per_event
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los indicadores');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get all events with traffic
     */
    public function getAllEventsWithTraffic()
    {
        try {
            $user = Auth::user()->load('globalImages', 'userRoles');
            $events = $this->event_service->getAllWithTraffic();

            return Inertia::render('App/Administration/Indicators/Release', [
               'user' => $user,
               'events' => $events
            ]);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los indicadores');
        }
    }

    /**
     * Release reserved seats for an event
     */
    public function releaseReservedSeats(int $event_id)
    {
        DB::beginTransaction();
        try {
            $this->event_service->releaseReservedSeats($event_id);
            $events = $this->event_service->getAllWithTraffic();
            DB::commit();

            return response()->json([
                    'data' => $events,
                    'message' => 'Asientos reservados liberados correctamente',
                    'success' => true,
                ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al liberar los asientos reservados',
                'success' => false
            ], 500);
        }
    }
}
