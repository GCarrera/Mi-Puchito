<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\City;
use App\Sector;
use App\TravelRate;

class TravelRateController extends Controller
{
	public function traer_ciudad($estado)
	{
		return City::where('state_id', $estado)->get();
	}

	public function traer_sectores($ciudad)
	{
		return Sector::where('city_id', $ciudad)->get();
	}

	public function show($id)
	{
		$data = TravelRate::find($id);

		$d = ['sector' => $data->sector, 'rate' => $data];
 		return $d;
	}

	public function store(Request $req)
	{
		$tarifa = $req->input('tarifa');
		$sector = $req->input('sector');
		$stimated_time = $req->input('stimated_time');

		$travelrate = new TravelRate();

		$travelrate->rate          = $tarifa;
		$travelrate->stimated_time = $stimated_time;
		$travelrate->sector_id     = $sector;

		$travelrate->save();

		return back()->with('success', 'La tarifa a sido asignada.');
	}

	public function update(Request $req, $id)
	{
		$tarifa = $req->input('tarifa');
		$sector = $req->input('sector');
		$stimated_time = $req->input('stimated_timeedit');

		$travelrate = TravelRate::find($id);

		$travelrate->rate          = $tarifa;
		$travelrate->stimated_time = $stimated_time;
		$travelrate->sector_id     = $sector;

		$travelrate->save();

		return back()->with('success', 'La tarifa a sido editada.');
	}

	public function destroy($id)
	{
		$model = TravelRate::destroy($id);

		return back()->with('success', 'La tarifa a sido eliminada.');
	}
}
