<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4'
        ]);

        if($validate->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validate->errors()
            ]);
        };

        $user = User::create($validate->validate());
        return response()->json([
            'message' => 'Пользователь успешно зарегистрован',
            "user" => $user
        ]);
    }

    public function login(Request $request) {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validate->errors()
            ]);
        };

        if(Auth::attempt($validate->validate())) {
            Auth::user()->tokens()->delete();
            $access_token = Auth::user()->createToken('api')->plainTextToken;
            return response()->json([
                'access_token' => $access_token
            ]);
        }

        return response()->json([
            'message' => 'Login failed'
        ]);

    }
}
