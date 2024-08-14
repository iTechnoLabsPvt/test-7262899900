<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    /**
     * Authentication - Authenticate user
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required']
        ];
        $response = ['success' => false];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return response($response, 400);
        } else {

            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                $response['message'] = 'The provided credentials are incorrect.';

                return response($response, 400);
            }
            $token = $user->createToken('apiToken')->plainTextToken;
            $response['success'] = true;
            $response['message'] = 'User loggedin';
            $response['data'] = ['user' => $user, 'token' => $token];
        }
        return response($response, 200);
    }

    /**
     * Logout - Distroy Session
     */
    public function logout(Request $request){
        $response = [];
        $request->user()->tokens()->delete();
        $response['message'] = 'Logout successfully.';

        return response($response, 200);
    }
}
