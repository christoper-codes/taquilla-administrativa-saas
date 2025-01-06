<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\SaleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IndicatorController extends Controller
{

    /*
    * Define variables for services
    */
    protected $sale_ticket_service;

    public function __construct(SaleTicketService $sale_ticket_service)
    {
        $this->sale_ticket_service = $sale_ticket_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user()->load('globalImages');

            $data = ['stadium_id' => 1];

            $sale_ticket_per_week = $this->sale_ticket_service->saleTicketsPerWeekInMonth($data);

            return Inertia::render('App/Administration/Indicators/Index', [
               'user' => $user,
               'saleTicketsPerWeek' => $sale_ticket_per_week
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
    public function show(string $id)
    {
        //
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
}
