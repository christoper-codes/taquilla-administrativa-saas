<?php

namespace App\Repositories;

use App\Interfaces\CyberSourceRepositoryInterface;
use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\ApiClient;
use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;
use CyberSource\Logging\LogConfiguration;
use CyberSource\Model\GenerateCaptureContextRequest;
use CyberSource\Model\PayerAuthSetupRequest;
use CyberSource\Api\PayerAuthenticationApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\Model\CheckPayerAuthEnrollmentRequest;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsConsumerAuthenticationInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCard;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use CyberSource\Model\Ptsv2paymentsTokenInformation;
use CyberSource\Model\ValidateRequest;

class CyberSourceRepository implements CyberSourceRepositoryInterface
{
    public function generateCaptureContextRequest(array $data)
    {
        return new GenerateCaptureContextRequest($data);
    }

    public function merchantConfigObject($mapping, $logConfiguration)
    {
        $config = new MerchantConfiguration();

        foreach ($mapping as $entry) {
            if (method_exists($config, $entry['method'])) {
                $config->{$entry['method']}($entry['value']);
            }
        }

        if($logConfiguration){
            $config->setLogConfiguration($logConfiguration);
        }

        $config->validateMerchantData();

        return $config;
    }

    public function connectionHost($merchantConfig)
    {
        $config = new Configuration();
        $config->setHost($merchantConfig->getHost());
        $config->setLogConfiguration($merchantConfig->getLogConfiguration());
        return $config;
    }

    public function apiClient($config, $merchantConfig)
    {
        return new ApiClient($config, $merchantConfig);
    }

    public function microformIntegrationApi($apiClient)
    {
        return new MicroformIntegrationApi($apiClient);
    }

    public function logConfiguration($mapping)
    {
        $logConfiguration = new LogConfiguration();

        foreach ($mapping as $entry) {
            if (method_exists($logConfiguration, $entry['method'])) {
                $logConfiguration->{$entry['method']}($entry['value']);
            }
        }

        return $logConfiguration;
    }

    public function payerAuthSetupRequest(array $data)
    {
        return new PayerAuthSetupRequest($data);
    }

    public function payerAuthenticationApi(ApiClient $apiClient)
    {
        return new PayerAuthenticationApi($apiClient);
    }

    public function checkPayerAuthEnrollmentRequest(array $data)
    {
        return new CheckPayerAuthEnrollmentRequest($data);
    }

    public function validateRequest(array $data)
    {
        return new ValidateRequest($data);
    }

    public function paymentsApi(ApiClient $apiClient)
    {
        return new PaymentsApi($apiClient);
    }

    public function createPaymentRequestVisa(Array $clientReferenceInformation, Array $amountDetails, Array $billTo, Array $consumerAuthenticationInformation, string $jti, string $expirationMonth, string $expirationYear)
    {
        return new CreatePaymentRequest([
            "clientReferenceInformation" => new Ptsv2paymentsClientReferenceInformation($clientReferenceInformation),
            "processingInformation" => new Ptsv2paymentsProcessingInformation([
                "capture"=> true,
                "commerceIndicator"=> $consumerAuthenticationInformation['ecommerceIndicator'] ?? $consumerAuthenticationInformation['indicator'],
            ]),
            "orderInformation" => new Ptsv2paymentsOrderInformation([
                "amountDetails" => new Ptsv2paymentsOrderInformationAmountDetails($amountDetails),
                "billTo" => new Ptsv2paymentsOrderInformationBillTo($billTo)
            ]),
            "consumerAuthenticationInformation" => new Ptsv2paymentsConsumerAuthenticationInformation([
                'cavv' => $consumerAuthenticationInformation['cavv'],
                'eciRaw' => $consumerAuthenticationInformation['eciRaw'],
                'xid' => $consumerAuthenticationInformation['xid'],
                'directoryServerTransactionId' => $consumerAuthenticationInformation['directoryServerTransactionId'],
            ]),
            "tokenInformation" => new Ptsv2paymentsTokenInformation([
                "jti" => $jti
            ]),
            "paymentInformation" => new Ptsv2paymentsPaymentInformation([
                "card" => new Ptsv2paymentsPaymentInformationCard([
                    "expirationMonth" => $expirationMonth,
                    "expirationYear" => $expirationYear
                ])
            ]),
        ]);
    }

    public function createPaymentRequestMaster(Array $clientReferenceInformation, Array $amountDetails, Array $billTo, Array $consumerAuthenticationInformation, string $jti, string $expirationMonth, string $expirationYear)
    {
        return new CreatePaymentRequest([
            "clientReferenceInformation" => new Ptsv2paymentsClientReferenceInformation($clientReferenceInformation),
            "processingInformation" => new Ptsv2paymentsProcessingInformation([
                "capture"=> true,
                "commerceIndicator"=> $consumerAuthenticationInformation['ecommerceIndicator'] ?? $consumerAuthenticationInformation['indicator']
            ]),
            "orderInformation" => new Ptsv2paymentsOrderInformation([
                "amountDetails" => new Ptsv2paymentsOrderInformationAmountDetails($amountDetails),
                "billTo" => new Ptsv2paymentsOrderInformationBillTo($billTo)
            ]),
            "consumerAuthenticationInformation" => new Ptsv2paymentsConsumerAuthenticationInformation([
                'ucafCollectionIndicator' => $consumerAuthenticationInformation['ucafCollectionIndicator'],
                'ucafAuthenticationData' => $consumerAuthenticationInformation['ucafAuthenticationData'],
            ]),
            "tokenInformation" => new Ptsv2paymentsTokenInformation([
                "jti" => $jti
            ]),
            "paymentInformation" => new Ptsv2paymentsPaymentInformation([
                "card" => new Ptsv2paymentsPaymentInformationCard([
                    "expirationMonth" => $expirationMonth,
                    "expirationYear" => $expirationYear
                ])
            ]),
        ]);
    }
}
