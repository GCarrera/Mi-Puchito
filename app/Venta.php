<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //
    protected $fillable = [
        'sub_total', 'iva', 'total', 'piso_venta_id', 'type'
    ];


    public function piso_venta()
    {
    	return $this->belongsTo('App\Piso_venta');
    }

    public function detalle()
    {
        return $this->belongsToMany('App\Inventario', 'detalle_ventas')->using('App\Detalle_venta')->withPivot([
                            'cantidad',
                            'sub_total',
                            'iva',
                            'total'
                        ]);
    }
}
