<?php

namespace App\Http\Controllers\Api\MongoDB;

use App\Events\EmailSend;
use App\Http\Controllers\Controller;
use App\Models\MongoDB\{Role, User};
use App\Repositories\MongoDB\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Log, Validator};
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all MongoDB users.
     *
     * @param 
     *
     * @return response
     */
    public function getUsers()
    {
        try {
            return response()->json(User::with('roles')->with('products')->get(), 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Get all MongoDB users with pagination.
     *
     * @param 
     *
     * @return response
     */
    public function getUsersPaginate()
    {
        try {
            return response()->json(User::with(['roles', 'products'])->paginate(10), 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Get all MongoDB users with some query operations.
     *
     * @param 
     *
     * @return response
     */
    public function getUsersPipelined(UserRepositoryInterface $user)
    {
        try {
            event(new EmailSend(["event" => "broadcasting"]));
            // Log::info('Event Broadcasted!');
            return response()->json($user->getPipelinedUsers(request('paginate')), 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Finds a user using entered _id.
     *
     * @param 
     *
     * @return response
     */
    public function getUser(User $user)
    {
        try {
            return response()->json($user->load(['roles', 'products']), 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Create new User.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function addUser(Request $request, User $user)
    {
        try {
            $data = $request->json()->all();

            $validator = Validator::make($data, [
                'fname' => 'required|string|min:6|max:255',
                'lname' => 'required|string|min:6|max:255',
                'email' => 'required|email|max:255|unique:mongodb.users',
                'password' => [
                    'required',
                    'min:6',
                    'max:255',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
                    'confirmed'
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()->all()], 422);
            }

            $user->profile = [
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'lname' => $data['lname'],
            ];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->api_token = Str::random(60);
            $user->save();
            $user->roles()->save(
                new Role(['type' => 'user', 'description' => 'Newly created user'])
            );
            if ($user) {
                return response()->json(["success" => "User created successfully!"], 201);
            } else {
                return response()->json(["error" => "Could not create user!"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Updates a User.
     *
     * @param Illuminate\Http\Request $request
     * @param string $_id  App\Models\MongoDB\User _id
     *
     * @return response
     */
    public function updateUser(Request $request, User $user)
    {
        try {
            $data = $request->json()->all();

            $validator = Validator::make($data, [
                'fname' => 'required|string|min:6|max:255',
                'lname' => 'required|string|min:6|max:255',
                'email' => 'required|email|max:255|unique:mongodb.users,'.$user->_id,
                'password' => [
                    'required',
                    'min:6',
                    'max:255',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
                    'confirmed'
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()->all()], 422);
            }

            if ($user) {
                $user->profile = [
                    'fname' => $data['fname'],
                    'mname' => $data['mname'],
                    'lname' => $data['lname'],
                ];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->api_token = Str::random(60);
                if ($user->save()) {
                    return response()->json(["success" => "User updated successfully!"], 200);
                } else {
                    return response()->json(["error" => "Could not update student!"], 404);
                }
            } else {
                return response()->json(["error" => "User does not exist!"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }

    /**
     * Deletes a user.
     *
     * @param string $_id  App\Models\MongoDB\User _id
     *
     * @return response
     */
    public function deleteUser(User $user)
    {
        try {
            if ($user) {
                if ($user->delete()) {
                    return response()->json(["success" => "User deleted successfully!"], 200);
                } else {
                    return response()->json(["success" => "Could not delete user!"], 404);
                }
            } else {
                return response()->json(["error" => "User does not exist!"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
        }
    }
}
