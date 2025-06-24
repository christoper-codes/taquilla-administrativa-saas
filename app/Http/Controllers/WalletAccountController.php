<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\RedirectResponse;
use App\Services\WalletAccountService;
use App\Helpers\WebResponseHelper;
use App\Services\GlobalSeasonService;
use App\Services\SeasonTicketWalletAccountService;
use App\Services\WalletAccountRoleService;
use App\Services\WalletAccountTypeService;
use App\Services\WalletAccountWalletAccountTypeService;
use Carbon\Carbon;
use Inertia\Inertia;

class WalletAccountController extends Controller
{
    protected $wallet_account_type_service;
    protected $wallet_account_service;
    protected $global_season_service;
    protected $season_ticket_wallet_account_service;
    protected $wallet_account_wallet_account_type_service;
    protected $wallet_account_role_service;

    public function __construct(WalletAccountTypeService $wallet_account_type_service, WalletAccountService $wallet_account_service,
                                GlobalSeasonService $global_season_service, SeasonTicketWalletAccountService $season_ticket_wallet_account_service,
                                WalletAccountWalletAccountTypeService $wallet_account_wallet_account_type_service, WalletAccountRoleService $wallet_account_role_service)
    {
        $this->wallet_account_type_service = $wallet_account_type_service;
        $this->wallet_account_service = $wallet_account_service;
        $this->global_season_service = $global_season_service;
        $this->season_ticket_wallet_account_service = $season_ticket_wallet_account_service;
        $this->wallet_account_wallet_account_type_service = $wallet_account_wallet_account_type_service;
        $this->wallet_account_role_service = $wallet_account_role_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');

            $wallet_account_types = $this->wallet_account_type_service->getAll();
            $wallet_accounts = $this->wallet_account_service->getAll(false);
            $global_seasons = $this->global_season_service->getAll();
            $wallet_account_role = $this->wallet_account_role_service->getAll();

            return Inertia::render('App/Administration/Wallet/Wallets', [
                'user' => $user,
                'wallet_account_roles' => $wallet_account_role,
                'wallet_account_types' => $wallet_account_types,
                'wallet_accounts' => $wallet_accounts,
                'global_seasons' => $global_seasons
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los monederos');
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'season_ticket_id' => 'nullable|exists:season_tickets,id',
            'user_id' => 'nullable|exists:users,id',
            'wallet_account_type_id' => 'required|exists:wallet_account_types,id',
            'role_type_id' => 'required',
            'current_balance' => 'required',
            'account_number' => 'nullable|string',
            'expiration_date' => 'required|date',
        ]);

        try {


            $user_id = $request->user_id;
            $season_ticket_id = $request->season_ticket_id;
            $wallet_account_type_id = $request->wallet_account_type_id;

            $validateTypeAccount = function($wallet_account, $wallet_account_type_id) {

                $exist_wallet_account_type = $wallet_account->walletAccountTypes()->wherePivot('wallet_account_type_id', $wallet_account_type_id)->first();

                if ($exist_wallet_account_type) {

                    return WebResponseHelper::sendResponse($wallet_account, "Ya existe este tipo de cuenta", null, false);
                }
            };

            $addWalletAccountType = function($request, $wallet_account) {

                /**
                * Vinculación de cuenta a el tipo
                */
                    $request->merge([
                        'wallet_account_id' => $wallet_account->id,
                        'expiration_date' => Carbon::parse($request->expiration_date)->format('Y-m-d'),
                    ]);

                    $this->wallet_account_wallet_account_type_service->save($request->only([
                        'wallet_account_id',
                        'wallet_account_type_id',
                        'current_balance',
                        'expiration_date'
                    ]));
            };

            $createdWalletAccount = function($request, $dataForWalletAccount){

                $request->merge([
                    'expiration_date' => Carbon::parse($request->expiration_date)->format('Y-m-d'),
                    'account_number' => $request->account_number ?? $this->wallet_account_service->generateUniqueAccountNumber()
                ]);

                $wallet_account = $this->wallet_account_service->save($request->only($dataForWalletAccount));

                $request->merge(['wallet_account_id' => $wallet_account->id ]);

                $this->wallet_account_wallet_account_type_service->save($request->only([
                    'wallet_account_id',
                    'wallet_account_type_id',
                    'current_balance',
                    'expiration_date'
                ]));

                return $wallet_account;
            };

            $addPrivilege = function($request,$wallet_account){
                if($request->get("name") && $request->get("code") && $request->get("percentage_cashback") && $request->get("description")){
                    $wallet_account->walletAccountPrivilegeHistories()->create([
                        'name' => $request->get("name"),
                        'code' => $request->get("code"),
                        'percentage_cashback' => $request->get("percentage_cashback"),
                        'description' => $request->get("description"),
                    ]);
                }
            };

            $addRole = function($request, $wallet_account){
                    $wallet_account->walletAccountRole()->attach($request->get("role_type_id"));
            };

            if ($season_ticket_id) {

                $season_ticket_wallet_account = $this->season_ticket_wallet_account_service->findForSubscriber($season_ticket_id);

                if ($season_ticket_wallet_account->count()) {

                    foreach ($season_ticket_wallet_account as $wallet_account) {
                        $responseValidateTypeAccount = $validateTypeAccount($wallet_account, $wallet_account_type_id);
                        if ($responseValidateTypeAccount instanceof RedirectResponse) {
                            return $responseValidateTypeAccount;
                        }
                    }

                    if(filled($request->get("account_number"))){

                        $season_ticket_wallet_account = $createdWalletAccount($request, ['account_number']);

                        $addPrivilege($request,$season_ticket_wallet_account);
                        $addRole($request,$season_ticket_wallet_account);

                        $this->season_ticket_wallet_account_service->saveForSubscriber($season_ticket_wallet_account->id, $request->only(['season_ticket_id']));

                    }else{
                        $addWalletAccountType($request, $season_ticket_wallet_account->first());
                    }

                }else{
                    $season_ticket_wallet_account = $createdWalletAccount($request, ['account_number']);

                    $addPrivilege($request,$season_ticket_wallet_account);
                    $addRole($request,$season_ticket_wallet_account);

                    $this->season_ticket_wallet_account_service->saveForSubscriber($season_ticket_wallet_account->id, $request->only(['season_ticket_id']));
                }

                return WebResponseHelper::sendResponse($season_ticket_wallet_account, "Cuenta de abonado registrada con éxito", null, false);

            }

            if ($user_id) {

                $user_wallet_account = $this->season_ticket_wallet_account_service->findForUser($user_id);

                if ($user_wallet_account->count()) {

                    foreach ($user_wallet_account as $wallet_account) {
                        $responseValidateTypeAccount = $validateTypeAccount($wallet_account, $wallet_account_type_id);
                        if ($responseValidateTypeAccount instanceof RedirectResponse) {
                            return $responseValidateTypeAccount;
                        }
                    }

                    if(filled($request->get("account_number"))){

                        $user_wallet_account = $createdWalletAccount($request, ['user_id','account_number']);

                        $addPrivilege($request,$user_wallet_account);
                        $addRole($request,$user_wallet_account);

                    }else{
                        $addWalletAccountType($request, $user_wallet_account->first());
                    }
                }else{
                    $user_wallet_account = $createdWalletAccount($request, ['user_id','account_number']);

                    $addPrivilege($request,$user_wallet_account);
                    $addRole($request,$user_wallet_account);
                }

                return WebResponseHelper::sendResponse($user_wallet_account, "Cuenta de usuario registrada con éxito", null, false);
            }

            if (blank($season_ticket_id) && blank($user_id)) {

                if (filled($request->get("account_number"))) {

                    $wallet_account = $this->wallet_account_service->getByAccountNumber($request->get("account_number"));

                    if($wallet_account){
                        $responseValidateTypeAccount = $validateTypeAccount($wallet_account, $wallet_account_type_id);
                        if ($responseValidateTypeAccount instanceof RedirectResponse) {
                            return $responseValidateTypeAccount;
                        }

                        $addWalletAccountType($request, $wallet_account);
                    }else{
                        $wallet_account = $createdWalletAccount($request, ['account_number']);

                        $addPrivilege($request,$wallet_account);
                        $addRole($request,$wallet_account);
                    }

                }else{
                    $wallet_account = $createdWalletAccount($request, ['account_number']);

                    $addPrivilege($request,$wallet_account);
                    $addRole($request,$wallet_account);
                }

                return WebResponseHelper::sendResponse($wallet_account, "Cuenta genérica registrada con éxito", null, false);
            }


        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar la cuenta');
        }
    }

    /**
     * Display the specified resource.
     */
    public function showByAccountNumber(Request $request)
    {
        $request->validate([ 'account_number' => 'required|exists:wallet_accounts,account_number']);

        try {
            $wallet_account = $this->wallet_account_service->getByAccountNumber($request->account_number, false);
             return response()->json([
                   'data' => [
                       'wallet_account' => $wallet_account,
                   ],
                    'message' => 'Cuenta encontrada con exito!',
                ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function showByUser(Request $request)
    {
        $request->validate([ 'id' => 'required|exists:users,id']);

        try {

            $wallet_account = $this->wallet_account_service->getByUser($request->id, false);

            if($wallet_account->count()){
                return response()->json([
                    'data' => [
                        'wallet_accounts' => $wallet_account
                    ],
                    'message' => 'Cuentas encontradas con éxito!',
                ], 200);
            }

             return response()->json([
                'data' => null,
                'message' => 'No se encontro cuentas relacionadas al usuario!',
            ], 404);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function showHistoryByAccountNumber(Request $request)
    {
        $request->validate(['account_number' => 'exists:wallet_accounts,account_number']);

        try {


            $wallet_account = $this->wallet_account_service->getHistoryByAccountNumber($request->account_number);

            return response()->json([
                'data' => [
                    'history' => $wallet_account
                ],
                'message' => 'Historial encontrado con éxito!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'is_active' => 'required|boolean',
            'expiration_date' => 'required|date',
        ]);

        try {


            $request->merge([
                'expiration_date' => Carbon::parse($request->expiration_date)->format('Y-m-d')
            ]);

            $wallet_account = $this->wallet_account_wallet_account_type_service->update($request->id, $request->only([
                'is_active',
                'expiration_date'
            ]));

            return WebResponseHelper::sendResponse($wallet_account, "Cuenta actualizado con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al actualizar la cuenta');

        }
    }
}
