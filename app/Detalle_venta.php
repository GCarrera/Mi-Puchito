<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Detalle_venta extends Pivot
{
    //
    protected $table = 'detalle_ventas';

	protected $fillable = [
        'sub_total', 'iva', 'total', 'inventario_id', 'cantidad', 'venta_id'
    ];

    public function piso_venta() {
        return $this->belongsTo('App\Venta');
    }

    public function inventario() {
        return $this->belongsTo('App\Inventario');
    }
}
