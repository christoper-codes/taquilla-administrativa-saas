<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\Agreement;
use App\Models\Institution;
use App\Services\AgreementService;
use App\Services\GlobalSeasonService;
use App\Services\InstitutionService;
use App\Services\PromotionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AgreementController extends Controller
{
    protected $agreement_service;
    protected $institution_service;
    protected $global_season_service;
    protected $promotion_service;

    public function __construct(AgreementService $agreement_service, InstitutionService $institution_service, GlobalSeasonService $global_season_service,
                                PromotionService $promotion_service)
    {
        $this->agreement_service = $agreement_service;
        $this->institution_service = $institution_service;
        $this->global_season_service = $global_season_service;
        $this->promotion_service = $promotion_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');
            $agreements = $this->agreement_service->getAll();
            $institutions = $this->institution_service->getAll();
            $global_seasons = $this->global_season_service->getAll();
            $promotions = $this->promotion_service->getAll();

            return Inertia::render('App/Administration/Offer/Agreement', [
                'user' => $user,
                'agreements' => $agreements,
                'institutions'=> $institutions,
                'global_seasons'=> $global_seasons,
                'promotions'=> $promotions
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los convenios');
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'institution_id' => 'required|exists:institutions,id',
                'global_season_id' => 'required|exists:global_seasons,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean',
                'promotions' => 'required',
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d')
            ]);

            $data = $request->only(['institution_id', 'global_season_id','name','description','start_date', 'end_date','is_active']);

            $agreement = $this->agreement_service->save( $data );

            $promotions = collect($request->promotions)->map(fn($value) => ['promotion_id' => $value]);

            $agreement->promotions()->attach($promotions);

            return WebResponseHelper::sendResponse($agreement, "Convenio guardado con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar el convenio');

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'institution_id' => 'required|exists:institutions,id',
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

            $data = $request->only(['institution_id','global_season_id','name','description','start_date', 'end_date','is_active']);

            $this->agreement_service->update($request->id, $data);

            $agreement = $this->agreement_service->getById($request->id);

            $promotions = collect($request->promotions)->mapWithKeys(fn($value) => [ $value => ['updated_at' => now()]]);

            $agreement->promotions()->sync($promotions);

            return WebResponseHelper::sendResponse($agreement, "Convenio actualizado con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al actualizar el convenio');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $serie = $this->agreement_service->delete( $id );

            return WebResponseHelper::sendResponse($serie, "Convenio eliminado con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al eliminar el convenio');

        }
    }
}
