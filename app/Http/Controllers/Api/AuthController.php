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

    /**
     * Change password of registered users.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->old_password == $request->new_password) {
            return response("New password must be different from old one!", 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                return ($user->save()) 
                    ? response("Password changed successfully!", 200)
                    : response("Unable to change password!", 404);
            } else {
                return response("Old password missmatch!", 422);
            }
        } else {
            return response("User does not exist!", 422);
        }
    }
}
