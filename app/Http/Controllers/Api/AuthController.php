<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};

class AuthController extends Controller
{
    /**
     * Register new users.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->toArray());
        return response(['token' => $user->createToken('Laravel Password Grant Client')->accessToken], 200);
    }

    /**
     * Login for registered users.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response(['token' => $token], 200);
            } else {
                return response("Password missmatch!", 422);
            }
        } else {
            return response("User does not exist!", 422);
        }
    }

    /**
     * Logs registered users out.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response("You have been succesfully logged out!", 200);
    }
}
