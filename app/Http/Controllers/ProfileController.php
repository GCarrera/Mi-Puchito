<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sale;
use App\User;
use App\State;
use App\People;
use App\Wishlist;
use App\SaleDetail;
use App\TravelRate;
use App\AddressUserDelivery;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Validator;


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

	public function editar_perfil(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
			'email' => 'required|email',
			'phone' => 'required|digits:7',
			'password' => 'nullable|confirmed|min:6'
		]);
		
        if ($validator->fails()) {
            return redirect('perfil')
                        ->withErrors($validator)
                        ->withInput();
        }

		$user = User::where('id', $request->id)->first();
		$user->email = $request->email;
		if ($request->password != '') {
			$user->password = bcrypt($request->password);
		}
		$people = People::where('id', $user->people_id)->first();
		$people->name = $request->name;
		$people->phone_number = $request->phone;
		$people->save();
		$user->save();

		return redirect()->back()->withSuccess('Datos guardados exitosamente!');
	}
}