<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WebResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGender;
use App\Services\GlobalImageService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    protected $global_image_service;

    public function __construct(GlobalImageService $global_image_service)
    {
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
