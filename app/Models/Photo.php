<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The Polymorphic relationship.
     *
     * @return object
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
