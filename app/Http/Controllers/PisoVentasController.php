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
use App\Inventario;
use App\Precio;
use App\Product;
use App\Inventory;
use App\Solicitud;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class PisoVentasController extends Controller
{
    //
    public function index()
    {
    	return view('admin.piso_ventas');
    }

    public function ganancias(Request $request)
    {
      if ($request->fechas) {
        $fechas = explode('-', $request->fechas);

  			$fecha_inicial = new Carbon($fechas[0]);
  			$fecha_final = new Carbon($fechas[1]);

        $cajas = Vaciar_caja::with('pisos_de_ventas')
        ->whereDate('created_at', '>=', $fecha_inicial)
  			->whereDate('created_at', '<=', $fecha_final)
        ->orderBy('id', 'desc')
        ->get();

      } else {
        $cajas = Vaciar_caja::with('pisos_de_ventas')->get();
      }

      //return $cajas;
      return view('admin.gain')->with('cajas', $cajas);

    }

    public function solicitudes()
    {
      $solicitudes = Solicitud::get();

      return view('admin.solicitudes')->with('solicitudes', $solicitudes);

    }

    public function ventas_mostrar($id)
    {
      $ventas = Venta::with('detalle')->where('piso_venta_id', $id)->where('type', 1)->limit(1555)->get();
      $piso_ventas = Piso_venta::where('id', $id)->get();
    	return view('admin.piso_ventas_ventas')
      ->with('piso_venta', $piso_ventas)
			->with('ventas', $ventas);
    }

    public function cajas_mostrar($id)
    {
      $ventas = Vaciar_caja::where('piso_venta_id', $id)->get();
      $piso_ventas = Piso_venta::where('id', $id)->get();
    	return view('admin.piso_ventas_cajas')
      ->with('piso_venta', $piso_ventas)
			->with('cajas', $ventas);
    }

    public function inventario_mostrar($id)
    {
      $inventario = Inventario_piso_venta::with('inventario')->where('piso_venta_id', $id)->orderBy('id', 'desc')->get();
      $piso_ventas = Piso_venta::where('id', $id)->get();
    	return view('admin.piso_ventas_inventario')
      ->with('piso_venta', $piso_ventas)
			->with('inventario', $inventario);
    }

    public function despachos_mostar($id)
    {
      $despachos = Despacho::where('piso_venta_id', $id)->where('type', 1)->get();
      $piso_ventas = Piso_venta::where('id', $id)->get();
    	return view('admin.piso_ventas_despachos')
      ->with('piso_venta', $piso_ventas)
			->with('despachos', $despachos);
    }

    public function retiros_mostrar($id)
    {
      $retiros = Despacho::where('piso_venta_id', $id)->where('type', 2)->get();
      $piso_ventas = Piso_venta::where('id', $id)->get();
    	return view('admin.piso_ventas_retiros')
      ->with('piso_venta', $piso_ventas)
			->with('retiros', $retiros);
    }

    public function precios()
    {
        return view('admin.piso_ventas_precios');
    }

    public function anclar()
    {
        return view('admin.piso_ventas_anclar');
    }

    public function get_piso_ventas(){
    	$piso_ventas = Piso_venta::all();
      foreach ($piso_ventas as $key => $value) {
        $usuario = $value['id'];
        $sincronizacion = Sincronizacion::where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();
        if (isset($sincronizacion['created_at'])) {
          $pos = $usuario-1;
          $carbon = new Carbon($sincronizacion['created_at']);
          $piso_ventas[$pos]['sincro'] = $sincronizacion;
        }
      }

    	return response()->json($piso_ventas);
    }

    public function resumen($id)
    {
    	$date = Carbon::now();
    	$mes = $date->month;
    	$dia = $date->day;

    	//$ventas = Venta::where('piso_venta_id', $id)->where('type', 1)->whereMonth('created_at', $mes)->count(); --> Cuenta solo el mes en transcurso
    	$ventas = Venta::where('piso_venta_id', $id)->where('type', 1)->count(); // --> Cuanta todas
      //return response()->json(['ventas' => $ventas]);
    	//$compras = Venta::where('piso_venta_id', $id)->where('type', 2)->whereMonth('created_at', $mes)->count(); --> Cuenta las compras
      //$inventario = Inventario_piso_venta::with('inventario')->where('piso_venta_id', $id)->orderBy('id', 'desc')->count();
    	$compras = Inventario_piso_venta::with('inventario')->where('piso_venta_id', $id)->orderBy('id', 'desc')->count(); // --> Cuenta cada producto diferente
    	$despachos = Despacho::where('piso_venta_id', $id)->where('type', 1)->whereMonth('created_at', $mes)->count();
    	$retiros = Despacho::where('piso_venta_id', $id)->where('type', 2)->whereMonth('created_at', $mes)->count();

        $sincronizacion = Sincronizacion::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

      $caja = Vaciar_caja::where('piso_venta_id', $id)->whereDay('created_at', $dia)->count();
      //$caja = Vaciar_caja::where('piso_venta_id', $id)->orderBy('id', 'desc')->first();

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
        //return response()->json($request->fecha_i);

    		//$fecha_i = new Carbon($request->fecha_i);
    		//$fecha_f = new Carbon($request->fecha_f);


    		$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereDate('created_at','>=', $request->fecha_i)->whereDate('created_at','<=', $request->fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	//$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(10); -->trae un solo mes
	    	$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->orderBy('id', 'desc')->paginate(10);
        //return response()->json("else");
	    }

    	return response()->json($ventas);
    }

    public function cajas($id, Request $request)
    {
      //return response()->json($id);
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {
        //return response()->json('$request->fecha_i');

    		$ventas = Vaciar_caja::where('piso_venta_id', $id)->whereDate('created_at','>=', $request->fecha_i)->whereDate('created_at','<=', $request->fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{
        //return response()->json('$request->fecha_i');

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	//$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(10); -->trae un solo mes
	    	$ventas = Vaciar_caja::where('piso_venta_id', $id)->orderBy('id', 'desc')->paginate(10);
        //return response()->json("else");
	    }

    	return response()->json($ventas);
    }

    public function all_ventas_compras($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {
        //return response()->json($request->fecha_i);

    		//$fecha_i = new Carbon($request->fecha_i);
    		//$fecha_f = new Carbon($request->fecha_f);


    		$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereDate('created_at','>=', $request->fecha_i)->whereDate('created_at','<=', $request->fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$ventas = Venta::with('detalle')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(10);
        //return response()->json("else");
	    }

    	return response()->json($ventas);
    }

    public function all_cajas($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {

    		$ventas = Vaciar_caja::where('piso_venta_id', $id)->whereDate('created_at','>=', $request->fecha_i)->whereDate('created_at','<=', $request->fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$ventas = Vaciar_caja::where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(10);
        //return response()->json("else");
	    }

    	return response()->json($ventas);
    }

    public function despachos_retiros($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {

    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->where('type', '1')->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->where('type', '1')->orderBy('id', 'desc')->paginate(10);
    	}
    	return response()->json($despachos);
    }

    public function despachos_retiros_retiros($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {

    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->where('type', '2')->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->where('type', '2')->orderBy('id', 'desc')->paginate(10);
    	}
    	return response()->json($despachos);
    }

    public function productos_piso_venta($id, Request $request)
    {

    	$productos = Inventario_piso_venta::with('inventario.precio')->where('piso_venta_id', $id)->whereHas('inventario', function($q)use($request){
         // $q->where('name', 'quo');
          if ($request->search != null) {

              $q->where('name', 'like', '%'.$request->search.'%');
          }

      })->orderBy('cantidad', 'desc')->paginate(10);

    	return response()->json($productos);
    }

    public function cantidad_edit(Request $request)
    {
      $producto = Inventario_piso_venta::where('id', $request->idproductos)->orderBy('id', 'desc')->first();

      $producto->cantidad = $request->cantidad;
      $producto->save();

      $inventario = Inventario::where('id', $producto->inventario_id)->orderBy('id', 'desc')->first();

      $inventario->audit = 1;
      $inventario->save();

      return response()->json($producto);
    }

    public function auditoria(Request $request)
    {
      //$productos = Inventario_piso_venta::with('inventario.piso_venta')->where('piso_venta_id', $request->id)->get();
      //$productos = Inventario::with('inventory.product')->where('piso_venta_id', $request->id)->get();
      $productos = Inventory::whereHas('inventario', function($inventario) use($request)
      {
        $inventario->where('piso_venta_id', $request->id)->whereHas('piso_venta', function($inventario_piso_venta) use($request)
        {
          $inventario_piso_venta->where('piso_venta_id', $request->id);
        });
      })->with('product')->get();

      $cantidades = Inventario_piso_venta::whereHas('inventario', function($inventario) use($request)
      {
        $inventario->where('piso_venta_id', $request->id);
      })->with('inventario:id,inventory_id')->where('piso_venta_id', $request->id)->get();

      /*$productos = Inventory::with(['inventario' => function ($query) {
          $query->where('piso_venta_id', 2);
      }, 'product'])->get();*/
    
      /*$productos = Inventory::whereHas('inventario', function($inventario) use($request) {
				$inventario->where('piso_venta_id', $request->id);
				$inventario->with(['inventario_piso_venta' => function($product) {
					if ($product != NULL) {

					}
				}]);				
			})->get();*/
      //$productos = Inventory::with('inventario')->get();
      $softdeletes = Inventory::onlyTrashed()->get();

      return response()->json(['productos' => $productos, 'softdeletes' => $softdeletes, 'cantidades' => $cantidades]);
    }
    
    public function auditoriap(Request $request)
    {      
      $cantidades = Inventario_piso_venta::whereHas('inventario', function($inventario) use($request)
      {
        $inventario->where('piso_venta_id', $request->id)->where('audit', 1);
      })->with('inventario:id,inventory_id')->where('piso_venta_id', $request->id)->get();

      return response()->json(['cantidades' => $cantidades]);
    }
    
    public function auditorias(Request $request)
    {
      $status = Inventario::where('piso_venta_id', $request->id)
          ->where('audit', 1)
          ->update(['audit' => '0']);

      return response()->json($status);
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
            $vaciar_caja->ganancia = $caja['ganancia'];
            $vaciar_caja->id_extra = $caja['id_extra'];
            $vaciar_caja->save();
        }

        return response()->json(true);
    }

    public function productos_piso_venta_actualizar_precios($id)
    {

        $inventario = Inventario::with('precio')->where('piso_venta_id', $id)->where('inventory_id', null)->get();

        return response()->json($inventario);
    }

    public function actualizar_precio(Request $request, $id)
    {

        $precio = Precio::where('inventario_id', $id)->orderBy('id', 'desc')->first();

        $precio->costo = $request->costo;
        $precio->iva_porc = $request->iva_porc;
        $precio->iva_menor = $request->iva;
        $precio->sub_total_menor = $request->sub_total;
        $precio->total_menor = $request->total;
        $precio->save();

        $inventario = Inventario::with('precio')->findOrFail($id);

        return response()->json($inventario);
    }

    public function requisitos_anclar($id)
    {
        $respuesta = 0;

        $inventario = Inventario::with('precio')->where('piso_venta_id', $id)->where('inventory_id', null)->get();

        $ides_inventory = Inventario::select('inventory_id')->where('piso_venta_id', $id)->where('inventory_id', '!=' ,null)->get();
        $ides = [];

        foreach ($ides_inventory as $value) {

            array_push($ides, $value['inventory_id']);
        }

        $inventory = Inventory::whereHas('product', function($q){

            $q->where('id', '!=', null);

        })->cursor()->filter(function($item) use($ides, $respuesta){

            foreach ($ides as $valor) {
                # code...
                if ($valor == $item->id) {

                    $respuesta = $item->id;
                }
            }

            return $item->id != $respuesta;


        });

        return response()->json(['inventario' => $inventario, 'inventory' => $inventory, 'ides' => $ides, 'respuesta' => $respuesta]);
    }

    public function enlazar(Request $request, $id)
    {
        $inventario = Inventario::where('piso_venta_id', $request->piso_venta_id)->where('id', $id)->orderBy('id', 'desc')->first();

        $inventario->inventory_id = $request->producto_anclar;
        $inventario->save();

        return response()->json(true);
    }

    public function last_solicitud(Request $request)
    {
      $usuario = $request->id;

      $solicitud = Solicitud::withTrashed()->select('id_extra')->where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();

      return $solicitud;
    }

    public function nuevas_solicitud(Request $request)
    {
      $solicitudes = $request->data;

      foreach ($solicitudes as $key => $value) {
        $solicitud = new Solicitud();

    		$solicitud->nombre = $value['nombre'];
    		$solicitud->telefono = $value['telefono'];
    		$solicitud->producto = $value['producto'];
    		$solicitud->piso_venta_id = $value['piso_venta_id'];
    		$solicitud->id_extra = $value['id'];
    		$solicitud->save();
      }

      return $solicitud;
    }

    public function finish_solicitud($Solicitud)
    {
      $Solicitud = Solicitud::find($Solicitud);

      $Solicitud->delete();

      return redirect()->back()->with('success', 'Solicitud finalizada exitosamente.');
    }

    public function finished_solicitud(Request $request)
    {
      $usuario = $request->id;

      $solicitud = Solicitud::onlyTrashed()->select('id_extra')->where('piso_venta_id', $usuario)->orderBy('id', 'desc')->get();

      return $solicitud;
    }
}
