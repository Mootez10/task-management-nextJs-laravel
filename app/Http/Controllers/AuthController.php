<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields= $request->validate([
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);
        $user = User::create($fields);
        
        return [
            'user' => $user,
            
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email', //provide an error if the user mail doesnt exixst in the usertab donc kif temchi besh tlogini w tbadel l email ykoli the sleelcted emauil does not exist jeya mn users exists
            'password'=>'required'
        ]);
        $user = User::where('email',$request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)){
            return ['message' => 'the provided credentials are incorrect']; // w hne ken tbadel haja f password ykolik heka 
        }

        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();//trajaalik all the tokens mtaa user heka w tfassakhhom 

        return [
            'message' => 'You are logged out'
        ];
    }
}
