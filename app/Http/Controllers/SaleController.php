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
use App\inventory;
use DB;

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
		$subtotal = 0;
		$iva = 0;
		$total = 0;

		if ($req->forma_delivery == 1) {

			$validator = Validator::make($req->all(), [
            'direc_descrip_area' => 'required|max:255',
    
			]);
		}else if($req->forma_delivery == 2){

			$validator = Validator::make($req->all(), [
	            'city_id' => 'required',
	            'sector_id' => 'required',
	            'detalles' => 'required'
			]);
		}
		
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
		}

		
		$user     = auth()->user()->id;
		$productos = \Cart::content();


			if (count($productos) > 0) {	

				$code     = '20-123321213';
				// 
				try{

					DB::beginTransaction();

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

					foreach ($productos as $producto) {
						//VALIDACION PARA LA CANTIDAD DE PRODUCTOS DISPONIBLES
						if ($producto->options->sale_type == "al-menor") {
							$stock = $producto->model->inventory->total_qty_prod - $producto->qty;
							if ($stock <= 0) {
								return redirect()->back()->withErrors(['no hay suficientes '. $producto->model->inventory->product_name. " para la venta"]);
							}
							//RESTAMOS DEL STOCK
							$inventario = Inventory::findOrFail($producto->model->inventory->id);

							$inventario->total_qty_prod -= $producto->qty;
							$inventario->quantity = $inventario->total_qty_prod / $inventario->qty_per_unit;
							$inventario->save();

						}else{
							$stock = $producto->model->inventory->quantity - $producto->qty;
							if ($stock <= 0) {
								return redirect()->back()->withErrors(['no hay suficientes '. $producto->model->inventory->product_name. " para la venta"]);
							}
							//RESTAMOS DEL STOCK
							$inventario = Inventory::findOrFail($producto->model->inventory->id);

							$inventario->quantity -= $producto->qty;
							$inventario->total_qty_prod = $inventario->quantity * $inventario->qty_per_unit;
							$inventario->save();
						}
						$saleDetail = new SaleDetail();
						$saleDetail->quantity   = $producto->qty;
						$saleDetail->type = $producto->options->sale_type;
						$saleDetail->product_id = $producto->model->id;
						$saleDetail->sale_id    = $sale->id;

						if ($saleDetail->type == "al-mayor") {
							
						$saleDetail->sub_total = $producto->model->wholesale_packet_price * $producto->qty;
						$saleDetail->iva = ($producto->model->wholesale_iva_amount * $producto->model->inventory->qty_per_unit) * $producto->qty;
						$saleDetail->amount = $saleDetail->sub_total + $saleDetail->iva;
						$subtotal += $saleDetail->sub_total;
						$iva += $saleDetail->iva;
						$total += $saleDetail->amount;
						}else{

						$saleDetail->sub_total = ($producto->model->retail_total_price - $producto->model->retail_iva_amount) * $producto->qty;

						$saleDetail->iva = $producto->model->retail_iva_amount * $producto->qty;

						$saleDetail->amount = $producto->model->retail_total_price * $producto->qty;

						$subtotal += $saleDetail->sub_total;
						$iva += $saleDetail->iva;
						$total += $saleDetail->amount;
						}
						

						$saleDetail->save();
					}

					$sale->sub_total = $subtotal;
					$sale->iva = $iva;
					//$sale->amount = $total;
					$sale->save();

					if ($req->input('user_address_delivery')) {


						$delivery = new Delivery();
						$delivery->address_user_delivery_id = $req->input('user_address_delivery');
						$delivery->sale_id = $sale->id;
						$delivery->save();
					}

					\Cart::destroy();

					DB::commit();
				}catch(Exception $e){

					DB::rollback();
				}
		

			return redirect('/perfil')->with(['success' => 'Su compra está en proceso, puede verla en sus pedidos.', 'pedidos' => true]);
			}


		return redirect()->back()->withErrors(['No ha cargado productos al carrito.']);

	}
}
