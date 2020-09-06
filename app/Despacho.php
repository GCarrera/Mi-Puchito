<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Despacho extends Model
{
    //
    protected $fillable = [
        'piso_venta_id', 'confirmado', 'type'
    ];

    public function piso_venta()
    {
    	return $this->belongsTo('App\Piso_venta');
    }

    public function productos()
    {
    	return $this->belongsToMany('App\Inventory', 'despacho_detalles')->withPivot([
                            'cantidad'
                        ]);
    }
}
