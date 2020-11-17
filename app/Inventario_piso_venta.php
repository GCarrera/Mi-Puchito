<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Inventario_piso_venta extends Pivot
{
    //
    protected $table = "inventario_piso_ventas";

    public function piso_venta() {
        return $this->belongsTo('App\Piso_venta');
    }

    public function inventario() {
        return $this->belongsTo('App\Inventario');
    }
}
