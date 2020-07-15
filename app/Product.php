<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['cost', 'iva_percent', 'retail_margin_gain', 'retail_pvp', 'retail_total_price', 'retail_iva_amount', 'image', 'wholesale_margin_gain', 'wholesale_pvp', 'wholesale_total_individual_price', 'wholesale_total_packet_price', 'wholesale_iva_amount', 'inventory_id'];

    public function inventory()
    {
    	return $this->belongsTo('App\Inventory');
    }

    public function product_ratings()
    {
    	return $this->hasMany('App\ProductRating');
    }
}