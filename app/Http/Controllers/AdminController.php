<?php

/*

REGISTRAR UN CODIGO DE FACTURA
MANEJAR TIPO DE VENTAS
HACER UNA FACTURA Y QUE SE DFESCASRGUE EN EL CLIENTE
DETALLES DE LA VENTA
NOTIFICACION EN EL ASDMIN CUANDO SE HAGA UNA VENTA
FILTRAR VENTAS POR EL CODIGO DE LA FACTURA
REGISTRAR Y ASIGNAR USUARIOS


*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TravelRate;
use App\Enterprise;
use App\Inventory;
use App\Category;
use App\Product;
use App\State;
use App\Sale;
use App\Delivery;
use App\SaleDetail;
use App\AddressUserDelivery;
use Carbon\Carbon;
use App\Dolar;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
	public function __construct()
	{
		// $this->middleware('admin')->except('traer_productos');
	}

	public function index(Request $request)
	{
		// $empresasCount   = Enterprise::all()->count();
		// $categoriasCount = Category::all()->count();
		// $productosCount  = Product::all()->count();
		// $salesCount      = Sale::all()->count();
		$now = Carbon::now()->toDateString();
		
		if ($request->fechas) {

			$fechas = explode('-', $request->fechas);

			$fecha_inicial = new Carbon($fechas[0]);
			$fecha_final = new Carbon($fechas[1]);

			$ventas = Sale::with(['deliveries', 
			'details' => function($details) {
				$details->with(['inventory' => function ($inventory) {
					$inventory->select('id',	'product_name');
				}]);
			}, 'user' => function($user) {
				$user->select('id', 'people_id')->with(['people' => function($people) {
					$people->select('id', 'name');
				}]);
			}])
			->whereHas('details')
			->where('delivery', 'no')
			->orderBy('id', 'desc')
			->whereDate('created_at', '>=', $fecha_inicial)
			->whereDate('created_at', '<=', $fecha_final)
			->paginate();
		}else{

		$ventas = Sale::with(['deliveries', 
		'details' => function($details) {
			$details->with(['inventory' => function ($inventory) {
				$inventory->select('id',	'product_name');
			}]);
		}, 'user' => function($user) {
			$user->select('id', 'people_id')->with(['people' => function($people) {
				$people->select('id', 'name');
			}]);
		}])
		->whereHas('details')
		->where('delivery', 'no')
		->whereDate('created_at', $now)
		->orderBy('id', 'desc')
		->paginate();
		}
		
		return view('admin.index')
			->with('ventas', $ventas);
			// ->with('empresasCount', $empresasCount)
			// ->with('productosCount', $productosCount)
			// ->with('salesCount', $salesCount)
			// ->with('categoriasCount', $categoriasCount);
	}

	public function inventario()
	{
		$categorias      = Category::all();
		$empresas        = Enterprise::all();
		// $empresasCount   = count($empresas);
		// $categoriasCount = count($categorias);
		// $salesCount      = Sale::all()->count();
		// $productosCount  = Product::all()->count();
		$almacen         = 'activar un almacen por defecto';

		$inventario = Inventory::orderBy('id', 'desc')->paginate();

		if (count($inventario) > 0) {
			$almacen = $inventario[0]->warehouse->name;
		}

		return view('admin.inventario')
			->with('inventario', $inventario)
			->with('almacen', $almacen)
			->with('empresas', $empresas)
			->with('categorias', $categorias);
			// ->with('empresasCount', $empresasCount)
			// ->with('productosCount', $productosCount)
			// ->with('salesCount', $salesCount)
			// ->with('categoriasCount', $categoriasCount);
	}

	public function compra_venta()
	{
		// $empresasCount   = Enterprise::all()->count();
		// $categoriasCount = Category::all()->count();
		// $productosCount  = Product::all()->count();
		// $salesCount      = Sale::all()->count();

		$inventario = Inventory::orderBy('id', 'desc')->where('status', 2)->get();
		$productos  = Product::paginate();
		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		return view('admin.costos')
			->with('inventario', $inventario)
			->with('productos', $productos)
			->with('dolar', $dolar);
			// ->with('empresasCount', $empresasCount)
			// ->with('productosCount', $productosCount)
			// ->with('salesCount', $salesCount)
			// ->with('categoriasCount', $categoriasCount);
	}

	public function empresa_categorias()
	{
		$categorias      = Category::all();
		$empresas        = Enterprise::all();
		// $empresasCount   = count($empresas);
		// $productosCount  = Product::all()->count();
		// $salesCount      = Sale::all()->count();
		// $categoriasCount = count($categorias);

		return view('admin.empresa_categoria')
			->with('empresas', $empresas)
			->with('categorias', $categorias);
			// ->with('empresasCount', $empresasCount)
			// ->with('productosCount', $productosCount)
			// ->with('salesCount', $salesCount)
			// ->with('categoriasCount', $categoriasCount);
	}

	public function delivery(Request $request)
	{
		// $empresasCount   = Enterprise::all()->count();
		// $categoriasCount = Category::all()->count();
		// $productosCount  = Product::all()->count();
		// $salesCount      = Sale::all()->count();
		//$estados = State::all();
		//$rates   = TravelRate::all();

		//return view('admin.delivery')
			// ->with('empresasCount', $empresasCount)
			// ->with('categoriasCount', $categoriasCount)
			// ->with('productosCount', $productosCount)
			// ->with('salesCount', $salesCount)
		//	->with('rates', $rates)
		//	->with('estados', $estados)
		//	->with('user_address', $user_address);

		$now = Carbon::now()->toDateString();
		
		if ($request->fechas) {

			$fechas = explode('-', $request->fechas);

			$fecha_inicial = new Carbon($fechas[0]);
			$fecha_final = new Carbon($fechas[1]);

			$ventas = Sale::with(['deliveries', 
			'details' => function($details) {
				$details->with(['inventory' => function ($inventory) {
					$inventory->select('id',	'product_name');
				}]);
			}, 'user' => function($user) {
				$user->select('id', 'people_id')->with(['people' => function($people) {
					$people->select('id', 'name');
				}]);
			}])
			->whereHas('details')
			->where('delivery', 'si')
			->orderBy('id', 'desc')
			->whereDate('created_at', '>=', $fecha_inicial)
			->whereDate('created_at', '<=', $fecha_final)
			->paginate();
		}else{	

			$ventas = Sale::with(['deliveries', 
			'details' => function($details) {
				$details->with(['inventory' => function ($inventory) {
					$inventory->select('id',	'product_name');
				}]);
			}, 'user' => function($user) {
				$user->select('id', 'people_id')->with(['people' => function($people) {
					$people->select('id', 'name');
				}]);
			}])
			->whereHas('details')
			->where('delivery', 'si')
			->orderBy('id', 'desc')
			->whereDate('created_at', $now)
			->paginate();
		}

		return view('admin.delivery')
			->with('ventas', $ventas);
	}

	public function traer_productos()
	{
		$productos = Inventory::all();
		$data = [];

		foreach ($productos as $key => $value) {
			$data[] = $value->product_name;
		}

		return $data;
	}

	// ---------------- CRUD DE EMPRESAS Y CATEGORIAS ----------------------

	public function traer_empresa(Request $req)
	{
		return Enterprise::find($req->id);
	}

	public function registrar_empresa(Request $req)
	{
		$enterprise = new Enterprise;
		$enterprise->name = $req->name;
		$enterprise->save();

		return redirect()->back()->with('success', 'Empresa registrada con éxito.');
	}

	public function editar_empresa(Request $req)
	{
		$category = Enterprise::find($req->empresaid);
		$category->name = $req->name;
		$category->save();

		return redirect()->back()->with('success', 'Empresa editada con éxito.');
	}

	public function eliminar_empresa(Request $req)
	{
		Enterprise::destroy($req->empresaid);

		return redirect()->back()->with('success', 'Empresa eliminada con éxito.');
	}


	public function traer_categoria(Request $req)
	{
		return Category::find($req->id);
	}

	public function registrar_categoria(Request $req)
	{
		$category = new Category;
		$category->name = $req->name;
		$category->save();

		return redirect()->back()->with('success', 'Categoria registrada con éxito.');
	}

	public function editar_categoria(Request $req)
	{
		$category = Category::find($req->categoriaid);
		$category->name = $req->name;
		$category->save();

		return redirect()->back()->with('success', 'Categoria editada con éxito.');
	}

	public function eliminar_categoria(Request $req)
	{
		Category::destroy($req->categoriaid);

		return redirect()->back()->with('success', 'Categoria eliminada con éxito.');
	}

	// ------------------------------------------------------------------------------------------------

	public function confirmar_pedido($id, Request $request)
	{
		$now = Carbon::now();
		$venta = Sale::with('details')->findOrFail($id);

		if ($request->confirmacion != "aprobado") {
			//return "confirmado";

			foreach ($venta->details as $detalle) {
				//BUSCAMOS EL PRODUCTO DEL CARRO
				$producto = Product::findOrFail($detalle->product_id);
				$inventario = Inventory::findOrFail($producto->inventory_id);
				//SI LA COMPRA ES AL MENOR
				if ($detalle->type == "al-menor") {

					
					$inventario->total_qty_prod += $detalle->quantity;

					$inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;

	
				}
				//SI LA COMPRA ES AL MAYOR
				if ($detalle->type == "al-mayor") {

						
						$inventario->quantity += $detalle->quantity;
						$inventario->total_qty_prod = $inventario->quantity * $inventario->qty_per_unit;

				}

				$inventario->save();
					

			}
		}else{

			if ($venta->confirmacion == "aprobado") {
				return "confirmado";
			}


		}
		$confirmacion = $venta->dispatched = $now;
		$venta->confirmacion = $request->confirmacion;
		$venta->save();

			return $venta->confirmacion;
		
	}

	public function confirmar_pedido_delivery($id, Request $request)
	{

		$now = Carbon::now();

		$venta = Sale::with('details')->findOrFail($id);

		if ($request->confirmacion != "aprobado") {
			
			foreach ($venta->details as $detalle) {
				//BUSCAMOS EL PRODUCTO DEL CARRO
				$producto = Product::findOrFail($detalle->product_id);
				$inventario = Inventory::findOrFail($producto->inventory_id);
				//SI LA COMPRA ES AL MENOR
				if ($detalle->type == "al-menor") {

					
					$inventario->total_qty_prod += $detalle->quantity;

					$inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;

	
				}
				//SI LA COMPRA ES AL MAYOR
				if ($detalle->type == "al-mayor") {

						
						$inventario->quantity += $detalle->quantity;
						$inventario->total_qty_prod = $inventario->quantity * $inventario->qty_per_unit;

				}

				$inventario->save();
					

			}

		}else{

			if ($venta->confirmacion == "aprobado") {
				return "confirmado";
			}

			if ($request->stimated_time == "") {
				return "el campo tiempo estimado es obligatorio";
			}
			
		}

			$confirmacion = $venta->dispatched = $now;
			$venta->confirmacion = $request->confirmacion;
			$venta->stimated_time = $request->stimated_time;
			$venta->save();
			


			return $venta->confirmacion;
		
	}

	public function get_dolar()
	{
		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		return $dolar;
	}

	public function establecer_dolar(Request $request)
	{
		$dolar = new Dolar();

		$dolar->price = $request->precio;
		$dolar->save();

		return redirect()->back()->with('success', 'Nuevo precio del dolar establecido.');
	}
}
