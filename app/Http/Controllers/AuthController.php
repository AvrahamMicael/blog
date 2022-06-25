<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $req)
    {
        $data = $req->all();
        $data['password'] = bcrypt($req->password);

        $user = User::create($data);
        $token = $user->createToken('main')->plainTextToken;
        
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $req)
    {
        $credentials = $req->except('remember');
        $remember = $req->remember ?? false;

        if(!auth()->attempt($credentials, $remember))
        {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }

        $user = auth()->user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
}
