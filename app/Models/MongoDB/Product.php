<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model;

class Product extends Model
{
    /**
     * The attribute to notify the connection type.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The Many To Many relationship.
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
