<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{

  use SoftDeletes;

  protected $table = 'solicitudes';

    public function pisos()
    {
        return $this->belongsTo('App\Piso_venta');
    }
}
