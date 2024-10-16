<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WebResponseHelper
{
    public static function rollback($e = null, $message = 'Fracaso en el proceso')
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e = null, $message = 'Fracaso en el proceso')
    {
        if($e == null) {
            $errorDetails = [
                'success' => false,
                'message' => $message,
            ];

            return redirect()->back()->with('message', $errorDetails);
        }
        Log::info($e);
        $errorDetails = [
            'success' => false,
            'message' => $message,
            'error' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ];

        //Session::flash('error', $errorDetails);
        return redirect()->back()->with('error', $errorDetails);
    }

    public static function sendResponse($result, $message = 'Éxito en el proceso', $route = 'welcome', $is_redirect = true)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        //Session::flash('message', $response);

        if($is_redirect) {
            return redirect(route($route, absolute: false))->with('message', $response);
        }

        return redirect()->back()->with('success', $response);
    }
}
