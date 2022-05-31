<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    //
    protected function login(Request $request) {
        $creds = $request->only(['email', 'password']);

        if (!$token=auth() -> attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid credintials'
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    protected function register(Request $request) {
        $encryptedPass = Hash::make($request->password);

        $user = new User;

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $encryptedPass;
            $user->saveOrFail();
            return $this->login($request);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th
            ]);
        }
    }

    protected function logout(Request $request) {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th
            ]);
        }
    }
}
