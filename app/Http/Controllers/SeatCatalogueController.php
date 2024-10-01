<?php

namespace App\Http\Controllers;

use App\Models\SeatCatalogue;
use App\Services\SeatCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SeatCatalogueController extends Controller
{

    /*
    * Inject the SeatCatalogue service into the controller
    */
    protected $seat_catalogue_service;

    public function __construct(SeatCatalogueService $seat_catalogue_service)
    {
        $this->seat_catalogue_service = $seat_catalogue_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->load('globalImages');
        $flash = $user->is_new ? 'is_new_user' : null;

        return Inertia::render('Dashboard', [
            'user' => $user,
            'flash' => $flash,
        ]);
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
    public function show(SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeatCatalogue $seatCatalogue)
    {
        //
    }
}
