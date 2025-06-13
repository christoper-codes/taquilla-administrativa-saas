<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\PromotionService;
use App\Services\PromotionTypeService;
use App\Services\StadiumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromotionController extends Controller
{
    protected $promotion_type_service;
    protected $promotion_service;
    protected $stadium_service;

    public function __construct(PromotionTypeService $promotion_type_service, PromotionService $promotion_service, StadiumService $stadium_service)
    {
        $this->promotion_type_service = $promotion_type_service;
        $this->promotion_service = $promotion_service;
        $this->stadium_service = $stadium_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');
            $stadiums = $this->stadium_service->getAll();
            $promotion_types = $this->promotion_type_service->getAll();
            $promotions = $this->promotion_service->getAll();

            return Inertia::render('App/Administration/Offer/Promotion', [
                'user' => $user,
                'promotion_types'=> $promotion_types,
                'promotions' => $promotions,
                'stadiums' => $stadiums
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar las promociones');
      }
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllByStadium($id)
    {
        try {

            $promotions = $this->promotion_service->getAllByStadium($id);

            return response()->json($promotions, 200);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar las promociones');
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'promotion_type_id' => 'required|exists:promotion_types,id',
                'stadium_id' => 'required|exists:stadiums,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'generic_seats_allowed' => 'nullable|numeric',
                'promotional_seats_allowed' => 'nullable|numeric',
                'maximun_promotions_allowed' => 'nullable|numeric',
                'percent_allow' => 'nullable|numeric',
                'is_active_online' => 'nullable|boolean',
                'is_active' => 'required|boolean',
                'availability_sale' => 'nullable|numeric'
            ]);

            $data = $request->only(['promotion_type_id','stadium_id','name','description','generic_seats_allowed','promotional_seats_allowed','maximun_promotions_allowed','percent_allow','is_active_online','is_active', 'availability_sale']);

            $promotion = $this->promotion_service->save( $data );

            return WebResponseHelper::sendResponse($promotion, "Promoción guardado con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar la promoción');

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
                'promotion_type_id' => 'required|exists:promotion_types,id',
                'stadium_id' => 'required|exists:stadiums,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'generic_seats_allowed' => 'nullable|numeric',
                'promotional_seats_allowed' => 'nullable|numeric',
                'maximun_promotions_allowed' => 'nullable|numeric',
                'percent_allow' => 'nullable|numeric',
                'is_active_online' => 'nullable|boolean',
                'is_active' => 'required|boolean',
                'availability_sale' => 'nullable|numeric'
            ]);

            $data = $request->only(['promotion_type_id','stadium_id','name','description','generic_seats_allowed','promotional_seats_allowed','maximun_promotions_allowed','percent_allow','is_active_online','is_active', 'availability_sale']);

            $agreement = $this->promotion_service->update($request->id, $data);

            return WebResponseHelper::sendResponse($agreement, "Promoción actualizada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al actualizar la promoción');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $serie = $this->promotion_service->delete( $id );

            return WebResponseHelper::sendResponse($serie, "Promoción eliminada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al eliminar la promoción');

        }
    }
}
