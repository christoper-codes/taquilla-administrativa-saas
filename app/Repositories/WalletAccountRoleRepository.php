<?php

namespace App\Repositories;

use App\Interfaces\WalletAccountRoleRepositoryInterface;
use App\Models\WalletAccountRole;

class WalletAccountRoleRepository implements WalletAccountRoleRepositoryInterface
{
    public function getAll()
    {
        return WalletAccountRole::all();
    }

    public function find($id)
    {
        return WalletAccountRole::findOrFail($id);
    }

    public function create(array $data)
    {
        return WalletAccountRole::create($data);
    }

    public function update(array $data, $id)
    {
        $walletAccountRole = WalletAccountRole::find($id);
        return $walletAccountRole->update($data);
    }

    public function delete($id)
    {
        return WalletAccountRole::destroy($id);
    }
}
