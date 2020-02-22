<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model;

class Role extends Model
{
    /**
     * The attribute to notify the connection type.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'description',
    ];
}
