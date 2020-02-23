<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
