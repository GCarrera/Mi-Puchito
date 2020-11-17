<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AddressUserDelivery;
use App\TravelRate;
use DB;

class AddressUserDeliveryController extends Controller
{
	public function index()
	{
		return view('customer.address_deliveries');
	}

	public function store(Request $req)
	{
		$user_id = auth()->user()->id;
		/*
		$details        = $req->input('details');
		$travel_rate_id = $req->input('travel_id');

		$user_address = new AddressUserDelivery();

		$user_address->details        = $details;
		$user_address->travel_rate_id = $travel_rate_id;
		$user_address->user_id        = $user_id;

		$user_address->save();
		*/
		try{
			DB::beginTransaction();

			if ($req->forma_delivery == 1 || $req->forma_delivery == 2) {//SI LA OPCION ES ESCRIBIR LA DIRECCION O BUSCARLA
				$address_delivery = new AddressUserDelivery();
				$address_delivery->user_id = $user_id;

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
			}

			DB::commit();
		}catch(Exception $e){

			DB::rollback();

		}
		return back()->with('success', 'Dirección guardada correctamente.');
	}

	public function show($id)
	{
		$travel = TravelRate::where('sector_id', $id);
		if ($travel->count() == 0) {	 
			return response()->json(['travel_rate' => ['stimated_time' => 0, 'rate' => 0], 'details' => 'No hay tiempos estimados registrados.']);
		}
		$travel = $travel->first();
		$data = AddressUserDelivery::where('travel_rate_id', $travel->id)->first();
		$data->travel_rate;
 		return $data;
	}

	public function destroy($id)
	{
		try{
			DB::beginTransaction();
			
			$address = AddressUserDelivery::findOrFail($id);
			if ($address->travel_rate_id != null) {

				$rate = TravelRate::findOrFail($address->travel_rate_id);
				$rate->delete();
			}
			$address->delete();

			DB::commit();
		}catch(Exception $e){

			DB::rollback();
		}

		return back()->with('success', 'Direccion eliminada correctamente');
	}
}
