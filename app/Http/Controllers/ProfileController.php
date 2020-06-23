<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\State;
use App\TravelRate;
use App\AddressUserDelivery;
use App\Sale;
use App\SaleDetail;
use App\Wishlist;

class ProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer');
	}

	public function perfil()
	{
		$user     = auth()->user();
		$sectores = TravelRate::all();
		$rates    = AddressUserDelivery::where('user_id', $user->id)->get();
		$pedidos  = Sale::where('user_id', $user->id)->get();
		$detalles = [];

		$pedidosCount  = count($pedidos);
		$wishlistCount = Wishlist::where('user_id', $user->id)->count();

		foreach ($pedidos as $pedido) {
			$detalles[] = SaleDetail::where('sale_id', $pedido->id)->get();
			$pedido->details = $detalles;
		}

		return view('customer.perfil')
			->with('pedidosCount', $pedidosCount)
			->with('wishlistCount', $wishlistCount)
			->with('pedidos', $pedidos)
			->with('sectors', $sectores)
			->with('rates', $rates)
			->with('user', $user);
	}
}
