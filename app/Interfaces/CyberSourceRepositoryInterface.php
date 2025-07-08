<?php

namespace App\Interfaces;

use CyberSource\ApiClient;
use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;
use CyberSource\Logging\LogConfiguration;

interface CyberSourceRepositoryInterface
{
    public function generateCaptureContextRequest(Array $data);
    public function merchantConfigObject(Array $data, LogConfiguration $logConfiguration);
    public function connectionHost(MerchantConfiguration $merchantConfig);
    public function apiClient(Configuration $config, MerchantConfiguration $merchantConfig);
    public function microformIntegrationApi(ApiClient $apiClient);
    public function logConfiguration(Array $data);
    public function payerAuthSetupRequest(Array $data);
    public function payerAuthenticationApi(ApiClient $apiClient);
    public function checkPayerAuthEnrollmentRequest(Array $data);
    public function validateRequest(Array $data);
    public function paymentsApi(ApiClient $apiClient);
    public function createPaymentRequestVisa(Array $clientReferenceInformation, Array $amountDetails, Array $billTo, Array $consumerAuthenticationInformation, string $jti, string $expirationMonth, string $expirationYear);
    public function createPaymentRequestMaster(Array $clientReferenceInformation, Array $amountDetails, Array $billTo, Array $consumerAuthenticationInformation, string $jti, string $expirationMonth, string $expirationYear);
}
