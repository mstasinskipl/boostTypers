<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $validateData['password'] = bcrypt($validateData['password']);

        $user = User::create($validateData);

        $accessToken = null;
        if ($user instanceof User) {
            $accessToken = $user->createToken('authToken')->accessToken;
        }

        return response()->json(['user' => $user, 'accessToken' => $accessToken]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (! auth()->attempt($loginData)) {
            return response()->json(['message', 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(['user' =>  auth()->user(), 'accessToken' => $accessToken]);
    }
}
