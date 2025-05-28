<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EventService;

class EventController extends Controller
{
    protected $event_service;

    public function __construct(EventService $event_service)
    {
        $this->event_service = $event_service;
    }

    public function index()
    {
        try {
            $events = $this->event_service->getAll();

            return response()->json([
                'data' => [
                    'events' => $events,
                ],
                'message' => 'Eventos recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
