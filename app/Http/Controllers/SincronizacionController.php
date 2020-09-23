<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sincronizacion;

class SincronizacionController extends Controller
{
    //
    public function ultimo($id)
	{
		$sincronizacion = Sincronizacion::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

		return response()->json($sincronizacion);
	}

    public function store(Request $request)
    {
    	$sincronizacion = new Sincronizacion();
    	$sincronizacion->piso_venta_id = $request->id;
    	$sincronizacion->save();

    	return response()->json($sincronizacion);
    }
}
