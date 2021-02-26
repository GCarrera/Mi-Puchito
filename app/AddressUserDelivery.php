<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressUserDelivery extends Model
{
  use SoftDeletes;

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
