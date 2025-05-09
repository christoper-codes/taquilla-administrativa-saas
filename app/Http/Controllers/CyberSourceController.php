<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\CyberSourceService;
use Illuminate\Http\Request;

class CyberSourceController extends Controller
{

    protected $cyber_source_service;

    public function __construct(CyberSourceService $cyber_source_service)
    {
        $this->cyber_source_service = $cyber_source_service;
    }


    public function getCaptureContext()
    {
        try {

            $getCaptureContext = $this->cyber_source_service->getCaptureContext();

            return response()->json(['data' => $getCaptureContext], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function paymentWithFlexTransientToken(Request $request)
    {
        try {

            $request->validate([
                'clientReferenceInformation' => 'required',
                'orderInformationAmountDetails' => 'required',
                'orderInformationBillTo' => 'required',
                'tokenInformation' => 'required',
            ]);

            $response =  $this->cyber_source_service->paymentWithFlexTransientToken($request->only(['clientReferenceInformation', 'orderInformationAmountDetails','orderInformationBillTo','tokenInformation']));

            return response()->json(['data' => $response], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

}
