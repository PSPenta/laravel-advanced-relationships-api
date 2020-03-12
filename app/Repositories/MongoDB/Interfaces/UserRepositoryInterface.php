<?php

namespace App\Repositories\MongoDB\Interfaces;

interface UserRepositoryInterface
{
    public static function getPipelinedUsers($paginate);
}
