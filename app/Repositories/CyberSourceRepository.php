<?php

namespace App\Repositories;

use App\Interfaces\CyberSourceRepositoryInterface;
use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\ApiClient;
use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;
use CyberSource\Logging\LogConfiguration;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\GenerateCaptureContextRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsTokenInformation;

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

    public function clientReferenceInformation($data)
    {
        return new Ptsv2paymentsClientReferenceInformation($data);
    }

    public function orderInformationAmountDetails($data)
    {
        return new Ptsv2paymentsOrderInformationAmountDetails($data);
    }

    public function orderInformationBillTo($data)
    {
        return new Ptsv2paymentsOrderInformationBillTo($data);
    }

    public function orderInformation($data)
    {
        return new Ptsv2paymentsOrderInformation($data);
    }

    public function tokenInformation($data)
    {
        return new Ptsv2paymentsTokenInformation($data);
    }

    public function createPayment($data)
    {
        return new CreatePaymentRequest($data);
    }

    public function paymentsApi($api_client)
    {
        return new PaymentsApi($api_client);
    }
}
