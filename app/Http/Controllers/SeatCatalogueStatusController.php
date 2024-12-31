<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\SeatCatalogueStatusesService;
use Illuminate\Http\Request;

class SeatCatalogueStatusController extends Controller
{
    protected $seat_catalogue_statuses_service;

    public function __construct(SeatCatalogueStatusesService $seat_catalogue_statuses_service)
    {
        $this->seat_catalogue_statuses_service = $seat_catalogue_statuses_service;
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
                'seats.*.modified_status_id' => 'required|exists:seat_catalogue_statuses,id'
            ]);

            $seats = $request->collect('seats')->map(function ($seat) {
                return [
                    "id" => $seat['id'],
                    "modified_status_id" => $seat['modified_status_id']
                ];
            })->toArray();

            $data =  $this->seat_catalogue_statuses_service->addStatusToSeat($seats);

            return response()->json($data, 200);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar las promociones para los asientos');

        }
    }

    /**
     * Display a listing of the resource.
     */
    public function blockAndReservationStatuses()
    {
        try {

            $seat_catalogue_statuses = $this->seat_catalogue_statuses_service->getBlockAndReservationStatuses();

            return response()->json($seat_catalogue_statuses, 200);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los status de asientos');
      }
    }
}
