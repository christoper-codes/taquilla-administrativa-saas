<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserGender;
use App\Services\GlobalImageService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $global_image_service;
    protected $user_service;

    public function __construct(GlobalImageService $global_image_service, UserService $user_service)
    {
        $this->user_service = $user_service;
        $this->global_image_service = $global_image_service;
    }

    public function register(Request $request)
    {

        DB::beginTransaction();
        try {

            $request->validate([
                'user_gender_id' => 'required',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'birthdate' => 'required|date_format:Y-m-d',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'global_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $request->merge([ 'stadium_id' => 1, 'is_new_user' => true]);

            $exist_user = User::where('email', $request->email)->first();
            if($exist_user) {
                if($exist_user->stadiums->contains($request->stadium_id)){
                    throw new \Exception('Ya existe un registro con este email o username para este club');
                } else {
                    $request->merge([
                        'is_new_user' => false,
                    ]);
                }
            }

            if($request->is_new_user){

                $user = User::create([
                    'user_gender_id' => $request->user_gender_id ?? null,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'username' => $request->username,
                    'birthdate' => Carbon::parse($request->birthdate)->format('Y-m-d'),
                    'email' => $request->email,
                    'color' => 'purple',
                    'is_active' => true,
                    'password' => Hash::make($request->password),
                ]);

                $user->userRoles()->attach($request->user_rol_id ?? 1);
                $user->stadiums()->attach($request->stadium_id ?? 1);
                $user->userRoles;

                if($request->global_image){
                    $global_image = $this->global_image_service->save($request->all(), 'profile_images');
                    $user->globalImages()->attach($global_image->id);
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                DB::commit();

                return response()->json([
                   'data' => [
                       'user' => $user,
                       'token' => $token,
                   ],
                    'message' => 'Usuario creado con éxito',
                ], 201);
            }

            throw new \Exception('El usuario ya existe en el sistema');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try{

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

       if(!Auth::attempt($request->only('email', 'password'))){
            throw new \Exception('Credenciales inválidas');
        }

        $user = Auth::user()->load(['userRoles']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Inicio de sesión exitoso',
        ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                'data' => [],
                'message' => 'Sesión cerrada con éxito',
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function userGenders()
    {
        try {
            $genders = UserGender::all();

            return response()->json([
                'data' => [
                    'user_genders' => $genders,
                ],
                'message' => 'Géneros de usuario recuperados con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function profile(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::with(['userGender', 'userRoles', 'globalImages', 'seasonTickets'])
                ->where('id', $request->user_id)
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'user' => $user,
                ],
                'message' => 'Usuario recuperado con éxito',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
