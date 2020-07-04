<?php

// 24432495 => wifi passwd

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sale;
use App\Delivery;
use App\SaleDetail;

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
		// $type     = $req->input('type');
		$monto    = $req->input('monto');
		$code     = '20-123321213';
		$delivery = $req->input('delivery');
		$user     = auth()->user()->id;
		// $payment_capture = $req->file('capture_payment')->store('capturas');
		$payment_reference_code = $req->input('numero_referencia');

		$sale = new Sale();

		$sale->code     = $code;
		// $sale->type     = $type;
		$sale->amount   = $monto;
		$sale->payment_reference_code = $payment_reference_code;
		// $sale->payment_capture = $payment_capture;
		$sale->delivery = $delivery;
		$sale->user_id  = $user;

		$sale->save();

		$saleid = $sale->lastid();

		// guardar en sales details
		$productos = \Cart::session($user)->getContent();

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

		return redirect('/perfil')->with('success', 'Su compra está en proceso, puede verla en sus pedidos.');
	}
}
