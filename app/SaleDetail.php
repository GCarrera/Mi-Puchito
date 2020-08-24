<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = ['quantity', 'product_id', 'sale_id', 'sub_total', 'amount', 'iva'];
    
    public function inventory()
    {
        return $this->hasOne('App\Inventory', 'id', 'product_id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product');
    }

    public function sale()
    {
    	return $this->belongsTo('App\Sale');
    }
}
