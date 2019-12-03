<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * The Polymorphic M2M relationship.
     *
     * @return array
     */
    public function tags()
    {
        return $this->morphToMany(App\Models\Tag::class, 'taggable');
    }
}
