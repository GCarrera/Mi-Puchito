<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['product_id', 'user_id'];

    public function product()
    {
    	return $this->belongsTo('App\Product');
    }

    public function inventory()
    {
    	return $this->hasOne('App\Inventory', 'id', 'product_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
