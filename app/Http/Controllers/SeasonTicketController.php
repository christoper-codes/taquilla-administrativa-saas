<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\SeasonTicket;
use App\Services\SeasonTicketService;
use Illuminate\Http\Request;

class SeasonTicketController extends Controller
{
    protected $season_ticket_service;

    public function __construct(SeasonTicketService $season_ticket_service)
    {
        $this->season_ticket_service = $season_ticket_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(SeasonTicket $seasonTicket)
    {
        //
    }

    /**
     * Display the list resource.
     */
    public function showTicketsBySeasonId(int $id)
    {
        try {

            $season_tickets = $this->season_ticket_service->getBySeason($id);

            return response()->json($season_tickets, 200);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los tickets por temporada');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeasonTicket $seasonTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeasonTicket $seasonTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeasonTicket $seasonTicket)
    {
        //
    }
}
