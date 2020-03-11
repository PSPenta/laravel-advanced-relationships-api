<?php

namespace App\Repositories;

use App\Models\MongoDB\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users pipelined data.
     *
     * @param 
     *
     * @return response
     */
    public static function getPipelinedUsers($paginate = 0)
    {
        $data = app(\Illuminate\Pipeline\Pipeline::class)
            ->send(User::query())
            ->through([
                \App\Pipes\Products::class,
                \App\Pipes\Roles::class
            ])
            ->thenReturn();

        if ($paginate) {
            return $data->paginate((int) $paginate);
        }

        return $data->get();
    }
}
