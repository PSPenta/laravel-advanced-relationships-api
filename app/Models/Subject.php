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
     * @return array
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The Polymorphic O2M relationship.
     *
     * @return array
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
}
