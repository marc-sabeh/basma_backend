<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogOutController extends Controller
{

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        try{
            auth()->logout($token);

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
        } catch(JWTException $exception){
            return response()->json([
                "status" => false,
                "message" => "The user cannot be logged out"
            ]);
        }
    }
}
