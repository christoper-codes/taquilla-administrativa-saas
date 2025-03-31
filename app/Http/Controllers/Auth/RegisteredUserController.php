<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WebResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserGender;
use App\Services\GlobalImageService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    protected $global_image_service;
    protected $user_service;

    public function __construct(GlobalImageService $global_image_service, UserService $user_service)
    {
        $this->user_service = $user_service;
        $this->global_image_service = $global_image_service;
    }

    /**
     * Display the registration view.
     */
    public function create($slug = null, $id = null): Response
    {
        return Inertia::render('Auth/Register',[
            'slug' => $slug,
            'id' => $id,
        ]);
    }

    public function createUser(): Response
    {
        $user = Auth::user()->load('globalImages', 'userRoles');
        $users = $this->user_service->getAll()->load('userRoles');
        $roles = UserRole::all();
        return Inertia::render('App/Pos/CreateUser', [
            'user' => $user,
            'users' => $users,
            'roles' => $roles
        ]);

    }

    public function  createUserWithRoles(Request $request)
    {
        $idRoles = [];
        $data = [];
        $roles = $request->input('roles');

        foreach ($roles as $item) {

            array_push($idRoles, $item['id']);

        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
        ]);

        $request->merge([
            'stadium_id' => 1,
            'is_new_user' => true,
            'password' => 'HDX-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT),
        ]);

        $existUser = User::where('email', $request->email)->first();

        $user_gender = UserGender::where('name', $request->user_gender)->first();

        DB::beginTransaction();
        try {
                if($existUser) {

                    if($existUser->stadiums->contains($request->stadium_id)){
                        throw new \Exception('Opps! Ya existe un registro con este email o username para este club');
                    } else {
                        $request->merge([
                            'is_new_user' => false,
                        ]);
                    }
                }

                if($request->is_new_user){

                    $user = User::create([
                        'user_gender_id' => $user_gender->id ?? null,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'middle_name' => $request->middle_name,
                        'username' => $request->username,
                        'birthdate' => Carbon::parse($request->birthdate)->format('Y-m-d'),
                        'email' => $request->email,
                        'color' => 'blue',
                        'is_active' => true,
                        'password' => Hash::make($request->password),
                    ]);

                    if(!$request->user_rol_id){
                        $request->merge([
                            'user_rol_id' => $idRoles,
                        ]);
                    }

                    $user->userRoles()->attach($request->user_rol_id);

                    $user->stadiums()->attach($request->stadium_id);

                    $data = array_merge(
                        $data,
                        [
                            'email' => $request->email,
                            'password' => $request->password,
                            'username' => $request->username
                        ]
                    );

                    if($request->global_image){
                        $global_image = $this->global_image_service->save($request->all(), 'profile_images');
                        $user->globalImages()->attach($global_image->id);
                    }

                    event(new Registered($user));
                    DB::commit();

                    WebResponseHelper::sendResponse($data, 'Usuario registrado con éxito', 'dashboard', 200, true);

                }



        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponseHelper::rollback($e, 'Opps! Algo salió mal al registrar el usuario');
        }

    }

    public function updateRolesUser(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            $userRoles = $request->input('roles');
            $idRoles = [];

            foreach ($userRoles as $item) {

                array_push($idRoles, $item['id']);

            }

            $user->userRoles()->sync($idRoles);

            $roles = $user->userRoles()->get()->toArray();


            WebResponseHelper::sendResponse($roles, 'Usuario actualizado con éxito', 'dashboard', 200, true);
        } catch (\Exception $e) {
            return WebResponseHelper::rollback($e, 'Opps! Algo salió mal al registrar el usuario');
        }
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
            'password' => ['required', 'confirmed'],
        ]);

        $request->merge([
            'stadium_id' => 1,
            'is_new_user' => true,
        ]);

        /* $existUser = User::where('email', $request->email)
                        ->orWhere('username', $request->username)
                        ->first(); */
        $existUser = User::where('email', $request->email)->first();

        $user_gender = UserGender::where('name', $request->user_gender)->first();

        DB::beginTransaction();
        try {

            if($existUser) {

                if($existUser->stadiums->contains($request->stadium_id)){
                    throw new \Exception('Opps! Ya existe un registro con este email o username para este club');
                } else {
                    $request->merge([
                        'is_new_user' => false,
                    ]);
                }
            }

            if($request->is_new_user){

                $user = User::create([
                    'user_gender_id' => $user_gender->id ?? null,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'username' => $request->username,
                    'birthdate' => Carbon::parse($request->birthdate)->format('Y-m-d'),
                    'email' => $request->email,
                    'color' => 'blue',
                    'is_active' => true,
                    'password' => Hash::make($request->password),
                ]);

                if(!$request->user_rol_id){
                    $request->merge([
                        'user_rol_id' => 1,
                    ]);
                }

                $user->userRoles()->attach($request->user_rol_id);

                $user->stadiums()->attach($request->stadium_id);


                if($request->global_image){
                    $global_image = $this->global_image_service->save($request->all(), 'profile_images');
                    $user->globalImages()->attach($global_image->id);
                }

                event(new Registered($user));

                Auth::login($user);

                DB::commit();

                if($request->slug && $request->id) {
                    return redirect()->route('events.show', ['slug' => $request->slug, 'id' => $request->id]);
                }

                WebResponseHelper::sendResponse($user, 'Usuario registrado con éxito', 'dashboard', 200, true);

            }

            throw new \Exception('El usuario ya existe en el sistema');

        } catch (\Exception $e) {
            DB::rollBack();
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al registrar el usuario');
        }
    }
}
