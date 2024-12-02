<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WebResponseHelper;
use App\Services\CyberSourceService;
use CyberSource\Model\GenerateCaptureContextRequest;
use CyberSource\ExternalConfiguration;
use CyberSource\Configuration;
use CyberSource\ApiClient;
use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\Authentication\Core\MerchantConfiguration;

use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use  CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsTokenInformation;
use CyberSource\Model\CreatePaymentRequest;
use  CyberSource\Api\PaymentsApi;




class PaymentController extends Controller
{


    public function getCaptureContext()
    {
        try {
            $targetOrigins = array();
            $targetOrigins[0] = "http://localhost";
            $allowedCardNetworks = array();
            $allowedCardNetworks[0] = "VISA";
            $allowedCardNetworks[1] = "MASTERCARD";
            $allowedCardNetworks[2] = "AMEX";
            $requestObjArr = [
                "targetOrigins" => $targetOrigins,
                "clientVersion" => "v2.0",
                "allowedCardNetworks" => $allowedCardNetworks
            ];
            $requestObj = new GenerateCaptureContextRequest($requestObjArr);

            $merchantConfig = new MerchantConfiguration();
            $merchantConfig->setAuthenticationType('JWT');
            $merchantConfig->setMerchantID('idcompany_1730761460');
            $merchantConfig->setApiKeyID('1fed69df-bef3-462c-8534-6ee10709b9af');
            $merchantConfig->setSecretKey('ZGmaoIKJjY5eR7B5JEJpL69retYhj9J3EtSsf+xFoog=');
            $merchantConfig->setKeysDirectory('C:/Users/rich1/TaquillaNueva/taquilla-administrativa-saas/');
            $merchantConfig->setKeyFileName('idcompany_1730761460');

            $merchantConfig->setRunEnvironment(env('CYBERSOURCE_ENVIRONMENT') === 'production'
                ? 'api.cybersource.com'
                : 'apitest.cybersource.com');

            $merchantConfig->setKeyPassword('');
            $config = new Configuration();
            $config->setHost($merchantConfig->getHost());
            $config->setLogConfiguration($merchantConfig->getLogConfiguration());

            $apiClient = new ApiClient($config, $merchantConfig);
            $apiInstance = new MicroformIntegrationApi($apiClient);


            $apiResponse = $apiInstance->generateCaptureContext($requestObj);

            return response()->json([ "data"=> $apiResponse] , 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function paymentFlexApi(Request $request )
    {
        try {

            $amount = $request->input('amount');
            $token = $request->input('token');
            $user = $request->input('user');

            $clientReferenceInformationArr = [
                "code" => "TC50171_3"
            ];
            $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation($clientReferenceInformationArr);

            $orderInformationAmountDetailsArr = [
                "totalAmount" => "$amount",
                "currency" => "MXN"
            ];
            $orderInformationAmountDetails = new Ptsv2paymentsOrderInformationAmountDetails($orderInformationAmountDetailsArr);

            $orderInformationBillToArr = [
                "firstName" => $request->input('user.first_name'),
                "lastName" => $request->input('user.last_name').$request->input('user.middle_name'),
                "address1" => $request->input('address'),
                "locality" => $request->input('municipality'),
                "administrativeArea" => $request->input('state'),
                "postalCode" => $request->input('cp'),
                "country" => "MX",
                "email" => $request->input('user.email'),
                "phoneNumber" => $request->input('numPhone')
            ];
            $orderInformationBillTo = new Ptsv2paymentsOrderInformationBillTo($orderInformationBillToArr);

            $orderInformationArr = [
                "amountDetails" => $orderInformationAmountDetails,
                "billTo" => $orderInformationBillTo
            ];
            $orderInformation = new Ptsv2paymentsOrderInformation($orderInformationArr);

            $tokenInformationArr = [
                "transientTokenJwt" => $token
            ];
            $tokenInformation = new Ptsv2paymentsTokenInformation($tokenInformationArr);

            $requestObjArr = [
                "clientReferenceInformation" => $clientReferenceInformation,
                "orderInformation" => $orderInformation,
                "tokenInformation" => $tokenInformation
            ];
            $requestObj = new CreatePaymentRequest($requestObjArr);


            $merchantConfig = new MerchantConfiguration();
            $merchantConfig->setAuthenticationType('JWT');
            $merchantConfig->setMerchantID('idcompany_1730761460');
            $merchantConfig->setApiKeyID('1fed69df-bef3-462c-8534-6ee10709b9af');
            $merchantConfig->setSecretKey('ZGmaoIKJjY5eR7B5JEJpL69retYhj9J3EtSsf+xFoog=');
            $merchantConfig->setKeysDirectory('C:/Users/rich1/TaquillaNueva/taquilla-administrativa-saas/');
            $merchantConfig->setKeyFileName('idcompany_1730761460');
            $merchantConfig->setRunEnvironment(env('CYBERSOURCE_ENVIRONMENT') === 'production'
            ? 'api.cybersource.com'
            : 'apitest.cybersource.com');
            $merchantConfig->setKeyPassword('');
            $config = new Configuration();
            $config->setHost($merchantConfig->getHost());
            $config->setLogConfiguration($merchantConfig->getLogConfiguration());

            $apiClient = new ApiClient($config, $merchantConfig);
            $apiInstance = new PaymentsApi($apiClient);

            $apiResponse = $apiInstance->createPayment($requestObj);

            return response()->json(['response' => $apiResponse], 200);

        } catch (\Exception $e) {

            return response()->json(['error' =>  $e->getMessage()], 500);


        }
    }








}
