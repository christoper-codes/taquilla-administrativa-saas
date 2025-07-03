<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventSeatCatalog;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function checkTicket(Request $request)
    {
        try {

             $request->validate([
                'event_id' => 'required|integer|exists:events,id',
                'qr' => 'required|string|exists:event_seat_catalog,qr',
            ]);

            $ticket = EventSeatCatalog::where('event_id', $request->event_id)
                ->where('qr', $request->qr)
                ->first();

            if($ticket->is_verified) {
                throw new \Exception('El boleto ya ha sido verificado');
            }

            $ticket->is_verified = true;
            $ticket->save();

             return response()->json([
                'data' => [
                    'ticket' => $ticket,
                ],
                'message' => 'Ticket verificado con éxito',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
