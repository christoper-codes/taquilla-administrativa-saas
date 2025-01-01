<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use phpseclib3\Crypt\RSA;
use phpseclib3\Math\BigInteger;
use Illuminate\Http\Request;
use App\Helpers\WebResponseHelper;
use App\Services\CyberSourceService;
use CyberSource\Model\GenerateCaptureContextRequest;
use CyberSource\ExternalConfiguration;
use CyberSource\Configuration;
use CyberSource\ApiClient;
use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Model\Riskv1authenticationsetupsClientReferenceInformation;
use CyberSource\Model\Riskv1authenticationsetupsPaymentInformation;
use CyberSource\Model\Riskv1authenticationsetupsTokenInformation;
use CyberSource\Model\PayerAuthSetupRequest;
use Cybersource\ApiException;
use CyberSource\Api\PayerAuthenticationApi;
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
            $merchantConfig->setMerchantID('bb_halcones_xalapa');
            $merchantConfig->setApiKeyID('e8221c68-6e9f-4866-82b9-d39eb1add363');
            $merchantConfig->setSecretKey('BAxHHg+yxHu7IYtWV1nutlPf01v8Oi7zTHPt0/8jyso=');
            $merchantConfig->setKeysDirectory('C:/Users/rich1/TaquillaNueva/taquilla-administrativa-saas/');
            $merchantConfig->setKeyFileName('bb_halcones_xalapa');

            $merchantConfig->setRunEnvironment(env('CYBERSOURCE_ENVIRONMENT') === 'production'
                ? 'api.cybersource.com'
                : 'apitest.cybersource.com');

            $merchantConfig->setKeyPassword('Halconesxalapa1*dic');
            $config = new Configuration();
            $config->setHost($merchantConfig->getHost());
            $config->setLogConfiguration($merchantConfig->getLogConfiguration());

            $apiClient = new ApiClient($config, $merchantConfig);
            $apiInstance = new MicroformIntegrationApi($apiClient);


            $apiResponse = $apiInstance->generateCaptureContext($requestObj);

            //return response()->json(['data' => $apiResponse], 200);
            if (is_array($apiResponse)) {
                //return $this->validarContextoCaptura($apiResponse);
                return response()->json(['datos' => $apiResponse], 200);
            }else {
                throw new \Exception('No es una lista');
            }

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    function setupCompletionWithFlexTransientToken(Request $request)
    {
        $clientReferenceInformationArr = [
            "code" => "halcones-xalapa2024"
        ];
        $clientReferenceInformation = new Riskv1authenticationsetupsClientReferenceInformation($clientReferenceInformationArr);

        $card = [
            'type' => $request->cardType,
            'expirationMonth' => $request->expirationMonth,
            'expirationYear'=> $request->expirationYear
        ];

        $paymentInformationArr = [
            'card' => $card
        ];

        $paymentInformation = new Riskv1authenticationsetupsPaymentInformation($paymentInformationArr);


        $tokenInformationArr = [
            "transientToken" => $request->token
        ];
        $tokenInformation = new Riskv1authenticationsetupsTokenInformation($tokenInformationArr);

        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            'paymentInformation' => $paymentInformation,
            "tokenInformation" => $tokenInformation
        ];
        $requestObj = new PayerAuthSetupRequest($requestObjArr);

        $merchantConfig = new MerchantConfiguration();
        $merchantConfig->setAuthenticationType('HTTP_SIGNATURE');
        $merchantConfig->setMerchantID('bb_halcones_xalapa');
        $merchantConfig->setApiKeyID('e8221c68-6e9f-4866-82b9-d39eb1add363');
        $merchantConfig->setSecretKey('BAxHHg+yxHu7IYtWV1nutlPf01v8Oi7zTHPt0/8jyso=');
        $merchantConfig->setKeysDirectory('C:/Users/rich1/TaquillaNueva/taquilla-administrativa-saas/');
        $merchantConfig->setKeyFileName('bb_halcones_xalapa');

        $merchantConfig->setRunEnvironment(env('CYBERSOURCE_ENVIRONMENT') === 'production'
            ? 'api.cybersource.com'
            : 'apitest.cybersource.com');

        $merchantConfig->setKeyPassword('Halconesxalapa1*dic');
        $config = new Configuration();
        $config->setHost($merchantConfig->getHost());
        $config->setLogConfiguration($merchantConfig->getLogConfiguration());


        $api_client = new ApiClient($config, $merchantConfig);
        $api_instance = new PayerAuthenticationApi($api_client);

        try {
            $apiResponse = $api_instance->payerAuthSetup($requestObj);
            return response()->json(['datos' => $apiResponse], 200);

        } catch (ApiException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function validarContextoCaptura($jwt)
    {
        try {


        // Decodificar el encabezado para obtener el 'kid'
        $parts = explode('.', $jwt[0]);
        $header = json_decode(base64_decode($parts[0]), true);

        if (!isset($header['kid'])) {
            throw new \Exception("El JWT no contiene un 'kid'.");
        }

        $kid = $header['kid'];

        //Obtener la clave pública desde CyberSource
        $publicKeyUrl = "https://apitest.cybersource.com/flex/v2/public-keys/{$kid}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $publicKeyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            throw new \Exception('No se pude obtener la clave publica');
        }

        $publicKeyData = json_decode($response, true);

        //Extraer y decodificar los valores de `n` y `e`
        $modulus = new BigInteger($this->base64url_decode($publicKeyData['n']), 256);
        $exponent = new BigInteger($this->base64url_decode($publicKeyData['e']), 256);

        //Crear una clave pública RSA usando phpseclib
        $rsa = RSA::loadFormat('Raw', ['n' => $modulus, 'e' => $exponent]);
        $publicKeyPem = $rsa->toString('PKCS8'); // Convierte al formato PEM

        //Validar si la clave pública está bien formada
        if (!$publicKeyPem) {
            throw new \Exception("No se pudo generar una clave pública válida.");
        }

        $decoded = JWT::decode($jwt[0], new Key($publicKeyPem, $header['alg']));
        return response()->json(['datos' => $decoded], 200);
        } catch (Exception $e) {
            return response()->json(['errorClave' => $e->getMessage()], 500);
        }

    }

    function base64url_decode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
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
            $merchantConfig->setKeyPassword('$Richi12024');
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
