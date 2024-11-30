<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        try{
            
            $user= User::create([
                'name'=>$validated['name'],
                'email'=>$validated['email'],
                'password'=>$validated['password'],
            ]);    

        }catch(\Exception $e){
            return response()->json([
                'message'=>'failed',
                'user'=>$e->getMessage()
            ]);
        }
        return response()->json([
            'message'=>'success',
            'user'=>$user
        ]);
    }
    public function login(Request $request){
        try{
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6'
            ]);
            if(!Auth::attempt($request->only('email','password'))){
                return response()->json([
                    'status'=>'false',
                    'message'=>'Unauthorized',
                ],403);
            }
    
            $user= User::where('email', $validated['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
    
        }catch(\Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>$e->getMessage()
            ],201);
        }
        return response()->json([
            'status'=>'success',
            'token'=>$token,
        ],201);

    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status'=>'success',
        ],201);
    }
}
