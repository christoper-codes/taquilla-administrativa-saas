<?php

namespace App\Services;

use App\Interfaces\StadiumRepositoryInterface;

class StadiumService
{
    /*
    * |--------------------------------------------------------------------------
    * | StadiumService the repository services for global module by Christoper Patiño
    */

    protected $stadium_repository_interface;

    public function __construct(StadiumRepositoryInterface $stadium_repository_interface)
    {
        $this->stadium_repository_interface = $stadium_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all stadium catalogues
    */
    public function getAll()
    {
        $stadiums = $this->stadium_repository_interface->getAll();

        return $stadiums;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new stadium catalogue
    */
    public function save(array $data)
    {
        try {

            $stadium = $this->stadium_repository_interface->save($data);

            return $stadium;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update stadium catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $stadium = $this->stadium_repository_interface->update($id, $data);

            return $stadium;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete stadium catalogue
    */
    public function delete(int $id)
    {
        try {

            $stadium = $this->stadium_repository_interface->delete($id);

            return $stadium;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
