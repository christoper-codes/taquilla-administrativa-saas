<?php

namespace App\Http\Controllers;

use App\Models\SaleTicket;
use App\Services\SaleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleTicketController extends Controller
{
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
    public function show(SaleTicket $saleTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleTicket $saleTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleTicket $saleTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleTicket $saleTicket)
    {
        //
    }

    /**
     * Cancellation of sale tickets
     */
    public function cancellationSaleTickets(Request $request)
    {
        DB::beginTransaction();
        try {

            $response = $this->sale_ticket_service->cancellationSaleTickets($request->all());

            DB::commit();

            return redirect()->back()->with('success', $response);

        } catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());

        }
    }

    /**
     * Sale tickets per week in a month
     */
    public function saleTicketsPerWeekInMonth(Request $request)
    {
        try {

            $response = $this->sale_ticket_service->saleTicketsPerWeekInMonth($request->all());

            return response()->json([
                'data' => $response,
                'message' => 'Sale tickets per week in a month',
                'success' => true
            ], 200);


        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function getSaleTicketStatusPending (Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
            ]);

            $response = $this->sale_ticket_service->pendingSaleTickets($request->get('id'));

            return response()->json([
                'data' => $response,
                'message' => 'Tickets pendientes consultados con exito',
                'success' => true,
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function getTicketsWithInstallmentPaymentsCompleted(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
            ]);

            $response = $this->sale_ticket_service->ticketsWithInstallmentPaymentsCompleted($request->get('id'));

            return response()->json([
                'data' => $response,
                'message' => 'Tickets pagados consultados con exito',
                'success' => true,
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
