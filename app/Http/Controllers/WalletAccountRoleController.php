<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\WalletAccountRoleService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class WalletAccountRoleController extends Controller
{
   private $walletAccountRoleService;

    public function __construct(WalletAccountRoleService $walletAccountRoleService)
    {
         $this->walletAccountRoleService = $walletAccountRoleService;
    }

    /*
    *
    * Get all Wallet Account Roles by Christoper Patiño
    *
    */
    public function index()
    {
        try {

            $data = $this->walletAccountRoleService->getAll();

            return response()->json($data, 200);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar la cuenta');
        }
    }

    /*
    *
    * Create Wallet Account Role by Christoper Patiño
    *
    */
    public function store(Request $request)
    {
        try {

            $data = $this->walletAccountRoleService->storeWalletAccountRole($request->all());

            return response()->json($data, 200);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar la cuenta');
        }
    }
}
