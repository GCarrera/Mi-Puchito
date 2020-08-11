<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressUserDelivery extends Model
{
    protected $fillable = ['details', 'travel_rate_id', 'user_id', 'stimated_time'];

    public function travel_rate()
    {
    	return $this->belongsTo('App\TravelRate');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function deliveries()
    {
    	return $this->hasMany('App\Delivery');
    }
}
