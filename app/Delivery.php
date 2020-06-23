<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['address_user_delivery_id', 'sale_id'];

    public function address_user_delivery()
    {
    	return $this->belongsTo('App\AddressUserDelivery');
    }

    public function sale()
    {
    	return $this->belongsTo('App\Sale');
    }
}
