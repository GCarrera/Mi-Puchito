<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function state()
    {
    	return $this->belongsTo('App\State');
    }

    public function sectors()
    {
    	return $this->hasMany('App\Sector');
    }
}
