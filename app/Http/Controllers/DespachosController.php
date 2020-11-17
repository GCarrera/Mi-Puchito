<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Piso_venta;
use App\Despacho;
use Illuminate\Support\Facades\Auth;
use App\Inventario;
use App\Inventario_piso_venta;
use App\Inventory;
use App\Despacho_detalle;
use DB;
use App\Precio;
//use App\Inventory;

class DespachosController extends Controller
{
    //
    public function index()
    {

    	return view('despachos.index');
    }

    public function get_despachos()
    {
        $usuario = Auth::user()->piso_venta->id;

        $despachos = Despacho::with(['productos' => function($producto){
            $producto->select('product_name');
        }])->where('piso_venta_id', $usuario)->orderBy('id', 'desc')->paginate(1);

        return response()->json($despachos);
    }

    public function confirmar_despacho(Request $request)
    {
        $usuario = Auth::user()->piso_venta->id;

        $despacho = Despacho::with(['productos' => function($producto){
            $producto->select('product_name');
        }])->findOrFail($request->id);
        $despacho->confirmado = 1;
        $despacho->save();


        foreach ($despacho->productos as $valor) {
            //BUSCAMOS EL ID EN INVENTARIO
            $producto = Inventario::select('id')->where('inventory_id', $valor->pivot->inventory_id)->orderBy('id', 'desc')->first();

            $inventario = Inventario_piso_venta::with('inventario')->where('piso_venta_id', $usuario)->where('inventario_id', $producto->id)->orderBy('id', 'desc')->first();

            if ($inventario->id == null) {
                $inventario = new Inventario_piso_venta();
                $inventario->inventario_id = $producto->id;
                $inventario->piso_venta_id = $usuario;
                $inventario->cantidad = $valor->pivot->cantidad;
                $inventario->save();
            }else{
                //SI ES UN DESPACHO O ES UN RETIRO
                if($despacho->type == 1){


                        $inventario->cantidad += $valor->pivot->cantidad;

                }else{

                        $inventario->cantidad -= $valor->pivot->cantidad;

                }
            }
            $inventario->save();


        }

        return response()->json($despacho);
    }

    public function negar_despacho(Request $request)
    {

        $despacho = Despacho::with(['productos' => function($articulo){

        }])->findOrFail($request->id);
        $despacho->confirmado = 0;
        $despacho->save();
        /*
        foreach ($despacho->productos as $valor) {

            $producto = Inventario_piso_venta::whereHas('inventario', function($q){
                    $q->where('inventory_id', $valor->pivot->inventory_id);
                })->orderBy('id', 'desc')->first();

            $inventario = Inventario_piso_venta::with('inventario')->where('piso_venta_id', $usuario)->where('inventario_id', $producto->id)->orderBy('id', 'desc')->first();

            if ($inventario->id == null) {
                $inventario = new Inventario_piso_venta();
                $inventario->inventario_id = $producto->id;
                $inventario->piso_venta_id = $usuario
                $inventario->cantidad = $valor->pivot->cantidad;
                $inventario->save();
            }else{
                //SI ES UN DESPACHO O ES UN RETIRO
                if($despacho->type == 1){


                        $inventario->cantidad += $valor->pivot->cantidad;

                }else{

                        $inventario->cantidad -= $valor->pivot->cantidad;

                }
            }
            $inventario->save();

        }
        */

        return response()->json($despacho);
    }
    //A PARTIR DE AQUI ES EL BOYON REFRESCAR
    public function ultimo_despacho()
    {
        $usuario = Auth::user()->piso_venta->id;

        $despacho = Despacho::select('id_extra')->where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();

        return response()->json($despacho);
    }

    public function get_despachos_web(Request $request)//DEL LADO DE LA WEB
    {

        $despachos = Despacho::with('productos', 'productos.product')->where('piso_venta_id', $request->piso_venta_id)->where('id_extra', '>', $request->ultimo_despacho)->get();

        return response()->json($despachos);

    }

    public function registrar_despacho_piso_venta (Request $request)
    {
        try{

            DB::beginTransaction();

            foreach ($request->despachos as $despacho){
                //REGISTRAMOS EL DESPACHO
                $registro = new Despacho();
                $registro->id_extra = $despacho['id_extra'];
                $registro->piso_venta_id = $despacho['piso_venta_id'];
                $registro->type = $despacho['type'];
                $registro->save();

                foreach ($despacho['productos'] as $producto) {
                    //REGISTRAMOS LOS PRODUCTOS
                    $detalles = new Despacho_detalle();
                    $detalles->despacho_id = $registro->id;
                    $detalles->cantidad = $producto['pivot']['cantidad'];
                    $detalles->inventory_id = $producto['pivot']['inventory_id'];
                    $detalles->save();

                    //SUMAMOS AL STOCK
                    $inventario = Inventario_piso_venta::whereHas('inventario', function($q)use($producto){
                        $q->where('inventory_id', $producto['pivot']['inventory_id']);
                    })->where('piso_venta_id', $despacho['piso_venta_id'])->orderBy('id', 'desc')->first();
                    //SI NO ENCUENTRA EL PRODUCTO LO REGISTRA
                    if ($inventario['id'] == null) {
                        $articulo = new Inventario();
                        $articulo->name = $producto['product_name'];
                        $articulo->unit_type_mayor = $producto['unit_type'];
                        $articulo->unit_type_menor = $producto['unit_type_menor'];
                        $articulo->inventory_id = $producto['pivot']['inventory_id'];
                        $articulo->status = $producto['status'];
                        $articulo->piso_venta_id = $despacho['piso_venta_id'];
                        $articulo->save();
                        //$articulo->id_extra = $articulo->id;
                        //$articulo->save();
                        //REGISTRAMOS LOS PRECIOS
                        $precio = new Precio();
                        $precio->costo = $producto['product']['cost'];
                        $precio->iva_porc = $producto['product']['iva_percent'];
                        $precio->iva_menor = $producto['product']['retail_iva_amount'];
                        $precio->sub_total_menor = $producto['product']['retail_total_price'] - $producto['product']['retail_iva_amount'];
                        $precio->total_menor = $producto['product']['retail_total_price'];
                        $precio->iva_mayor = $producto['product']['wholesale_iva_amount'] * $producto['qty_per_unit'];
                        $precio->sub_total_mayor = $producto['product']['wholesale_packet_price'];
                        $precio->total_mayor = $precio->sub_total_mayor + $precio->iva_mayor;
                        $precio->oferta = $producto['product']['oferta'];
                        $precio->inventario_id = $articulo->id;
                        $precio->save();
                        //$precio->costo =
                        //REGISTRA LA CANTIDAD EN EL INVENTARIO DEL PISO DE VENTA
                        /*
                        $inventario = new Inventario_piso_venta();
                        $inventario->inventario_id = $articulo->id;
                        $inventario->piso_venta_id = $despacho['piso_venta_id'];
                        $inventario->cantidad = $producto['pivot']['cantidad'];
                        $inventario->save();
                        */
                    }else{
                        //SI ES UN DESPACHO O UN RETIRO
                        /*
                        if($registro->type == 1){
                            $inventario->cantidad += $producto['pivot']['cantidad'];
                            $inventario->save();
                        }else{
                            $inventario->cantidad -= $producto['pivot']['cantidad'];
                            $inventario->save();
                        }
                        */
                    }

                }
            }

            DB::commit();

            return response()->json(true);

        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }



        return response()->json(true);
    }

    public function get_despachos_sin_confirmacion($id)//DEL LADO DE LA WEB
    {
        $despachos = Despacho::select('id_extra')->where('piso_venta_id', $id)->where('confirmado', null)->get();

        return response()->json($despachos);
    }

    public function get_despachos_confirmados(Request $request)//RECIBE EL RESULTADO DEL METODO ANTERIOR
    {
        $despachos = [];

        foreach ($request->despachos as $valor) {

            $despachos[] = Despacho::with('productos')->where('id_extra', $valor['id_extra'])->first();
        }


        return response()->json($despachos);
    }

    public function actualizar_confirmaciones(Request $request)//DEL LADO DE LA WEB
    {
        try{

            DB::beginTransaction();

            foreach ($request->despachos as $valor) {

                $despacho = Despacho::with('productos')->where('id_extra', $valor['id_extra'])->where('piso_venta_id', $request->piso_venta_id)->first();
                $despacho->confirmado = $valor['confirmado'];
                $despacho->save();

                foreach ($valor['productos'] as $detalle) {


                    if ($despacho->type == 1) {

                            if ($despacho->confirmado === 0) {//NEGADO
                                //RESTAMOS DE INVENTORY DE PROMETHEUS
                                $inventario = Inventory::findOrFail($detalle['id']);
                                $inventario->total_qty_prod += $detalle['pivot']['cantidad'];
                                $inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;
                                $inventario->save();


                                //RESTAMOS DEL INVENTARIO DEL PISO DE VENTA AL STOCK
                                $inventario = Inventario_piso_venta::whereHas('inventario', function($q)use($detalle, $despacho){
                                    $q->where('inventory_id', $detalle['id']);
                                })->where('piso_venta_id', $despacho->piso_venta_id)->orderBy('id', 'desc')->first();

                                $inventario->cantidad -= $detalle['pivot']['cantidad'];
                                $inventario->save();
                            }

                    }else if($despacho->type == 2){

                            if ($despacho->confirmado === 0) {//NEGADO
                                //SUMAMOS DE INVENTORY DE PROMETHEUS
                                $inventario = Inventory::findOrFail($detalle['id']);
                                $inventario->total_qty_prod -= $detalle['pivot']['cantidad'];
                                $inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;
                                $inventario->save();

                                //SUMAMOS DEL INVENTARIO DEL PISO DE VENTA AL STOCK
                                $inventario = Inventario_piso_venta::whereHas('inventario', function($q)use($detalle, $despacho){
                                    $q->where('inventory_id', $detalle['id']);
                                })->where('piso_venta_id', $despacho->piso_venta_id)->orderBy('id', 'desc')->first();
                                $inventario->cantidad += $detalle['pivot']['cantidad'];
                                $inventario->save();
                            }

                    }
                }


            }

            DB::commit();

            return response()->json("actualizado");

        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
    }

    public function index_almacen()
    {

    	return view('admin.index_almacen');
    }

    public function get_datos_create()
    {
        $piso_ventas = Piso_venta::all();

        $productos = Inventory::with('product')->where('status', 1)->get();;

        return response()->json(["piso_ventas" => $piso_ventas, "productos" => $productos]);
    }

    public function get_despachos_almacen()
    {

        $despachos = Despacho::with(['productos' => function($producto){
            $producto->select('product_name');
        }, 'piso_venta'])->orderBy('id', 'desc')->paginate(1);

        return response()->json(['pagination' => [
                                    'total' => $despachos->total(),
                                    'current_page' => $despachos->currentPage(),
                                    'per_page' => $despachos->perPage(),
                                    'last_page' => $despachos->lastPage(),
                                    'from' => $despachos->firstItem(),
                                    'to' => $despachos->lastPage(),
                                ],
                                'despachos' => $despachos
                                ]);
    }

    public function store(Request $request)
    {
        try{

            DB::beginTransaction();

            $despacho = new Despacho();
            $despacho->piso_venta_id = $request->piso_venta;
            $despacho->type = 1;
            $despacho->save();

            $despacho->id_extra = $despacho->id;
            $despacho->save();

            foreach ($request->productos as $producto) {
                $detalles = new Despacho_detalle();
                $detalles->despacho_id = $despacho->id;
                $detalles->cantidad = $producto['cantidad'];
                $detalles->inventory_id = $producto['id'];
                $detalles->save();

                //REGISTRAR EN INVENTARIO Y PRECIO FALTA
                //SUMAMOS AL STOCK
                $inventario = Inventario_piso_venta::whereHas('inventario', function($q)use($producto){
                    $q->where('inventory_id', $producto['id']);
                })->where('piso_venta_id', $despacho['piso_venta_id'])->orderBy('id', 'desc')->first();
                //SI NO ENCUENTRA EL PRODUCTO LO REGISTRA
                if (empty($inventario['id'])){
                    $articulo = new Inventario();
                    $articulo->name = $producto['modelo']['product_name'];
                    $articulo->unit_type_mayor = $producto['modelo']['unit_type'];
                    $articulo->unit_type_menor = $producto['modelo']['unit_type_menor'];
                    $articulo->inventory_id = $producto['id'];
                    $articulo->status = $producto['modelo']['status'];
                    $articulo->piso_venta_id = $request->piso_venta;
                    $articulo->save();
                    //REGISTRAMOS LOS PRECIOS
                    $precio = new Precio();
                    $precio->costo = $producto['modelo']['product']['cost'];
                    $precio->iva_porc = $producto['modelo']['product']['iva_percent'];
                    $precio->iva_menor = $producto['modelo']['product']['retail_iva_amount'];
                    $precio->sub_total_menor = $producto['modelo']['product']['retail_total_price'] - $producto['modelo']['product']['retail_iva_amount'];
                    $precio->total_menor = $producto['modelo']['product']['retail_total_price'];
                    $precio->iva_mayor = $producto['modelo']['product']['wholesale_iva_amount'] * $producto['modelo']['qty_per_unit'];
                    $precio->sub_total_mayor = $producto['modelo']['product']['wholesale_packet_price'];
                    $precio->total_mayor = $precio->sub_total_mayor + $precio->iva_mayor;
                    $precio->oferta = $producto['modelo']['product']['oferta'];
                    $precio->inventario_id = $articulo->id;
                    $precio->save();
                    //$precio->costo =
                    //REGISTRA LA CANTIDAD EN EL INVENTARIO DEL PISO DE VENTA
                    $inventario = new Inventario_piso_venta();
                    $inventario->inventario_id = $articulo->id;
                    $inventario->piso_venta_id = $despacho['piso_venta_id'];
                    $inventario->cantidad = $producto['cantidad'];
                    $inventario->save();
                }else{
                    //SI ES UN DESPACHO O UN RETIRO
                    if($despacho->type == 1){
                        $inventario->cantidad += $producto['cantidad'];
                        $inventario->save();
                    }else{
                        $inventario->cantidad -= $producto['cantidad'];
                        $inventario->save();
                    }
                }
            }

            $despacho = Despacho::with(['productos' => function($producto){
                $producto->select('product_name');
            }, 'piso_venta'])->findOrFail($despacho->id);

            DB::commit();

            return response()->json($despacho);

        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
    }

    public function store_retiro(Request $request)
    {
        try{

            DB::beginTransaction();

            $despacho = new Despacho();
            $despacho->piso_venta_id = $request->piso_venta;
            $despacho->type = 2;
            $despacho->save();

            $despacho->id_extra = $despacho->id;
            $despacho->save();

            foreach ($request->productos as $producto) {
                $detalles = new Despacho_detalle();
                $detalles->despacho_id = $despacho->id;
                $detalles->cantidad = $producto['cantidad'];
                $detalles->inventory_id = $producto['id'];
                $detalles->save();
                $detalles->save();
                //SUMAMOS DE INVENTORY DE PROMETHEUS
                $inventario = Inventory::findOrFail($producto['id']);
                $inventario->total_qty_prod += $producto['cantidad'];
                $inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;
                $inventario->save();

                //SUMAMOS AL STOCK
                $inventario = Inventario_piso_venta::whereHas('inventario', function($q)use($producto){
                    $q->where('inventory_id', $producto['id']);
                })->where('piso_venta_id', $despacho['piso_venta_id'])->orderBy('id', 'desc')->first();
                //SI NO ENCUENTRA EL PRODUCTO LO REGISTRA
                if ($inventario['id'] == null) {
                    $articulo = new Inventario();
                    $articulo->name = $producto['modelo']['product_name'];
                    $articulo->unit_type_mayor = $producto['modelo']['unit_type'];
                    $articulo->unit_type_menor = $producto['modelo']['unit_type_menor'];
                    $articulo->inventory_id = $producto['id'];
                    $articulo->status = $producto['modelo']['status'];
                    $articulo->piso_venta_id = $request->piso_venta;
                    $articulo->save();
                    //REGISTRAMOS LOS PRECIOS
                    $precio = new Precio();
                    $precio->costo = $producto['modelo']['product']['cost'];
                    $precio->iva_porc = $producto['modelo']['product']['iva_percent'];
                    $precio->iva_menor = $producto['modelo']['product']['retail_iva_amount'];
                    $precio->sub_total_menor = $producto['modelo']['product']['retail_total_price'] - $producto['modelo']['product']['retail_iva_amount'];
                    $precio->total_menor = $producto['modelo']['product']['retail_total_price'];
                    $precio->iva_mayor = $producto['modelo']['product']['wholesale_iva_amount'] * $producto['modelo']['qty_per_unit'];
                    $precio->sub_total_mayor = $producto['modelo']['product']['wholesale_packet_price'];
                    $precio->total_mayor = $precio->sub_total_mayor + $precio->iva_mayor;
                    $precio->oferta = $producto['modelo']['product']['oferta'];
                    $precio->inventario_id = $articulo->id;
                    $precio->save();
                    //$precio->costo =
                    //REGISTRA LA CANTIDAD EN EL INVENTARIO DEL PISO DE VENTA
                    $inventario = new Inventario_piso_venta();
                    $inventario->inventario_id = $articulo->id;
                    $inventario->piso_venta_id = $despacho['piso_venta_id'];
                    $inventario->cantidad = $producto['cantidad'];
                    $inventario->save();
                }else{
                    //SI ES UN DESPACHO O UN RETIRO
                    if($despacho->type == 1){
                        $inventario->cantidad += $producto['cantidad'];
                        $inventario->save();
                    }else{
                        $inventario->cantidad -= $producto['cantidad'];
                        $inventario->save();
                    }
                }
            }

            $despacho = Despacho::with(['productos' => function($producto){
                $producto->select('product_name');
            }, 'piso_venta'])->findOrFail($despacho->id);

            DB::commit();

            return response()->json($despacho);

        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
    }

    public function get_datos_inventario_piso_venta($id)
    {
        $piso_ventas = Piso_venta::with('inventario')->findOrFail($id);
        $productos = Inventario::with('piso_venta')->whereHas('piso_venta', function($q)use($id){
            $q->where('piso_venta_id', $id);
            $q->where('cantidad', '>', 0);
        })->where('inventory_id', '!=' , null)->get();
        return response()->json($productos);
    }
}
