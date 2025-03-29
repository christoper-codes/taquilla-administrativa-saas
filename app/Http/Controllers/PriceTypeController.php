<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\PriceType;
use App\Services\PriceTypeService;
use Illuminate\Http\Request;

class PriceTypeController extends Controller
{

    protected $priceType_service;

    public function __construct(PriceTypeService $priceType_service) {
        $this->priceType_service = $priceType_service;
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
    public function show(PriceType $priceType)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        try {

            $prices_type = $this->priceType_service->getAll();

            return response()->json($prices_type, 200);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los tipos de precio');
      }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceType $priceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceType $priceType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceType $priceType)
    {
        //
    }
}
