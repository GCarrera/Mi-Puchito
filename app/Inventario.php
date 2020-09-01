<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    public function piso_venta()
    {
    	return $this->belongsToMany('App\Piso_venta', 'inventario_piso_ventas')->using('App\Inventario_piso_venta')->withPivot([
                            'cantidad'
                        ]);
    }

    public function precio()
    {
    	return $this->hasOne('App\Precio');
    }

    public function ventas(){

        return $this->belongsToMany('App\Venta', 'detalle_ventas')->using('App\Detalle_venta')->withPivot([
                            'cantidad',
                            'tipo',
                            'sub_total',
                            'iva',
                            'total'
                        ]);;
    }

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }
}
