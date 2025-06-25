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

    public function get()
    {
        try {
            $platform_settings = $this->platform_setting_service->get();

            return response()->json([
                'data' => [
                    'platform_settings' => $platform_settings->settings,
                ],
                'message' => 'Configuraciones recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
