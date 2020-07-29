<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AddressUserDelivery;
use App\TravelRate;

class AddressUserDeliveryController extends Controller
{
	public function index()
	{
		return view('customer.address_deliveries');
	}

	public function store(Request $req)
	{
		$details        = $req->input('details');
		$travel_rate_id = $req->input('travel_id');
		$user_id        = auth()->user()->id;

		$user_address = new AddressUserDelivery();

		$user_address->details        = $details;
		$user_address->travel_rate_id = $travel_rate_id;
		$user_address->user_id        = $user_id;

		$user_address->save();

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
}
