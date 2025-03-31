<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\GlobalImageService;
use App\Services\InstitutionService;
use App\Services\StadiumService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class InstitutionController extends Controller
{
    protected $institution_service;
    protected $stadium_service;
    protected $global_image_service;

    public function __construct(StadiumService $stadium_service, InstitutionService $institution_service, GlobalImageService $global_image_service)
    {
        $this->institution_service = $institution_service;
        $this->stadium_service = $stadium_service;
        $this->global_image_service = $global_image_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');
            $stadiums = $this->stadium_service->getAll();
            $institutions = $this->institution_service->getAll();

            return Inertia::render('App/Administration/Offer/Institution', [
                'user' => $user,
                'stadiums' => $stadiums,
                'institutions' => $institutions
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar las instituciones');
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'stadium_id' => 'required|exists:stadiums,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'is_active' => 'required|boolean',
            ]);

            $data = $request->only(['stadium_id','name','description','is_active']);

            $institution = $this->institution_service->save( $data );


            if($request->global_image){

                $global_image = $this->global_image_service->save($request->only('global_image'), 'institution_images');

                $institution->global_image_id = $global_image->id;

                $institution->save();
            }

            return WebResponseHelper::sendResponse($institution, "institución guardada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar la institucion');

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
                'stadium_id' => 'required|exists:stadiums,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'is_active' => 'required|boolean',
            ]);

            if($request->global_image){

                $global_image = $this->global_image_service->save($request->only('global_image'), 'institution_images');

                $request->merge([ 'global_image_id' => $global_image->id ]);
            }

            $data = $request->only(['stadium_id','global_image_id','name','description','is_active']);

            $institution = $this->institution_service->update($request->id, $data);

            return WebResponseHelper::sendResponse($institution, "institución actualizada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al actualizar la institución');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $institution = $this->institution_service->delete( $id );

            return WebResponseHelper::sendResponse($institution, "institución eliminada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al eliminar la institución');

        }
    }
}
