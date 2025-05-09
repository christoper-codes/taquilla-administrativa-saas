<?php

namespace App\Services;

use App\Interfaces\CyberSourceRepositoryInterface;

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
            'keyFilename'        => ['method' => 'setKeyFileName',    'value' => 'testrest'],
            'keyAlias'           => ['method' => 'setKeyAlias',    'value' => 'testrest'],
            'keyPass'            => ['method' => 'setKeyPassword',    'value' => 'testrest'],
            'useMetaKey'         => ['method' => 'setUseMetaKey',    'value' => false],
            'portfolioID'        => ['method' => 'setPortfolioID',    'value' => ''],
            'keyDirectory'       => ['method' => 'setKeysDirectory',    'value' => __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Resources/"],
            'jwePEMFileDirectory'             => ['method' => 'setJwePEMFileDirectory',    'value' => "Resources/NetworkTokenCert.pem"],
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

    public function paymentWithFlexTransientToken(array $data)
    {
            $clientReferenceInformation = $this->cyber_source_repository_interface->clientReferenceInformation($data['clientReferenceInformation']);

            $orderInformationAmountDetails = $this->cyber_source_repository_interface->orderInformationAmountDetails($data['orderInformationAmountDetails']);

            $orderInformationBillTo = $this->cyber_source_repository_interface->orderInformationBillTo($data['orderInformationBillTo']);

            $orderInformation = $this->cyber_source_repository_interface->orderInformation([
                "amountDetails" => $orderInformationAmountDetails,
			    "billTo" => $orderInformationBillTo
            ]);

            $tokenInformation = $this->cyber_source_repository_interface->tokenInformation([
                'transientTokenJwt' => $data['tokenInformation']
            ]);

            $createPayment = $this->cyber_source_repository_interface->createPayment([
                "clientReferenceInformation" => $clientReferenceInformation,
                "orderInformation" => $orderInformation,
                "tokenInformation" => $tokenInformation
            ]);

            $merchantConfig = $this->merchantConfigObject();

            $config = $this->connectionHost($merchantConfig);

            $apiClient = $this->cyber_source_repository_interface->ApiClient($config, $merchantConfig);

            $api_instance = $this->cyber_source_repository_interface->paymentsApi($apiClient);

            list($response, $statusCode, $httpHeader) = $api_instance->createPayment($createPayment);

            return json_decode ($response, true);

    }
}
