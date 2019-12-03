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
        return $this->morphedByMany(App\Models\Student::class, 'taggable');
    }

    /**
     * The Polymorphic M2M relationship.
     *
     * @return array
     */
    public function videos()
    {
        return $this->morphedByMany(App\Models\Video::class, 'taggable');
    }
}
