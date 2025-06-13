<?php

namespace App\Interfaces;

interface CyberSourceRepositoryInterface
{
    public function generateCaptureContextRequest(Array $data);
    public function merchantConfigObject(Array $data, $logConfiguration);
    public function connectionHost($merchantConfig);
    public function apiClient($config, $merchantConfig);
    public function microformIntegrationApi($apiClient);
    public function logConfiguration(Array $data);
    public function clientReferenceInformation(Array $data);
    public function orderInformationAmountDetails(Array $data);
    public function orderInformationBillTo(Array $data);
    public function orderInformation(Array $data);
    public function tokenInformation(Array $data);
    public function createPayment(Array $data);
    public function paymentsApi($apiClient);
}
