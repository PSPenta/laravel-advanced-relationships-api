<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public static function getPipelinedUsers($paginate);
}
