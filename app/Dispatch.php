<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    public function productos() {
        return $this->belongsToMany('App\Producto', 'dispatch_producto', 'dispatch_id', 'producto_id')->withPivot('cantidad');
    }
}
