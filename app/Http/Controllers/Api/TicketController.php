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
                'tikcet_id' => 'required|integer|exists:event_seat_catalog,id',
            ]);

            $ticket = EventSeatCatalog::where('id', $request->tikcet_id)->first();

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
