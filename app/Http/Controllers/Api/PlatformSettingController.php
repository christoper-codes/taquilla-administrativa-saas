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

    public function index()
    {
        try {
            $platform_settings = $this->platform_setting_service->getAll();

            return response()->json([
                'data' => [
                    'platform_settings' => $platform_settings,
                ],
                'message' => 'Configuraciones recuperadas con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
