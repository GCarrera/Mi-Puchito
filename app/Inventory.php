<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['product_name', 'quantity', 'status', 'margin_gain', 'iva', 'category_id', 'warehouse_id', 'enterprise_id'];

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
}