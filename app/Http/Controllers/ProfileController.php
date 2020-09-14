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
use App\City;
use App\Product;
use App\Dolar;

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
		$pedidos  = Sale::orderBy('id', 'desc')->where('user_id', $user->id)->get();
		$detalles = [];

		$pedidosCount  = count($pedidos);
		$wishlistCount = Wishlist::where('user_id', $user->id)->count();


		$cities = City::where('state_id', 4)->where('id', 44)->get(); //4 es aragua y 44 cagua

		//PRODUCTOS EN OFERTA

		$productos = Product::with(['inventory'])->where('oferta', 1)->paginate();

		$user = auth()->user();

		$carrito   = \Cart::content();

		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		return view('customer.perfil')
			->with('pedidosCount', $pedidosCount)
			->with('wishlistCount', $wishlistCount)
			->with('pedidos', $pedidos)
			->with('sectors', $sectores)
			->with('rates', $rates)
			->with('user', $user)
			->with('cities', $cities)
			->with('productos', $productos)
			->with('carrito', $carrito)
			->with('dolar', $dolar);
	}

	public function editar_perfil(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
            'name' => 'max:255',
			'email' => 'email',
			'phone' => 'digits:11',
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

	public function getDni(Request $request)
	{
		$texto = file_get_contents("http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula=".$request->dni);
		$pos_name = strpos($texto, "Nombre:");
		if (!$pos_name) {
			return response()->json(['result' => false, 'name' => '']);
		}
		$pos_init_name = strpos($texto, "<b>", $pos_name);
		$pos_end_name =  strpos($texto, "</b>", $pos_init_name);
		$name = substr($texto, $pos_init_name, $pos_end_name-$pos_init_name);
		$name = str_replace("<b>", "", $name);

		return response()->json(['result' => true, 'name' => $name]);
	}
}