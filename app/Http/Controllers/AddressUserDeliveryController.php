<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AddressUserDelivery;

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
		$data = AddressUserDelivery::find($id);
		$data->travel_rate;

 		return $data;
	}
}
