<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPaymentHistory;
use App\Services\CashRegisterService;
use App\Services\EventService;
use App\Services\InstallmentPaymentHistoryService;
use App\Services\SaleTicketService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentPaymentHistoryController extends Controller
{
    protected $installment_payment_history_service;
    protected $event_service;
    protected $sale_ticket_service;
    protected $cash_register_service;

    public function __construct(InstallmentPaymentHistoryService $installment_payment_history_service, EventService $event_service, SaleTicketService $sale_ticket_service, CashRegisterService $cash_register_service)
    {
        $this->installment_payment_history_service = $installment_payment_history_service;
        $this->event_service = $event_service;
        $this->sale_ticket_service = $sale_ticket_service;
        $this->cash_register_service = $cash_register_service;
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
        try {
            $request->validate([
                'sale_ticket_id' => 'required',
                'cash_register_id' => 'required',
                'amount_received' => 'required',
                'total_amount' => 'required',
                'total_returned' => 'required',
                'global_payment_type_sale_ticket' => 'required|array',
            ]);


            DB::beginTransaction();

            $request->merge([ 'is_active' => true ]);

            $installment_payment_history_service = $this->installment_payment_history_service->save($request->only([
                'sale_ticket_id',
                'cash_register_id',
                'amount_received',
                'total_amount',
                'total_returned',
                'is_active'
            ]));

            foreach ($request->get('global_payment_type_sale_ticket') as $global_payment_type) {
                $installment_payment_history_service->globalPaymentTypes()->attach($global_payment_type['global_payment_type_id'], [
                    'global_card_payment_type_id' => $global_payment_type['global_card_payment_type_id'],
                    'amount' => $global_payment_type['amount'],
                    'original_amount' => $global_payment_type['amount'],
                ]);
            }

            $request->merge([ 'cash_register_movement_type' => 'pago parcial de una venta' ]);

            $this->cash_register_service->updateCashRegister($request->only([
                'cash_register_id',
                'cash_register_movement_type',
                'sale_ticket_id',
                'total_amount'
            ]));

            $this->sale_ticket_service->updatedSaleTicketStatusForInstallmentPaymentHistories($request->get('sale_ticket_id'));

            DB::commit();

            return $this->printSubscriberInstallmentReceipt($request,$request->get('sale_ticket_id'));

        } catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InstallmentPaymentHistory $installmentPaymentHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstallmentPaymentHistory $installmentPaymentHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstallmentPaymentHistory $installmentPaymentHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstallmentPaymentHistory $installmentPaymentHistory)
    {
        //
    }

    public function printSubscriberInstallmentReceipt(Request $request, $id)
    {
        try {

            $response = $this->event_service->printSubscriberInstallmentReceipt($id);

            $pdf_response = Pdf::loadView('pdfs.hdx.subscriberInstallmentReceipt', [
                'data' => $response
            ]);

            $pdfContent = $pdf_response->output();

            return response()->json([
                'data' => $response,
                'message' => 'Asientos reservados y comprados correctamente',
                'success' => true,
                'pdf' => base64_encode($pdfContent)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al reservar los asientos',
                'success' => false
            ], 500);
        }
    }
}
