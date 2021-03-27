<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dolar extends Model
{
    //
    protected $fillable = ['price'];

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }
}
