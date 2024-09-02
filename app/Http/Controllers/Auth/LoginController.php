<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            return response()->json(['success' => $success], 200);

        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
