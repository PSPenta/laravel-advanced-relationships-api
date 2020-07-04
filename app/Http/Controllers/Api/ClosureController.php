<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClosureController extends Controller
{
    /**
     * Get all MongoDB users with pagination.
     *
     * @param 
     *
     * @return response
     */
    public function unauthenticated()
    {
        return response()->json(["error" => "Unauthenticated user!"], 401);
    }

    /**
     * Get all MongoDB users with pagination.
     *
     * @param 
     *
     * @return response
     */
    public function unauthorized()
    {
        return response()->json(["error" => "Unauthorized user!"], 403);
    }

    /**
     * Get all MongoDB users with pagination.
     *
     * @param 
     *
     * @return response
     */
    public function error404()
    {
        return response()->json(["error" => "Not found!"], 404);
    }
}
