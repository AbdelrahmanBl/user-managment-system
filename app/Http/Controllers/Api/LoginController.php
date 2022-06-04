<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if($user) {
            auth()->user()->setLoginInfo();
            $status = 401;
            $msg = "Authorized";
            $data = [
                'token' => auth()->user()->createToken('api')->plainTextToken
            ];
        }else {
            $status = 401;
            $msg = "Unauthorized";
            $data = [];
        }

        return response()->json([
            'message' => $msg,
            'data' => $data,
        ] ,$status);
    }
}
