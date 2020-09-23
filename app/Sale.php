<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['amount', 'dispatched', 'payment_capture', 'code', 'delivery', 'user_id', 'sub_total', 'amount', 'iva'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function deliveries()
    {
    	return $this->hasMany('App\Delivery');
    }


    public function lastid()
    {
    	return $this->max('id');
    }

    public function details()
    {
        return $this->hasMany('App\SaleDetail', 'sale_id', 'id');
    }

    public function dolar()
    {
        return $this->belongsTo('App\Dolar');
    }
}
