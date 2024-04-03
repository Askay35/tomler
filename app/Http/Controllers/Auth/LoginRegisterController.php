<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginRegisterController extends Controller
{
     /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        
        event(new Registered($user));
        
        $response = [
            'status' => true,
            'token' => $user->createToken($request->email)->plainTextToken
        ];

        return response()->json($response, 200);
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // Check email exist
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'status' => false,
                'errors' =>[
                    'email'=>'Пользователь с таким email не зарегистрирован'
                ]
            ], 401);
        }
        // Check password
        if(!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'errors' =>[
                    'password'=>'Неверный пароль'
                ]
            ], 401);            
        }

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;
        
        $response = [
            'status' => true,
            'data' => $data,
        ];

        return response()->json($response);
    } 

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            ], 200);
    }    
}