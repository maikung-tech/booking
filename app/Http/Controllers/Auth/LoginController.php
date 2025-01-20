<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt(array($fieldType => $request['username'], 'password' => $request['password']))) {
            // auth()->logoutOtherDevices($request['password']);
            $user = User::find(Auth::user()->id);
            $token = $user->createToken('access_token')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to authenticate.',
        ], 401);
    }
}
