<?php

namespace App\Services;

use App\Interfaces\InstitutionRepositoryInterface;

class InstitutionService
{
    /*
    * |--------------------------------------------------------------------------
    * | InstitutionService the repository services for global module by Christoper Patiño
    */

    protected $institution_repository_interface;

    public function __construct(InstitutionRepositoryInterface $institution_repository_interface)
    {
        $this->institution_repository_interface = $institution_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all institution catalogues
    */
    public function getAll()
    {
        $institutions = $this->institution_repository_interface->getAll();

        return $institutions;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new institution catalogue
    */
    public function save(array $data)
    {
        try {

            $institution = $this->institution_repository_interface->save($data);

            return $institution;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update institution catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $institution = $this->institution_repository_interface->update($id, $data);

            return $institution;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete institution catalogue
    */
    public function delete(int $id)
    {
        try {

            $institution = $this->institution_repository_interface->delete($id);

            return $institution;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
