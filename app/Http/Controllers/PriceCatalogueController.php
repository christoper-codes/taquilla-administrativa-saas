<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\PriceCatalogue;
use App\Services\PriceCatalogService;
use Illuminate\Http\Request;

class PriceCatalogueController extends Controller
{

    protected $price_catalog_service;

    public function __construct(PriceCatalogService $price_catalog_service) {
        $this->price_catalog_service = $price_catalog_service;
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
     * Display a listing of the resource.
     */
    public function firstOrCreate(Request $request)
    {
        try {

            $price = $this->price_catalog_service->firstOrCreate($request->only(['price', 'stadium_id']));

            return response()->json($price, 200);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar el de precio');
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceCatalogue $priceCatalogue)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllForStadium(Request $request)
    {
        try {

            $prices_type = $this->price_catalog_service->getAllForStadium($request->stadium_id);

            return response()->json($prices_type, 200);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los de precio');
      }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceCatalogue $priceCatalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceCatalogue $priceCatalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceCatalogue $priceCatalogue)
    {
        //
    }
}
