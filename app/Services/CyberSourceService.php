<?php

namespace App\Services;

use App\Interfaces\CyberSourceRepositoryInterface;
use App\Models\PayerAuthentication;
use CyberSource\Model\Riskv1authenticationsDeviceInformation;
use CyberSource\Model\Riskv1authenticationsetupsClientReferenceInformation;
use CyberSource\Model\Riskv1authenticationsetupsTokenInformation;
use CyberSource\Model\Riskv1authenticationsOrderInformation;
use CyberSource\Model\Riskv1authenticationsOrderInformationAmountDetails;
use CyberSource\Model\Riskv1authenticationsOrderInformationBillTo;
use CyberSource\Model\Riskv1authenticationsPaymentInformation;
use CyberSource\Model\Riskv1decisionsConsumerAuthenticationInformation;
use CyberSource\ObjectSerializer;


class CyberSourceService
{

    protected $cyber_source_repository_interface;

    public function __construct(CyberSourceRepositoryInterface $cyber_source_repository_interface)
    {
        $this->cyber_source_repository_interface = $cyber_source_repository_interface;
    }

    public function getCaptureContext()
    {
        $generateCaptureContextRequest = $this->generateCaptureContextRequest();

        $merchantConfig = $this->merchantConfigObject();

        $config = $this->connectionHost($merchantConfig);

        $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

        $apiInstance = $this->cyber_source_repository_interface->microformIntegrationApi($apiClient);

        $apiResponse = $apiInstance->generateCaptureContext($generateCaptureContextRequest);


        $captureContext = $apiResponse[0];

        $decoded = json_decode(base64_decode(explode('.', $captureContext)[1]), true);

        $clientLibrary = $decoded['ctx'][0]['data']['clientLibrary'] ?? '';
		$clientLibraryIntegrity =$decoded['ctx'][0]['data']['clientLibraryIntegrity'] ?? '';

        return [
            'captureContext' => $captureContext,
            'clientLibrary' => $clientLibrary,
            'clientLibraryIntegrity' => $clientLibraryIntegrity
        ];
    }

    public function generateCaptureContextRequest()
    {
        return $this->cyber_source_repository_interface->generateCaptureContextRequest([
                "targetOrigins" => ["http://localhost:8000"],
                "clientVersion" => "v2",
                "allowedCardNetworks" => ["VISA", "MASTERCARD", "AMEX"],
                "allowedPaymentTypes" => ["CARD"]
        ]);
    }

    public function merchantConfigObject()
    {
        return $this->cyber_source_repository_interface->merchantConfigObject([
            'authenticationType' => ['method' => 'setAuthenticationType', 'value' => strtoupper(trim('http_signature'))],
            'merchantID'         => ['method' => 'setMerchantID',        'value' => trim(env('CYBERSOURCE_MERCHANT_ID'))],
            'apiKeyID'           => ['method' => 'setApiKeyID',          'value' => env('CYBERSOURCE_API_KEY_ID')],
            'secretKey'          => ['method' => 'setSecretKey',         'value' => env('CYBERSOURCE_SECRET_KEY')],
            'runEnv'             => ['method' => 'setRunEnvironment',    'value' => env('CYBERSOURCE_ENVIRONMENT')],
        ], $this->logConfiguration());
    }

    public function connectionHost($merchantConfigObject)
    {
        return $this->cyber_source_repository_interface->connectionHost($merchantConfigObject);
    }

    public function logConfiguration()
    {
        return $this->cyber_source_repository_interface->logConfiguration([
            'enableLogging' => ['method' => 'enableLogging',    'value' => true],
            'debugLogFile'  => ['method' => 'setDebugLogFile',  'value' => __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "debugTest.log"],
            'errorLogFile'  => ['method' => 'setErrorLogFile',  'value' => __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "errorTest.log"],
            'logDateFormat' => ['method' => 'setLogDateFormat', 'value' => "Y-m-d\TH:i:s"],
            'logFormat'     => ['method' => 'setLogFormat',     'value' => "[%datetime%] [%level_name%] [%channel%] : %message%\n"],
            'logMaxFiles'   => ['method' => 'setLogMaxFiles',   'value' => 3],
            'logLevel'      => ['method' => 'setLogLevel',      'value' => "debug"],
            'enableMasking' => ['method' => 'enableMasking',    'value' => true],
        ]);
    }

    public function authenticationSetup(array $data)
    {
            $json = $this->decodeJwt($data['tokenInformation']);

            $requestObj = $this->cyber_source_repository_interface->payerAuthSetupRequest([
                 "paymentInformation" => new Riskv1authenticationsPaymentInformation([
                    "card" => [
                        "type" => $json['content']['paymentInformation']['card']['number']['detectedCardTypes'],
                        "expirationYear" => $data['expirationYear'],
                        "expirationMonth" => $data['expirationMonth']
                    ]
                ]),
                "tokenInformation" => new Riskv1authenticationsetupsTokenInformation(["jti" => $json['jti']]),
            ]);

            $merchantConfig = $this->merchantConfigObject();

            $config = $this->connectionHost($merchantConfig);

            $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

            $api_instance = $this->cyber_source_repository_interface->payerAuthenticationApi($apiClient);

            list($response, $statusCode, $httpHeader) = $api_instance->payerAuthSetup($requestObj);

            $data = json_decode ($response, true);

            $data['jti'] = $json['jti'];

            return $data;

    }

    function EnrollWithTransientToken(array $data)
    {
        $json = $this->decodeJwt($data['tokenInformation']);
        $cardType = $json['content']['paymentInformation']['card']['number']['detectedCardTypes'];

        $dataEnrollment = [
            "clientReferenceInformation" => new Riskv1authenticationsetupsClientReferenceInformation($data['clientReferenceInformation']),
            "paymentInformation" => new Riskv1authenticationsPaymentInformation([
                "card" => [
                    "type" => $cardType,
                    "expirationYear" => $data['expirationYear'],
                    "expirationMonth" => $data['expirationMonth']
                ]
            ]),
            "orderInformation" => new Riskv1authenticationsOrderInformation([
                "amountDetails" => new Riskv1authenticationsOrderInformationAmountDetails($data['orderInformation']['amountDetails']),
                "billTo" => new Riskv1authenticationsOrderInformationBillTo($data['orderInformation']['billTo'])
            ]),
            "tokenInformation" => new Riskv1authenticationsetupsTokenInformation(["jti" => $json['jti']]),
            "consumerAuthenticationInformation" => new Riskv1decisionsConsumerAuthenticationInformation([
                "returnUrl" => env('CYBERSOURCE_RETURN_URL'),
                "referenceId" => $data['referenceId']
            ]),
            "deviceInformation" => new Riskv1authenticationsDeviceInformation($data['browserInfo'])
        ];

        $requestObj = $this->cyber_source_repository_interface->checkPayerAuthEnrollmentRequest($dataEnrollment);

        $merchantConfig = $this->merchantConfigObject();

        $config = $this->connectionHost($merchantConfig);

        $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

        $api_instance = $this->cyber_source_repository_interface->payerAuthenticationApi($apiClient);

        list($response, $statusCode, $httpHeader) =  $api_instance->checkPayerAuthEnrollment($requestObj);

        $jsonDecode = json_decode ($response, true);

        if ($statusCode == 201) {
            PayerAuthentication::create([
                'code' => $data['clientReferenceInformation']['code'],
                'transaction_id' => $jsonDecode['consumerAuthenticationInformation']['authenticationTransactionId'],
                'cardType' => $cardType[0],
                'enroll_payload' => $data,
                'enroll_response' => $jsonDecode,
            ]);
        }

        return $jsonDecode;
    }

    function validateAuthentication(array $data)
    {
        $dataValidation = [
            "consumerAuthenticationInformation" => new Riskv1decisionsConsumerAuthenticationInformation([
                "authenticationTransactionId" => $data['TransactionId'],
            ]),
        ];

        $validateRequest = $this->cyber_source_repository_interface->validateRequest($dataValidation);

        $merchantConfig = $this->merchantConfigObject();

        $config = $this->connectionHost($merchantConfig);

        $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

        $api_instance = $this->cyber_source_repository_interface->payerAuthenticationApi($apiClient);

        list($response, $statusCode, $httpHeader) = $api_instance->validateAuthenticationResults($validateRequest);

        return json_decode ($response, true);
    }

    function paymentApi(array $data)
    {
        $json = $this->decodeJwt($data['tokenInformation']);

        $cardType = $json['content']['paymentInformation']['card']['number']['detectedCardTypes'][0];  // 001 for Visa, 002 for MasterCard.

        $dataPayment = null;

        if ($cardType === '001') {

            $dataPayment = $this->cyber_source_repository_interface->createPaymentRequestVisa(
                $data['clientReferenceInformation'],
                $data['orderInformation']['amountDetails'],
                $data['orderInformation']['billTo'],
                $data['response']['consumerAuthenticationInformation'],
                $json['jti'],
                $data['expirationMonth'],
                $data['expirationYear'],
            );

        }else if($cardType === '002'){

            $dataPayment = $this->cyber_source_repository_interface->createPaymentRequestMaster(
                $data['clientReferenceInformation'],
                $data['orderInformation']['amountDetails'],
                $data['orderInformation']['billTo'],
                $data['response']['consumerAuthenticationInformation'],
                $json['jti'],
                $data['expirationMonth'],
                $data['expirationYear'],
            );

        }

        $merchantConfig = $this->merchantConfigObject();

        $config = $this->connectionHost($merchantConfig);

        $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

        $api_instance = $this->cyber_source_repository_interface->paymentsApi($apiClient);

        list($response, $statusCode, $httpHeader) = $api_instance->createPayment($dataPayment);

        if( $statusCode == 201) {
            PayerAuthentication::where('code', $data['clientReferenceInformation']['code'])
            ->update([
                'payment_payload' => $data['response'],
                'payment_response' => json_decode($response, true),
            ]);
        }

        return json_decode ($response, true);
    }

    function clientAuthentication(array $data)
    {
        $validateAuthentication = $this->validateAuthentication($data);

        if ($validateAuthentication['status'] == 'AUTHENTICATION_SUCCESSFUL') {
            PayerAuthentication::where('transaction_id', $data['TransactionId'])
            ->update([
                'challenge_response' => $validateAuthentication,
            ]);
        }

        $htmlResponse = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Challenge completado</title>
            <script>
                (function() {
                    window.parent.postMessage({
                        MessageType: "CHALLENGE_COMPLETED",
                        Response: {$this->safeJsonEncode($validateAuthentication)}
                    }, "*");
                })();
            </script>
        </head>
        <body>
            <noscript>El proceso de autenticación ha finalizado.</noscript>
        </body>
        </html>
        HTML;

        return response($htmlResponse)->header('Content-Type', 'text/html');
    }

    function decodeJwt($jwt) {
        list($header, $payload, $signature) = explode('.', $jwt);
        $decoded = base64_decode(strtr($payload, '-_', '+/'));
        return json_decode($decoded, true);
    }

    function safeJsonEncode($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        return str_replace('</script>', '<\/script>', $json);
    }
}
