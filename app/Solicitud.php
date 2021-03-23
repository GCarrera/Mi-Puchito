<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    public function pisos()
    {
        return $this->belongsTo('App\Piso_venta');
    }
}
