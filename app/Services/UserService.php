<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;

class UserService
{
    /*
    * |--------------------------------------------------------------------------
    * | AgreementService the repository services for global module by Christoper Patiño
    */

    protected $user_repository_interface;

    public function __construct(UserRepositoryInterface $user_repository_interface)
    {
        $this->user_repository_interface = $user_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all agreement catalogues
    */
    public function getAll()
    {
        $users = $this->user_repository_interface->getAll();

        return $users;
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by id
    */
    public function getById($id)
    {
        try {

            $user = $this->user_repository_interface->getById($id);

            return $user;

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

            $user = $this->user_repository_interface->save($data);

            return $user;

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

            $user = $this->user_repository_interface->update($id, $data);

            return $user;

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

            $user = $this->user_repository_interface->delete($id);

            return $user;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
