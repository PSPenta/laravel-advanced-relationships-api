<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model;

class User extends Model
{
    protected $collection = 'users';
}
