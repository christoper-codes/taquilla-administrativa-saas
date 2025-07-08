<?php

namespace App\Http\Controllers;

use App\Services\UtilService;
use Illuminate\Http\Request;

class UtilController extends Controller
{
    protected $utils_service;

    public function __construct(UtilService $utils_service)
    {
        $this->utils_service = $utils_service;
    }

    public function migrate()
    {
        try{

            $migrate  = $this->utils_service->migrate();

            return response()->json([
                'data' => [
                    'migrate' => $migrate,
                ],
                'message' => 'Migración ejecuta con exitoso',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cleanAll()
    {
        try{

            $cleanAll  = $this->utils_service->cleanAll();

            return response()->json([
                'data' => [
                    'cleanAll' => $cleanAll,
                ],
                'message' => 'Optimización ejecuta con exitoso',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function refreshCaches()
    {
        try{

            $refreshCaches  = $this->utils_service->refreshCaches();

            return response()->json([
                'data' => [
                    'refreshCaches' => $refreshCaches,
                ],
                'message' => 'Optimización ejecuta con exitoso',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storageCopy()
    {
        try{

            $storageCopy  = $this->utils_service->storageCopy();

            return response()->json([
                'data' => [
                    'message' => $storageCopy,
                ],
                'message' => 'Copiado ejecuta con exitoso',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCP(Request $request)
    {
        $request->validate(['cp' => 'required|max:5|min:5']);

        try{
            $getCP  = $this->utils_service->getCP($request->cp);

            return response()->json($getCP, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => "No se pudo obtener información para el código postal ingresado"
            ], 500);
        }
    }

}
