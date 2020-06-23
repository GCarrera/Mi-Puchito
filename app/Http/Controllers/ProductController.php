<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Inventory;
use App\Product;

class ProductController extends Controller
{
	public function __construct()
	{
		$this->middleware('admin')->except('show');
	}

	public function index()
	{
//
	}

	public function store(Request $req)
	{
		$product = new Product();

		$product->cost                   = $req->input('cost');
		$product->iva_percent            = $req->input('iva_percent');
		$product->retail_margin_gain     = $req->input('retail_margin_gain');
		// $product->retail_pvp             = $req->input('retail_pvp');
		$product->retail_total_price     = $req->input('retail_total_price');
		$product->retail_iva_amount      = $req->input('retail_iva_amount');
		$product->image                  = explode('public/', $req->file('fileinput')->store('public'))[1];
		$product->wholesale_margin_gain  = $req->input('wholesale_margin_gain');
		// $product->wholesale_pvp          = $req->input('wholesale_pvp');
		$product->wholesale_packet_price = $req->input('wholesale_packet_price');
		$product->wholesale_total_individual_price = $req->input('wholesale_total_individual_price');
		$product->wholesale_total_packet_price     = $req->input('wholesale_total_packet_price');
		$product->wholesale_iva_amount   = $req->input('wholesale_iva_amount');
		$product->inventory_id           = $req->input('product');

		$product->save();

		$inventory = Inventory::find($req->input('product'));
		$inventory->status = 1;
		$inventory->save();

		return redirect()->back()->with('success', 'Producto registrado exitosamente.');
	}

	public function show($id)
	{
		$product    = Product::find($id);

		$inventory  = $product->inventory;
		$category   = $product->inventory->category;
		$enterprise = $product->inventory->enterprise;
		$warehouse  = $product->inventory->warehouse;

		return $product;
	}

	public function update(Request $request, $id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}
}
