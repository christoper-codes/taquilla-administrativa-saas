<?php

namespace App\Services;

use App\Repositories\ReasonAgreementRepository;

class ReasonAgreementService
{
     /*
    * |--------------------------------------------------------------------------
    * | ReasonAgreementService the repository services by Christoper Patiño
    */
    protected $reason_agreement_repository;

    public function __construct(ReasonAgreementRepository $reason_agreement_repository)
    {
        $this->reason_agreement_repository = $reason_agreement_repository;
    }

     /*
    * |--------------------------------------------------------------------------
    * | Get all ReasonAgreements
    */
    public function getAllByStadium($id)
    {
        try {

            $reason_agreements = $this->reason_agreement_repository->getAllByStadium($id);

            return $reason_agreements;

        } catch (\Exception $e) {

            throw $e;
        }
    }


}
