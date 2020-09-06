<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Piso_venta;
use App\Venta;
use App\Despacho;
use Carbon\Carbon;
use App\Inventario_piso_venta;
use App\Vaciar_caja;
use App\Sincronizacion;

class PisoVentasController extends Controller
{
    //
    public function index()
    {
    	return view('admin.piso_ventas');
    }

    public function get_piso_ventas(){
    	$piso_ventas = Piso_venta::all();

    	return response()->json($piso_ventas);
    }

    public function resumen($id)
    {
    	$date = Carbon::now();
    	$mes = $date->month;

    	$ventas = Venta::where('piso_venta_id', $id)->where('type', 1)->whereMonth('created_at', $mes)->count();
    	$compras = Venta::where('piso_venta_id', $id)->where('type', 2)->whereMonth('created_at', $mes)->count();
    	$despachos = Despacho::where('piso_venta_id', $id)->where('type', 1)->whereMonth('created_at', $mes)->count();
    	$retiros = Despacho::where('piso_venta_id', $id)->where('type', 2)->whereMonth('created_at', $mes)->count();

        $sincronizacion = Sincronizacion::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

        $caja = Vaciar_caja::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

    	return response()->json([
    							'ventas' => $ventas,
    							'compras' => $compras,
    							'despachos' => $despachos,
    							'retiros' => $retiros,
                                'sincronizacion' => $sincronizacion,
                                'caja' => $caja
    							]);
    }

    public function ventas_compras($id, Request $request)
    {	
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {
    		
    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(1);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(1);
	    }

    	return response()->json($ventas);
    }

    public function despachos_retiros($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {
    		
    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(1);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(1);
    	}
    	return response()->json($despachos);
    }

    public function productos_piso_venta($id)
    {

    	$productos = Inventario_piso_venta::with('inventario.precio')->where('piso_venta_id', $id)->orderBy('cantidad', 'desc')->paginate(1);

    	return response()->json($productos);
    }

    public function actualizar_dinero_piso_venta($id, Request $request)//WEB Y LOCAL
    {
        $piso_venta = Piso_venta::findOrFail($id);
        $piso_venta->dinero = $request->dinero;
        $piso_venta->save();

        return response()->json(true);
    }

    public function ultima_vaciada_caja($id)
    {
        $caja = Vaciar_caja::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

        return response()->json($caja);
    }

    public function registrar_cajas(Request $request)
    {
        foreach ($request->cajas as $caja) {
            $vaciar_caja = new Vaciar_caja();
            $vaciar_caja->piso_venta_id = $caja['piso_venta_id'];
            $vaciar_caja->monto = $caja['monto'];
            $vaciar_caja->id_extra = $caja['id_extra'];
            $vaciar_caja->save();
        }

        return response()->json(true);
    }
}
