<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request) {
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:8',
        ],
        [
            'required' => 'The :attribute field is required.',
            'min' => 'The :attribute field must be more than 8 characters.',
            'unique' => 'The :attribute field is already being used.',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=>$validator->errors()->all()
             ]);
        } else {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password)
            ]);
    
            return response()->json([
                "status" => "ok",
                "message" => "Register succesfully!"
            ]);
        }
    }

    public function login(Request $request) {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)){
            $token = Auth::user()->createToken('myapptoken')->plainTextToken;

            return response()->json([
                "status" => "ok",
                "token" => $token
            ]);
        }
        return response()->json([
            "status" => "error",
            "message" => "Usuario y/o contraseña incorrectos"
        ]);
    }

    public function logout() {
        Auth::user()->tokens()->each(function($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message'=>'You have successfully logged out.',
            'status'=>'ok'
        ]);
    }
}
