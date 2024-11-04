<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\EventSeatCatalogPromotionService;
use Illuminate\Http\Request;

class EventSeatCatalogPromotionController extends Controller
{
    protected $event_seat_catalog_promotion_service;

    public function __construct(EventSeatCatalogPromotionService $event_seat_catalog_promotion_service)
    {
        $this->event_seat_catalog_promotion_service = $event_seat_catalog_promotion_service;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'seats' => 'required|array',
                'seats.*.id' => 'required|exists:event_seat_catalog,id',
                'seats.*.select_promotion' => 'nullable|array',
                'seats.*.select_promotion.*' => 'nullable|exists:promotions,id'
            ]);

            $seats = $request->collect('seats')->map(function ($seat) {
                return [
                    "id" => $seat['id'],
                    "select_promotion" => $seat['select_promotion']
                ];
            })->toArray();

            $data =  $this->event_seat_catalog_promotion_service->addPromotionToSeat($seats);

            return response()->json($data, 200);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar las promociones para los asientos');

        }
    }
}
