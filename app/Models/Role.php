<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The Many To Many relationship.
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsToMany(App\Models\User::class)->withPivot('created_at', 'updated_at');
    }
}
