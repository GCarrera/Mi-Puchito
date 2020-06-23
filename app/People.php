<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $fillable = ['dni', 'name', 'lastname', 'phone_number'];


    public function user()
    {
    	return $this->hasOne('App\User');
    }


    public function lastid()
    {
    	return $this->max('id');
    }
}
