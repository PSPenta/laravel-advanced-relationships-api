<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MongoDB\{User};
use Illuminate\Http\Request;

class MongoDBController extends Controller
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
        return response()->json(User::all(), 200);
    }
}
