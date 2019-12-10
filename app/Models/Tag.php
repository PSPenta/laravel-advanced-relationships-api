<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The Polymorphic M2M relationship.
     *
     * @return array
     */
    public function students()
    {
        return $this->morphedByMany(Student::class, 'taggable');
    }

    /**
     * The Polymorphic M2M relationship.
     *
     * @return array
     */
    public function videos()
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }
}
