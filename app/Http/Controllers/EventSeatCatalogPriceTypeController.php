<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\EventSeatCatalogPriceType;
use App\Services\EventSeatCatalogPriceTypeService;
use App\Services\PriceCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EventSeatCatalogPriceTypeController extends Controller
{
    protected $event_seat_catalog_price_type_service;
    protected $price_catalog_service;

    public function __construct(EventSeatCatalogPriceTypeService $event_seat_catalog_price_type_service, PriceCatalogService $price_catalog_service)
    {
        $this->event_seat_catalog_price_type_service = $event_seat_catalog_price_type_service;
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
     * Display the specified resource.
     */
    public function show(EventSeatCatalogPriceType $eventSeatCatalogPriceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventSeatCatalogPriceType $eventSeatCatalogPriceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            $request->validate([
                'seats' => 'required|array',
                'seats.*.id' => 'required|exists:event_seat_catalog,id',
                'seats.*.price_types_update' => 'nullable|array',
                'seats.*.price_types_update.id' => 'nullable|exists:price_types,id',
                'seats.*.price_types_update.price' => 'nullable|exists:price_catalogues,price'
            ]);

            $stadium_id = $request->collect('seats')->first()['seat_catalogue']['stadium_id'];
            $prices_catalog = $this->price_catalog_service->getAllForStadium($stadium_id);

            $seats = $request->collect('seats')->flatMap(function ($seat) use ($prices_catalog) {

                $event_seat_catalog_id = $seat['id'];

                return collect($seat['price_types_update'])->filter(function($price_types_update){

                    return filled($price_types_update['id']) && filled($price_types_update['price']);

                })->map(function ($price_types_update) use ($prices_catalog, $event_seat_catalog_id) {

                    $price = $prices_catalog->where('price', $price_types_update['price'])->first();

                    return [
                        "event_seat_catalog_id" => $event_seat_catalog_id,
                        "price_type_id" => $price_types_update['id'],
                        "price_catalogue_id" => $price['id'],
                        "price" => $price_types_update['price']
                    ];
                });

            })->toArray();

            if (empty($seats)) {
                return response()->json(['message' => 'No hay actualizaciones de precios para los asientos seleccionados.'], 422);
            }

            $data =  $this->event_seat_catalog_price_type_service->updateInBulk($seats);

            return response()->json($data, 200);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al actualizar los precios para los asientos');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventSeatCatalogPriceType $eventSeatCatalogPriceType)
    {
        //
    }
}
