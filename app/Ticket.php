<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'owner_first_name', 'owner_last_name', 'user_id', 'event', 'artist', 'price', 'venue', 'city', 'date_event', 'image', 'description',
    ];


    public function user()
    {

        return $this->belongsTo(User::class);

    }
}