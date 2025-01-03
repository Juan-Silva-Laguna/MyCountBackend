<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();  
        
        if ($user) {
            return response()->json(['message' => 'El usuario ya se encuentra registrado'], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'mobile' => $request->mobile,
            ]);
        } catch (\Exception $th) {
            return response()->json(['message' => 'Por favor ingresa datos validos'], 400);
        }
        $message = 'Registro Exitoso Señor'.($user->gender=='M'?' ':'a ').$user->name;  
        return response()->json(['message' => $message]);
        // event(new Registered($user));
        // Auth::login($user);
        // return redirect(RouteServiceProvider::HOME);
        
    }
}
