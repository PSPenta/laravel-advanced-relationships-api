<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * @var array
     */
    public function roles()
    {
        return $this->belongsToMany(App\Models\Role::class);
    }

    /**
     * The Has Many Through relationship.
     *
     * @var array
     */
    public function subjects()
    {
        return $this->hasManyThrough(App\Models\Subject::class, App\Models\Student::class);
    }

    /**
     * The Polymorphic relationship.
     *
     * @var array
     */
    public function photos()
    {
        return $this->morphMany(App\Models\Photo::class, 'imageable');
    }
}
