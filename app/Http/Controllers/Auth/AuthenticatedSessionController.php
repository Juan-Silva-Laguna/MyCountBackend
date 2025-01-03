<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {              
        $credentials = $request->only('email', 'password');
        try {            
            if (! $token = JWTAuth::attempt($credentials)) {
                
                return response()->json(['message' => 'Credenciales Invalidas'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'No es posible realizar su ingreso'], 500);
        }

        $user = User::where('email', $request->email)->first();  
        $name = $user->name;   
        $gender = $user->gender;  
        $message = 'Bienvenid'.($gender=='M'?'o':'a').' Señor'.($gender=='M'?'':'a').' '.$name;        
        
        return response()->json(compact('token', 'name', 'gender', 'message'));

        //$request->authenticate(); 
        //$request->session()->regenerate();
        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        //Auth::guard('web')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
    
        Auth::logout();
        
        return response()->json(['message' => 'Session Cerrada']);
    }
}
