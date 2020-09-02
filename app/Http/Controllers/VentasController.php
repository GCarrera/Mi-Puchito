<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use Illuminate\Support\Facades\Auth;
use App\Inventario_piso_venta;
use App\Detalle_venta;
use DB;
use App\Inventario;
use App\Precio;

class VentasController extends Controller
{
    //
    public function index()
    {

    	return view('ventas.index');
    }

    public function get_ventas()
    {
    	$usuario = Auth::user()->piso_venta->id;

        $ventas = Venta::with(['detalle'])->where('piso_venta_id', $usuario)->orderBy('id', 'desc')->paginate(1);

        return response()->json($ventas);
    }

    public function get_datos_create()
    {
    	$usuario = Auth::user()->piso_venta->id;

    	$inventario = Inventario_piso_venta::with('inventario.precio')->where('piso_venta_id', $usuario)->get();

    	return response()->json($inventario);
    }

    public function store(Request $request)
    {
    	$usuario = Auth::user()->piso_venta->id;
    	try{

			DB::beginTransaction();
	    	$venta = new Venta();	
	        $venta->piso_venta_id = $usuario;
	        $venta->type = 1; // 1 ES VENTA
	        $venta->sub_total = $request->venta['sub_total'];
	        $venta->iva = $request->venta['iva'];
	        $venta->total = $request->venta['total'];

	        $venta->save();

	        $venta->id_extra = $venta->id;
	        $venta->save();

	        foreach ($request->productos as $producto) {
	        	//REGISTRAMOS EL PRODUCTO EN LOS DETALLES DE LA VENTA
	            $detalles = new Detalle_venta();
	            $detalles->venta_id = $venta->id;
	            $detalles->cantidad = $producto['cantidad'];
	            $detalles->inventario_id = $producto['id'];
	            $detalles->sub_total = $producto['sub_total'];
		        $detalles->iva = $producto['iva'];
		        $detalles->total = $producto['total'];
	            $detalles->save();

	            //RESTAMOS DEL STOCK
	            $inventario = Inventario_piso_venta::where('piso_venta_id', $usuario)->where('inventario_id', $producto['id'])->orderBy('id', 'desc')->first();

	            $resta = $inventario->cantidad -= $producto['cantidad'];
	            //VALICACION POR SI NO HAY SUFICIENTES PRODUCTOS
	            if ($resta < 0) {

	            	return response()->json(['errors' => 'no hay suficientes productos en el inventario']);
	            	DB::rollback();
	            	
	            }

	            $inventario->save();
	        }

	        DB::commit();

	        $venta = Venta::with('detalle')->findOrFail($venta->id);

	        return response()->json($venta);
	    	
		}catch(Exception $e){

			DB::rollback();
			return response()->json($e);
		}
    }

    public function store_compra(Request $request)
    {
    	$usuario = Auth::user()->piso_venta->id;
    	try{

			DB::beginTransaction();
	    	$venta = new Venta();	
	        $venta->piso_venta_id = $usuario;
	        $venta->type = 2; // 1 ES VENTA 2 ES COMPRA
	        $venta->sub_total = $request->venta['sub_total'];
	        $venta->iva = $request->venta['iva'];
	        $venta->total = $request->venta['total'];

	        $venta->save();

	        $venta->id_extra = $venta->id;
	        $venta->save();

	        foreach ($request->productos as $producto) {
	        	//REGISTRAMOS EL PRODUCTO
	        	 $articulo = new Inventario();
                $articulo->name = $producto['nombre'];
                //$articulo->unit_type_mayor = $producto['unit_type'];
                $articulo->unit_type_menor = $producto['unidad'];
                //$articulo->inventory_id = $producto['pivot']['inventory_id'];
                $articulo->status = 1;
                $articulo->save();
                //REGISTRAMOS LOS PRECIOS
                $precio = new Precio();
                $precio->costo = $producto['costo'];
                $precio->iva_porc = $producto['iva_porc'];
                $precio->iva_menor = $producto['iva_unitario'];
                $precio->sub_total_menor = $producto['sub_total_unitario'];
                $precio->total_menor = $producto['total_unitario'];
                $precio->inventario_id = $articulo->id;
                $precio->save();


	        	//REGISTRAMOS EL PRODUCTO EN LOS DETALLES DE LA VENTA
	            $detalles = new Detalle_venta();
	            $detalles->venta_id = $venta->id;
	            $detalles->cantidad = $producto['cantidad'];
	            $detalles->inventario_id = $articulo->id;
	            $detalles->sub_total = $producto['sub_total'];
		        $detalles->iva = $producto['iva'];
		        $detalles->total = $producto['total'];
	            $detalles->save();

	            //SUMAMOS EL STOCK
	            $inventario = new Inventario_piso_venta();
	            $inventario->inventario_id = $articulo->id;
	            $inventario->piso_venta_id = $usuario;
	            $inventario->cantidad = $producto['cantidad'];
	            $inventario->save();
	        }

	        DB::commit();

	        $venta = Venta::with('detalle')->findOrFail($venta->id);

	        return response()->json($venta);
	    	
		}catch(Exception $e){

			DB::rollback();
			return response()->json($e);
		}
    }
    //APARTIR DE AQUI ES EL REFRESCAR
    public function get_piso_venta_id()
    {
    	$usuario = Auth::user()->piso_venta->id;

    	return response()->json($usuario);
    }

    public function ultima_venta($piso_venta)//DEL LADO DE LA WEB
    {
    	//OBTENEMOS LA ULTIMA VENTA QUE TIENE LA WEB Y LE MANDAMOS EL ID_EXTRA
    	$venta = Venta::select('id_extra')->where('piso_venta_id', $piso_venta)->orderBy('id', 'desc')->first();

    	return response()->json($venta);
    }

    public function ventas_sin_registrar($piso_venta, $id)
    {
    	$ventas = Venta::with('detalle')->where('piso_venta_id', $piso_venta)->where('id_extra', '>', $id)->get();

    	return response()->json($ventas);
    }

    public function registrar_ventas(Request $request) //WEB
    {
    	
    	try{

			DB::beginTransaction();
			foreach ($request->ventas as $valor) {

		    	$venta = new Venta();	
		        $venta->piso_venta_id = $request->piso_venta_id;
		        $venta->type = $valor['type'];
		        $venta->sub_total = $valor['sub_total'];
		        $venta->iva = $valor['iva'];
		        $venta->total = $valor['total'];
		        $venta->id_extra = $valor['id_extra'];
		        $venta->save();

		        foreach ($valor['detalle'] as $producto) {
		        	//REGISTRAMOS EL PRODUCTO EN LOS DETALLES DE LA VENTA
		            $detalles = new Detalle_venta();
		            $detalles->venta_id = $venta->id;
		            $detalles->cantidad = $producto['pivot']['cantidad'];
		            $detalles->inventario_id = $producto['pivot']['inventario_id'];
		            $detalles->sub_total = $producto['pivot']['sub_total'];
			        $detalles->iva = $producto['pivot']['iva'];
			        $detalles->total = $producto['pivot']['total'];
		            $detalles->save();

		            //RESTAMOS DEL STOCK
		            $inventario = Inventario_piso_venta::where('piso_venta_id', $request->piso_venta_id)->whereHas('inventario', function($q){
		            	$q->where('inventory_id', $producto['inventory_id']);
		            })->orderBy('id', 'desc')->first();
		            //SI NO ENCUENTRA EL PRODUCTO QUE LO REGISTRE
		            
		            if ($inventario->id == null) {
                        $articulo = new Inventario();
                        $articulo->name = $producto['name'];
                        $articulo->unit_type_mayor = $producto['unit_type_mayor'];
                        $articulo->unit_type_menor = $producto['unit_type_menor'];
                        $articulo->inventory_id = $producto['inventory_id'];
                        $articulo->save();
                        //REGISTRA LA CANTIDAD EN EL INVENTARIO DEL PISO DE VENTA
                        $inventario = new Inventario_piso_venta();
                        $inventario->inventario_id = $articulo->id;
                        $inventario->piso_venta_id = $request->piso_venta_id;
                        $inventario->cantidad = $producto['pivot']['cantidad'];
                        $inventario->save();
                    }else{
					
                    //SI ES UNA VENTA O UNA COMPRA
                    if ($venta->type == 1) {

                    	$resta = $inventario->cantidad -= $producto['pivot']['cantidad'];
                    }else{
                    	$resta = $inventario->cantidad += $producto['pivot']['cantidad'];
                    }
		            	
		            //}
		            //VALICACION POR SI NO HAY SUFICIENTES PRODUCTOS
		            /*
		            if ($resta < 0) {

		            	return response()->json(['errors' => 'no hay suficientes productos en el inventario']);
		            	DB::rollback();
		            	
		            }
		            */

		            $inventario->save();
		        }
			}

		        DB::commit();

		        return response()->json(true);
	    	
		}catch(Exception $e){

			DB::rollback();
			return response()->json($e);
		}
    }
}
