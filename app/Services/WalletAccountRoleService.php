<?php

namespace App\Services;

use App\Interfaces\WalletAccountRoleRepositoryInterface;
use App\Models\WalletAccountRole;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletAccountRoleService
{
    protected $walletAccountRoleRepository;

    public function __construct(WalletAccountRoleRepositoryInterface $walletAccountRoleRepository)
    {
        $this->walletAccountRoleRepository = $walletAccountRoleRepository;
    }

    public function getAll()
    {
        $walletAccountRoles = $this->walletAccountRoleRepository->getAll();

        return $walletAccountRoles;
    }

    public function storeWalletAccountRole(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'is_active' => 'required|boolean'
        ]);

        if($validator->fails()) {
              throw new ValidationException($validator->errors()->first());
        }

        $walletAccountRoleName = str_replace(' ', '_', strtolower($data['name']));
        $existName = WalletAccountRole::where('name', $walletAccountRoleName)->first();

        if($existName) {
            throw new \Exception('El rol de cuenta ya existe');
        }

        $data['name'] = $walletAccountRoleName;

        DB::beginTransaction();

        try {

            $walletAccountRole = $this->walletAccountRoleRepository->create($data);

            DB::commit();

            return $walletAccountRole;

        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
