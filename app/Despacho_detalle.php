<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Despacho_detalle extends Pivot
{
    //
    protected $fillable = [
        'cantidad', 'inventory_id', 'tipo', 'despacho_id'
    ];

    protected $table = "despacho_detalles";
}
