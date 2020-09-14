<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Inventory extends Model implements Buyable 
{
    protected $fillable = ['product_name', 'quantity', 'status', 'margin_gain', 'iva', 'category_id', 'warehouse_id', 'enterprise_id', 'qty_per_unit'];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function enterprise()
    {
    	return $this->belongsTo('App\Enterprise');
    }

    public function warehouse()
    {
    	return $this->belongsTo('App\Warehouse');
    }

    // public function products()
    // {
    //     return $this->hasMany('App\Product');
    // }

    public function product()
    {
        return $this->hasOne('App\Product', 'inventory_id', 'id');
    }

    public function category_p()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

   public function getBuyableIdentifier($options = null){
        return $this->id;
    }

    public function getBuyableDescription($options = null){
        return $this->product_name;
    }

    public function getBuyablePrice($options = null){
        return $this->product->retail_total_price;
    }

}