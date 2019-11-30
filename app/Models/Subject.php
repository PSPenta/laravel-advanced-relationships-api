<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'sub_name', 'total_marks', 'obtained_marks',
    ];

    /**
     * The One To One relationship.
     *
     * @var array
     */
    public function student()
    {
        return $this->belongsTo(App\Models\Student::class);
    }

    /**
     * The Polymorphic O2M relationship.
     *
     * @var array
     */
    public function photos()
    {
        return $this->morphMany(App\Models\Photo::class, 'imageable');
    }
}
