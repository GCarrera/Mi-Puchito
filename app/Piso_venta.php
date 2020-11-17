<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piso_venta extends Model
{
    //
    protected $fillable = [
        'nombre', 'ubicacion', 'dinero', 'user_id',
    ];


    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function inventario()
    {
    	return $this->belongsToMany('App\Inventario', 'inventario_piso_ventas')->using('App\Inventario_piso_venta')->withPivot([
                            'cantidad'
                        ]);
    }

    public function compras()
    {
    	return $this->hasMany('App\Compra');
    }

    public function ventas()
    {
    	return $this->hasMany('App\Venta');
    }

    public function cajas_vaciadas()
    {
    	return $this->hasMany('App\Vaciar_caja');
    }

    public function despachos()
    {
    	return $this->hasMany('App\Despacho');
    }
}
