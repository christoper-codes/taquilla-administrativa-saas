<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return User::all();
    }

    public function getById($id)
    {
        return User::findOrfail($id);
    }

    public function save(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        return User::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
