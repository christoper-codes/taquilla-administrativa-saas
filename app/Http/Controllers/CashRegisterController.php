<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\CashRegister;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class CashRegisterController extends Controller
{

    protected $cash_register_service;

    public function __construct(CashRegisterService $cash_register_service)
    {
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
        $request->validate([
            'ticket_office_id' => 'required',
            'cash_register_type_id' => 'required',
            'seller_user_opening_id' => 'required',
            'description' => 'nullable',
            'is_open' => 'nullable',
            'confirmed_closure' => 'nullable',
            'batch_cash_register' => 'nullable',
            'batch_code' => 'nullable',
            'opening_balance' => 'required',
            'current_balance' => 'nullble',
            'closing_balance' => 'nullable',
        ]);

        DB::beginTransaction();
        try {

            $cash_register = $this->cash_register_service->openCashRegister($request->all());

            DB::commit();

            WebResponseHelper::sendResponse($cash_register, 'Caja registradora abierta correctamente', null, false);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al abrir la caja registradora');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CashRegister $cashRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashRegister $cashRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashRegister $cashRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashRegister $cashRegister)
    {
        //
    }

    public function closeCashRegister(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'cash_register_id' => 'required',
                'seller_user_closing_id' => 'required',
                'ticket_office_id' => 'required'
            ]);

            $response = $this->cash_register_service->closeCashRegister($request->all());

           /*  return response()->json([
                'data' => $response,
                'message' => 'success',
                'success' => false,
            ], 200); */

            $pdf_response = Pdf::loadView('pdfs.hdx.closeCashRegister', ['pdf_data' => $response]);
            $pdfContent = $pdf_response->output();

           DB::commit();

            return response()->json([
                'data' => $response,
                'message' => 'Caja registradora cerrada con exito',
                'success' => true,
                'pdf' => base64_encode($pdfContent)
            ], 200);

        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
