<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\Serie;
use App\Services\GlobalSeasonService;
use App\Services\SerieService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SerieController extends Controller
{
    protected $serie_service;
    protected $global_season_service;

    public function __construct(SerieService $serie_service, GlobalSeasonService $global_season_service)
    {
        $this->serie_service = $serie_service;
        $this->global_season_service = $global_season_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');
            $series = $this->serie_service->getAll();
            $global_seasons = $this->global_season_service->getAll();

            return Inertia::render('App/Administration/Event/Series', [
                'user' => $user,
                'series' => $series,
                'global_seasons'=> $global_seasons
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');
      }
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
        try {

            $request->validate([
                'global_season_id' => 'required|exists:global_seasons,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean',
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d')
            ]);

            $data = $request->only(['global_season_id','name','description','start_date', 'end_date','is_active']);

            $serie = $this->serie_service->save( $data );

            return WebResponseHelper::sendResponse($serie, "Serie guardada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Serie $serie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Serie $serie)
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
                'id' => 'required',
                'global_season_id' => 'required|exists:global_seasons,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean',
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d')
            ]);

            $data = $request->only(['global_season_id','name','description','start_date', 'end_date','is_active']);

            $data = $this->serie_service->update($request->id, $data);

            return WebResponseHelper::sendResponse($data, "Serie actualizada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $serie = $this->serie_service->delete( $id );

            return WebResponseHelper::sendResponse($serie, "Serie eliminada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');

        }
    }
}
