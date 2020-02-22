<?php

namespace App\Models\MongoDB;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;

class User extends Model
{
    use Notifiable;

    /**
     * The attribute to notify the connection type.
     *
     * @var string
     */
    protected $connection = 'mongodb';

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
        'password',
        'remember_token'
    ];
}
