<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\AddressUserDelivery;
use App\BankAccount;
use App\City;
use App\Sector;
use App\State;
use App\Dolar;
use App\Wishlist;

class ShoppingCartController extends Controller
{
	public function __construct()
	{
		//$this->middleware('customer')->except('prueba');
	}

	public function index()
	{
		$user = [];
		//$user = auth()->user();
		// \Cart::session($userId)->clear();
		$data   = \Cart::content();
		$total  = \Cart::subtotal();
		
		$ivatotal    = 0;
		$totalSinIva = 0;


		foreach ($data as $d) {
			
			if ($d->options->sale_type == 'al-mayor') {
				$t = $d->options->wholesale_iva_amount * $d->quantity;
				$ivatotal += $t;
				
				$r = $d->options->wholesale_total_packet_price * $d->quantity;
				$totalSinIva += $r;
			} else {
				$t = $d->options->retail_iva_amount * $d->quantity;
				$ivatotal+= $t;
				
				$r = $d->options->retail_pvp * $d->quantity;
				$totalSinIva += $r;
			}
		}
		$user_address = [];
		//$user_address = AddressUserDelivery::where('user_id', $user->id)->get();
		$cb = BankAccount::all();
		$cities = City::where('state_id', 4)->where('id', 44)->get(); //4 es aragua y 44 cagua

		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		return view('customer.shopping_cart')
				->with('cart', $data)
				->with('user_address', $user_address)
				->with('cb', $cb)
				->with('user', $user)
				->with('ivatotal', $ivatotal)
				->with('totalSinIva', $totalSinIva)
				->with('cities', $cities)
				->with('total', $total)
				->with('dolar', $dolar);
	}

	public function store(Request $request)
	{
		$id       = $request->input('id');
		$quantity = $request->input('cantidad');
		$price    = $request->input('precio');
		$type     = $request->input('type');

		$producto = Product::where('inventory_id', $id)
			->with('inventory')
			->first();
		
		//$userId   = auth()->user()->id;

		$data = \Cart::content();

		//return $data;

		foreach ($data as $value) {
			//SI EL PRODUCTO YA ESTA REGISTRADO EN EL CARRO Y TIENE EL MISMO TIPO DE COMPRA QUE SI AL MAYOR O AL MENOR
			if ($value->model->id == $producto->id && $value->options->sale_type == $request->input('type')) {
				# code...
				return 'rejected';
			}
					
					
		}

		/*
		if (isset($data)) {
			if ($data->options->sale_type != $request->input('type')) {
				return 'rejected';
			}
		}
		*/		

		\Cart::add([
			'id' => $producto->id,
			'name'       => $producto->inventory->product_name,
			'qty'   => $quantity,
			'price'      => $price,
			'options' => 	[
				'sale_type'  => $type,
				'image'      => $producto->image,
				'iva'        => $producto->iva_percent,
				'retail_iva_amount'      => $producto->retail_iva_amount,
				'cost'                   => $producto->cost,
				// 'wholesale_pvp'          => $producto->wholesale_pvp,
				'wholesale_iva_amount'   => $producto->wholesale_iva_amount,
				'wholesale_packet_price' => $producto->wholesale_packet_price,
				'wholesale_total_packet_price' => $producto->wholesale_total_packet_price,
				'wholesale_quantity'     => $producto->inventory->qty_per_unit
			]
		])->associate($producto);

		return count(\Cart::content());
	}

	public function update(Request $request, $rowId)
	{
		//$userId = auth()->user()->id;
		
		$carrito = \Cart::update($rowId, [
			'qty' => $request->qty
		]);

		return \Cart::content();
	}

	public function get_shoppingcart()
	{
		//$userId = auth()->user()->id;

		return count(\Cart::content());
	}


	public function destroy($id)
	{
		//$userId = auth()->user()->id;

		\Cart::remove($id);

		return count(\Cart::content());
	}

	public function clear()
	{
		//$userId = auth()->user()->id;
		\Cart::destroy();

		return count(\Cart::content());
	}

	public function getCity(Request $request)
	{
		$cities = City::where('state_id', $request->state_id)->get();
		return response()->json($cities);
	}

	public function getSector(Request $request)
	{
		$cities = Sector::where('city_id', $request->city_id)->get();
		return response()->json($cities);
	}

	public function prueba(Request $request)
	{	
		$producto = Product::with('inventory')->findOrFail(1);
		$carrito = \Cart::content();
		//\Cart::destroy();
		//$carrito = \Cart::add('1', 'Product 1', 1, 9.99, ['size' => 'large'])->associate($producto);
		return $carrito;
		return $carrito->model->inventory;
    	
	}
}