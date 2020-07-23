<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\AddressUserDelivery;
use App\BankAccount;

class ShoppingCartController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer');
	}

	public function index()
	{
		$user = auth()->user();
		// \Cart::session($userId)->clear();
		$data   = \Cart::session($user->id)->getContent();
		$total  = \Cart::session($user->id)->getTotal();
		
		$ivatotal    = 0;
		$totalSinIva = 0;


		foreach ($data as $d) {
			
			if ($d->attributes->sale_type == 'al-mayor') {
				$t = $d->attributes->wholesale_iva_amount * $d->quantity;
				$ivatotal += $t;
				
				$r = $d->attributes->wholesale_total_packet_price * $d->quantity;
				$totalSinIva += $r;
			}
			else {
				$t = $d->attributes->retail_iva_amount * $d->quantity;
				$ivatotal+= $t;
				
				$r = $d->attributes->retail_pvp * $d->quantity;
				$totalSinIva += $r;
			}
		}

		$user_address = AddressUserDelivery::where('user_id', $user->id)->get();
		$cb = BankAccount::all();

		return view('customer.shopping_cart')
				->with('cart', $data)
				->with('user_address', $user_address)
				->with('cb', $cb)
				->with('user', $user)
				->with('ivatotal', $ivatotal)
				->with('totalSinIva', $totalSinIva)
				->with('total', $total);
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
		
		$userId   = auth()->user()->id;


		$data     = \Cart::session($userId)->get($id);

		if (isset($data)) {
			if ($data->attributes->sale_type != $request->input('type')) {
				return 'rejected';
			}
		}

		\Cart::session($userId)->add([
			'id'         => $producto->id,
			'name'       => $producto->inventory->product_name,
			'price'      => $price,
			'quantity'   => $quantity,
			'attributes' => [
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
			],
    		'associatedModel' => $producto
		]);

		return \Cart::session($userId)->getTotalQuantity();
	}

	public function update(Request $request, $rowId)
	{
		$userId = auth()->user()->id;

		\Cart::session($userId)->update($rowId, [
			'quantity' => [
				'relative' => false,
				'value'    => $request->input('quantity')
			],
		]);

		return \Cart::session($userId)->getTotalQuantity();
	}

	public function get_shoppingcart()
	{
		$userId = auth()->user()->id;

		return \Cart::session($userId)->getTotalQuantity();
	}


	public function destroy($id)
	{
		$userId = auth()->user()->id;

		\Cart::session($userId)->remove($id);

		return \Cart::session($userId)->getTotalQuantity();
	}

	public function clear()
	{
		$userId = auth()->user()->id;
		\Cart::session($userId)->clear();

		return \Cart::session($userId)->getTotalQuantity();
	}
}
