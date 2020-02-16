<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\UserRegistered;
// use App\Models\User;
use App\Models\MongoDB\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Mail, Validator};

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
            return response()->json(["errors" => $validator->errors()->all()], 422);
        }

        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->toArray());
        Mail::to($request["email"])->send(new UserRegistered());
        return response()->json([
            "success" => "User registered successfully!",
            "token" => $user->createToken('Laravel Password Grant Client')->accessToken
        ], 200);
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
                return response()->json([
                    "success" => "Used logged in successfully!",
                    "token" => $token
                ], 200);
            } else {
                return response()->json(["error" => "Password missmatch!"], 422);
            }
        } else {
            return response()->json(["error" => "User does not exist!"], 422);
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
        return response()->json(["success" => "You have been succesfully logged out!"], 200);
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
            return response()->json(["errors" => $validator->errors()->all()], 422);
        }

        if ($request->old_password == $request->new_password) {
            return response()->json(["error" => "New password must be different from old one!"], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                return ($user->save()) 
                    ? response()->json(["success" => "Password changed successfully!"], 200)
                    : response()->json(["error" => "Unable to change password!"], 404);
            } else {
                return response()->json(["error" => "Old password missmatch!"], 422);
            }
        } else {
            return response()->json(["error" => "User does not exist!"], 422);
        }
    }
}
