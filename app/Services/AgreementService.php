<?php

namespace App\Services;

use App\Interfaces\AgreementRepositoryInterface;

class AgreementService
{
    /*
    * |--------------------------------------------------------------------------
    * | AgreementService the repository services for global module by Christoper Patiño
    */

    protected $agreement_repository_interface;

    public function __construct(AgreementRepositoryInterface $agreement_repository_interface)
    {
        $this->agreement_repository_interface = $agreement_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all agreement catalogues
    */
    public function getAll()
    {
        $agreements = $this->agreement_repository_interface->getAll();

        return $agreements;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by id
    */
    public function getById($id)
    {
        try {

            $agreement = $this->agreement_repository_interface->getById($id);

            return $agreement;

        } catch (\Exception $e) {

            throw $e;

        }
    }


    /*
    * |--------------------------------------------------------------------------
    * | Save new agreement catalogue
    */
    public function save(array $data)
    {
        try {

            $agreement = $this->agreement_repository_interface->save($data);

            return $agreement;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update agreement catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $agreement = $this->agreement_repository_interface->update($id, $data);

            return $agreement;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete agreement catalogue
    */
    public function delete(int $id)
    {
        try {

            $agreement = $this->agreement_repository_interface->delete($id);

            return $agreement;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
