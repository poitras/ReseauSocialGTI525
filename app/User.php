<?php

namespace App;


use App\Http\Controllers\FriendsController;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @SWG\Definition(required=
 *     {
 *       "id",
 *       "first_name",
 *       "last_name",
 *       "email",
 *       "address",
 *       "city",
 *       "province",
 *       "postal_code",
 *       "password",
 *     },
 *     type="object",
 *     @SWG\Xml(name="User"))
 */

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'address', 'city', 'province', 'postal_code', 'avatar','password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function friends()
    {

        return $this->hasMany(Friend::class);

    }

    public function friendships()
    {

        return $this->hasMany(Friendship::class);

    }

    public function tickets()
    {

        return $this->hasMany(Ticket::class);

    }



}
