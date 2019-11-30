<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users data.
     *
     * @param
     *
     * @return response
     */
    public function getUsers()
    {
        if (DB::select("SELECT id, name, email, created_at, updated_at FROM users")) {
            return response(DB::select("SELECT * FROM users"), 200);
        } else {
            return response("No users found!", 404);
        }
    }

    /**
     * Finds a user using entered id.
     *
     * @param int $id  user id
     *
     * @return response
     */
    public function getUser($id)
    {
        if (DB::select("SELECT * FROM users WHERE id=?", [$id])) {
            return response(DB::select("SELECT * FROM users WHERE id=?", [$id]), 200);
        } else {
            return response("No users found!", 404);
        }
    }

    /**
     * Creates a new user.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function addUser(Request $request)
    {
        $data = $request->json()->all();
        if ($data["password"]["first"] == $data["password"]["second"]) {
            if (DB::insert("INSERT INTO users (name, email, email_verified_at, password, api_token, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)", [$data["name"], $data["email"], Carbon::now()->toDateTimeString(), Hash::make($data["password"]["first"]), Str::random(60), Carbon::now()->toDateTimeString(), Carbon::now()->toDateTimeString()])) {
                return response("User created successfully!", 201);
            } else {
                return response("Could not create user!", 404);
            }
        } else {
            return response("Password doesn't match!", 400);
        }
    }

    /**
     * Updates a user.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  user id
     *
     * @return response
     */
    public function updateUser(Request $request, $id)
    {
        $data = $request->json()->all();
        if ($data["password"]["first"] == $data["password"]["second"]) {
            if (DB::update("UPDATE users SET name=?, email=?, email_verified_at=?, password=?, created_at=?, updated_at=? WHERE id=?", [$data["name"], $data["email"], Carbon::now()->toDateTimeString(), Hash::make($data["password"]["first"]), Carbon::now()->toDateTimeString(), Carbon::now()->toDateTimeString(), $id])) {
                return response("User updated successfully!", 201);
            } else {
                return response("Could not update user!", 404);
            }
        } else {
            return response("Password doesn't match!", 400);
        }
    }

    /**
     * Deletes a user.
     *
     * @param int $id  user id
     *
     * @return response
     */
    public function deleteUser($id)
    {
        if (DB::delete("DELETE FROM users WHERE id=?", [$id])) {
            return response("User deleted successfully!", 200);
        } else {
            return response("Could not delete user!", 404);
        }
    }
}
