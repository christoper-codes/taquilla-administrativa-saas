<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function ticketsPurchased(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($request->user_id);

            $tickets = $user->EventSeatCatalogues()
                ->with('event', 'seatCatalogue', 'seatCatalogueStatus')
                ->whereHas('seatCatalogueStatus', function ($query) {
                    $query->where('name', 'vendido');
                })
                ->get();

            return response()->json([
                'data' => [
                    'tickets' => $tickets,
                ],
                'message' => 'Boletos comprados recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
