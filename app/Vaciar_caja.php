<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaciar_caja extends Model
{
    public function pisos_de_ventas()
    {
      return $this->belongsTo('App\Piso_venta', 'piso_venta_id', 'id');
    }
}
