<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name', 'estado', 'ciudad', 'direccion'];

    public function users()
    {
    	return $this->belongsToMany('App\User');
    }

    public function inventories()
    {
    	return $this->hasMany('App\Inventory');
    }
}
