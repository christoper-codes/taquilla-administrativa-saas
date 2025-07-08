<?php

namespace App\Http\Controllers;

use App\Exports\SalesTicketDebtorExport;
use App\Models\SaleTicket;
use App\Services\SaleTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SaleTicketController extends Controller
{
    protected $sale_ticket_service;

    public function __construct(SaleTicketService $sale_ticket_service)
    {
        $this->sale_ticket_service = $sale_ticket_service;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:sale_tickets,id',
            ]);

            $response = $this->sale_ticket_service->getSaleTicketById($saleTicket->id);

            return response()->json([
                'data' => $response,
                'message' => 'Sale ticket retrieved successfully',
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

    public function saleTicketStatusPendingDebtor (Request $request)
    {
        try {

            $request->validate([
                'stadium_id' => 'required',
            ]);

            $response = $this->sale_ticket_service->saleTicketStatusPendingDebtor($request->get('stadium_id'));

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

    public function exportSaleTicketStatusPendingDebtor($stadium_id)
    {
        try {

            $response = $this->sale_ticket_service->saleTicketStatusPendingDebtor($stadium_id);

            return Excel::download(new SalesTicketDebtorExport($response), 'tickets_pendientes.xlsx');

        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function saleTicketStatusPaidDebtor(Request $request)
    {
        try {

            $request->validate([
                'stadium_id' => 'required',
            ]);

            $response = $this->sale_ticket_service->saleTicketStatusPaidDebtor($request->get('stadium_id'));

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

    public function exportSaleTicketStatusPaidDebtor($stadium_id)
    {
        try {

            $response = $this->sale_ticket_service->saleTicketStatusPaidDebtor($stadium_id);

            return Excel::download(new SalesTicketDebtorExport($response), 'tickets_pagados.xlsx');

        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
