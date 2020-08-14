<?php

// 24432495 => wifi passwd

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Sale;
use App\Delivery;
use App\SaleDetail;
use App\AddressUserDelivery;
use App\TravelRate;

class SaleController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer');
	}

	public function get_sale(Request $req)
	{  
		$sale = Sale::find($req->input('id'));
		
		$detalles = SaleDetail::where('sale_id', $req->input('id'))->get();
		$sale->details = $detalles;

		$sale->user;
		$sale->details;
		// $sale->details->products;
		$sale->user->people;

		return $sale;
	}

	public function store(Request $req)
	{

		$validator = Validator::make($req->all(), [
            'delivery' => 'required|max:191',
            'numero_referencia' => 'max:191',
			'monto' => 'required|max:191',
			'pay_method' => 'required',
		]);
		
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
		}
		
		$user     = auth()->user()->id;
		$productos = \Cart::session($user)->getContent();
		if (count($productos) > 0) {	

			$code     = '20-123321213';
			// 

			$sale = new Sale();
			$sale->code     = $code;
			$sale->payment_type     = $req->input('pay_method');
			$sale->amount   = $req->input('monto');
			$sale->payment_reference_code = $req->input('numero_referencia');
			$sale->dolar_id = $req->dolar;
			$sale->count_product = count($productos);
			if ($req->hasFile('fileattached')) {
				$tiempo = time();
				$payment_capture = explode('public/', $req->file('fileattached')->storeAs('public/capturas', $tiempo))[1];
				$sale->attached_file = $payment_capture;
			}
			$sale->delivery = $req->input('delivery');
			$sale->user_id  = $user;
			$sale->save();

			if ($req->input('delivery') == "si") {//VERIFICA SI LA OPCION SE SERVICIO DE DELIVERY ES SI
		
				$delivery = new Delivery();//CREA UN NUEVO DELIVERY
				$delivery->sale_id = $sale->id;
				
				if ($req->forma_delivery == "" && $req->input_direc_anteriores == "") {

					return redirect()->back()->withErrors(['Agregue alguna direccion.']);
				}

				if ($req->forma_delivery == 1 || $req->forma_delivery == 2) {//SI LA OPCION ES ESCRIBIR LA DIRECCION O BUSCARLA
				$address_delivery = new AddressUserDelivery();
				$address_delivery->user_id = $user;

					if ($req->forma_delivery == 1 ) {
						$address_delivery->details =  $req->direc_descrip_area;
					}else{
						$travel = new TravelRate();
						$travel->sector_id = $req->sector_id;
						$travel->save();

						$address_delivery->details =  $req->detalles;
						$address_delivery->travel_rate_id = $travel->id;
					}

				$address_delivery->save();
				$delivery->address_user_delivery_id = $address_delivery->id;
				}else {
					//colocar direcciones anteriores
					$delivery->address_user_delivery_id = $req->input_direc_anteriores;
				}
				
				$delivery->save();
			}

			//$saleid = $sale->lastid();
			$saleid = $sale->id;

			foreach ($productos as $producto) {
				$saleDetail = new SaleDetail();
				$saleDetail->quantity   = $producto->quantity;
				$saleDetail->product_id = $producto->id;
				$saleDetail->sale_id    = $saleid;
				$saleDetail->save();
			}

			if ($req->input('user_address_delivery')) {


				$delivery = new Delivery();
				$delivery->address_user_delivery_id = $req->input('user_address_delivery');
				$delivery->sale_id = $saleid;
				$delivery->save();
			}

			\Cart::session($user)->clear();

			return redirect('/perfil')->with(['success' => 'Su compra está en proceso, puede verla en sus pedidos.', 'pedidos' => true]);
		}

		return redirect()->back()->withErrors(['No ha cargado productos al carrito.']);

	}
}
