<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlatformSettingService;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
    protected $platform_setting_service;

    public function __construct(PlatformSettingService $platform_setting_service)
    {
        $this->platform_setting_service = $platform_setting_service;
    }

    public function getAll()
    {
        try {
            $platform_settings = $this->platform_setting_service->getAll();

            return response()->json([
                'data' => [
                    'platform_settings' => $platform_settings,
                ],
                'message' => 'Configuraciones recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getByKey(Request $request)
    {
        $request->validate([ 'key' => 'required|exists:platform_settings,key']);

        try {

            $platform_settings = $this->platform_setting_service->getByKey($request->key);

            return response()->json([
                'data' => [
                    'configs' => $platform_settings,
                ],
                'message' => 'Configuración recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request)
    {
        $request->validate([ 'key' => 'required', 'value' => 'required', 'platform' => 'required|in:web,app' ]);

        try {

            $platform_settings = $this->platform_setting_service->save($request->only(['key', 'value', 'platform']));

            return response()->json([
                'data' => $platform_settings,
                'message' => 'Configuración guardada con éxito',
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
