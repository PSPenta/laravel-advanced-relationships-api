<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The date mutators.
     *
     * @var array
     */
    protected $dates = ['email_verified_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The Many To Many relationship.
     *
     * @return array
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * The Has Many Through relationship.
     *
     * @return array
     */
    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, Student::class);
    }

    /**
     * The Polymorphic relationship.
     *
     * @return array
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
}
