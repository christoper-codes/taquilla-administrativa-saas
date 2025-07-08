<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Services\CyberSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        } catch (\CyberSource\ApiException $e) {

            return response()->json([
                'error' => $e->getResponseBody()
            ], $e->getCode() ?: 500);
        }
    }

    public function authenticationSetup(Request $request)
    {
        $request->validate([
            'expirationMonth' => 'required',
            'expirationYear' => 'required',
            'tokenInformation' => 'required',
        ]);

        try {

            $response =  $this->cyber_source_service->authenticationSetup($request->only(['tokenInformation','expirationMonth', 'expirationYear']));

            return response()->json(['data' => $response], 200);

        } catch (\CyberSource\ApiException $e) {

            return response()->json([
                'error' => $e->getResponseBody()
            ], $e->getCode() ?: 500);
        }
    }

    public function enrollWithTransientToken(Request $request)
    {
        $request->validate([
            'clientReferenceInformation' => 'required|array',
            'clientReferenceInformation.code' => 'required|string',

            'orderInformation' => 'required|array',
            'orderInformation.amountDetails' => 'required|array',
            'orderInformation.amountDetails.currency' => 'required|string',
            'orderInformation.amountDetails.totalAmount' => 'required|numeric|min:0',

            'orderInformation.billTo' => 'required|array',
            'orderInformation.billTo.firstName' => 'required|string|max:255',
            'orderInformation.billTo.lastName' => 'required|string|max:255',
            'orderInformation.billTo.phoneNumber' => 'required|string|max:10',
            'orderInformation.billTo.email' => 'required|email|max:255',
            'orderInformation.billTo.address1' => 'required|string|max:255',
            'orderInformation.billTo.country' => 'required|string|max:255',
            'orderInformation.billTo.locality' => 'required|string|max:255',
            'orderInformation.billTo.postalCode' => 'required|string|max:5',

            'browserInfo' => 'required|array',
            'tokenInformation' => 'required',
            'expirationMonth' => 'required',
            'expirationYear' => 'required',
            'referenceId' => 'Required',
        ]);

        try {

            $response =  $this->cyber_source_service->enrollWithTransientToken($request->only(['clientReferenceInformation', 'orderInformation','browserInfo','tokenInformation','expirationMonth', 'expirationYear', 'referenceId']));

            return response()->json(['data' => $response], 200);

        } catch (\CyberSource\ApiException $e) {

            return response()->json([
                'error' => $e->getResponseBody()
            ], $e->getCode() ?: 500);
        }
    }

    public function validateAuthentication(Request $request)
    {
        $request->validate([
            'clientReferenceInformation' => 'required|array',
            'clientReferenceInformation.code' => 'required|string',

            'orderInformation' => 'required|array',
            'orderInformation.amountDetails' => 'required|array',
            'orderInformation.amountDetails.currency' => 'required|string',
            'orderInformation.amountDetails.totalAmount' => 'required|numeric|min:0',

            'tokenInformation' => 'required',

            'consumerAuthenticationInformation' => 'required|array',
            'consumerAuthenticationInformation.authenticationTransactionId' => 'required|string',
        ]);

        try {

            $response =  $this->cyber_source_service->validateAuthentication($request->only(['clientReferenceInformation', 'orderInformation','tokenInformation', 'consumerAuthenticationInformation']));

            return response()->json(['data' => $response], 200);

        } catch (\CyberSource\ApiException $e) {

            return response()->json([
                'error' => $e->getResponseBody()
            ], $e->getCode() ?: 500);
        }
    }

    public function paymentApi(Request $request)
    {
        $request->validate([
            'clientReferenceInformation' => 'required|array',
            'clientReferenceInformation.code' => 'required|string',

            'orderInformation' => 'required|array',
            'orderInformation.amountDetails' => 'required|array',
            'orderInformation.amountDetails.currency' => 'required|string',
            'orderInformation.amountDetails.totalAmount' => 'required|numeric|min:0',

            'orderInformation.billTo' => 'required|array',
            'orderInformation.billTo.firstName' => 'required|string|max:255',
            'orderInformation.billTo.lastName' => 'required|string|max:255',
            'orderInformation.billTo.phoneNumber' => 'required|string|max:10',
            'orderInformation.billTo.email' => 'required|email|max:255',
            'orderInformation.billTo.address1' => 'required|string|max:255',
            'orderInformation.billTo.country' => 'required|string|max:255',
            'orderInformation.billTo.locality' => 'required|string|max:255',
            'orderInformation.billTo.postalCode' => 'required|string|max:5',

            'tokenInformation' => 'required',
            'expirationMonth' => 'required',
            'expirationYear' => 'required',
            'response' => 'required',
        ]);

        try {
            $response =  $this->cyber_source_service->paymentApi($request->only(['clientReferenceInformation', 'orderInformation','tokenInformation', 'expirationMonth', 'expirationYear', 'response']));

            return response()->json(['data' => $response], 201);

        } catch (\CyberSource\ApiException $e) {

            return response()->json([
                'error' => $e->getResponseBody()
            ], $e->getCode() ?: 500);

        }
    }


    public function clientAuthentication(Request $request)
    {
        try {
            return $this->cyber_source_service->clientAuthentication($request->only(['TransactionId']));

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
